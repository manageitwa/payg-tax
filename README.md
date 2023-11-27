# Australian PAYG Tax Calculator

This library allows for the calculation of income tax on gross payments as per the rules of the [Australian Tax Office](https://www.ato.gov.au). It supports all standard tax scales and the most common tax offsets and adjustments, including the Seniors and Pensioners Offset, Medicare Levy Reduction and Exemption, Study and Training Support Loans and Working Holiday Makers.

This library only supports calculating tax for gross payments after 13th October 2020 (FY2020-21 tax table release).

## Requirements

- PHP 7.4 or PHP 8.0, and above.

## Installation

This library can be installed via [Composer](https://getcomposer.org)

```
composer require manageitwa/payg-tax
```

## Usage

### General usage

This library is built to be able to be slotted in to most PHP software. The library publishes a number of interfaces within the `src/Entities` directory. At a bare minimum, you would need to fulfill the following interfaces:

- `Payer`: A payer record. The payer is generally the employer or the entity who is paying the payee.
- `Payee`: A payee record. The payee is the employee or entity who is being paid the gross income and needs income tax to be withheld from their earning.
- `Earning`: An earning record. The earning is a single gross amount of income that is paid either ad-hoc or as part of a pay cycle to the payee.

In general, you would like implement these interfaces into a model record for each of these entities.

```php
<?php

namespace Your\App;

use ManageIt\PaygTax\Entities\Payee;

class Employee implements Payee
{
    // ...
}
```

You would then need to ensure that your model or class contains all the required methods specified by the interface. All interfaces and library files are strict typed to ensure data integrity.

Once you have your records correctly implementing the entitiy interfaces, you may use the `\ManageIt\PaygTax\PaygTax` class as an entrypoint to provide the entity records as a scenario and calculate the tax withheld for that scenario.

```php
use ManageIt\PaygTax\PaygTax;

$payer = new Payer();
$payee = new Payee();
$earning = new Earning();

$tax = PaygTax::new()
    ->setPayer($payer)
    ->setPayee($payee)
    ->setEarning($earning)
    ->getTaxWithheldAmount();
```

### Including offsets and adjustments

Payees may require tax offsets, debts and adjustments to be applied to their tax withholding amount in order to fulfill obligations to the ATO. You need to apply adjustments to the `Payee` record. For example, a payee who is at the age that they are applicable for the Australian pension may wish to use the Seniors and Pensioners tax offset.

The library does not mind how you implement this into your model / system, as long as the `Payee` record has a `getAdjustments()` method that returns the adjustments as an array.

```php
<?php

namespace Your\App;

use ManageIt\PaygTax\Entities\Payee;

class Employee implements Payee
{
    // ...

    public function getAdjustments(): array
    {
        if ($this->medicareLevyReduction) {
            return [
                new \ManageIt\PaygTax\Adjustments\October2020\MedicareLevyReduction(true, 2),
            ];
        }
    }

    // ...
}
```

Offsets and adjustments are applied *after* the initial tax withholding amount is calculated.

### Custom adjustments

If this software does not cover a particular adjustments that might be applied to the tax, you may create your own adjustments and ensure that it is returned by the `getAdjustments()` method for the `Payee` record. You may use the `isEligible()` method in the custom adjustment to determine the eligibility of the adjustment based on the payer, payee, tax scale and earning applied in the scenario.


```php
<?php

namespace Your\App;

use ManageIt\PaygTax\Entities\TaxAdjustment;

class MyCustomOffset implements TaxAdjustment
{
    // ...

    public function isEligible(
        \ManageIt\PaygTax\Entities\Payer $payer,
        \ManageIt\PaygTax\Entities\Payee $payee,
        \ManageIt\PaygTax\Entities\TaxScale $taxScale,
        \ManageIt\PaygTax\Entities\Earning $earning
    ): bool {
        return $this->needsCustomOffset;
    }

    public function getAdjustmentAmount(
        \ManageIt\PaygTax\Entities\Payer $payer,
        \ManageIt\PaygTax\Entities\Payee $payee,
        \ManageIt\PaygTax\Entities\TaxScale $taxScale,
        \ManageIt\PaygTax\Entities\Earning $earning
    ): float {
        // Take 10 dollars away from the initial withholding
        return -10;
    }

    // ...
}
```

### Tax scale classification

By default, the library uses tax scale and adjustment eligibility methods to work out the applicable tax scales and available adjustments to apply to a given scenario. These are the `isEligible()` methods defined in both the `TaxScale` and `TaxAdjustment` entities. It is expected that only one tax scale will apply to any given scenario - any number of adjustments may be applied however.

In some rare cases, this may result in a particular scenario not having an applicable tax scale, or more than one tax scale being applicable. In these cases, either a `NoTaxScalesException` or `MultipleTaxScalesException` will be thrown, respectively.

You may choose to use a different tax scale classifier if you wish to change how the system determines the applicability of the tax scales. Note that the `getTaxScale()` method should still return one applicable tax scale per scenario.

## Disclaimer

While Manage It Pty Ltd has taken great care to ensure the accuracy of the tax withholding calculations in this library, including test cases that match to the ATO sample data and static analysis, it does not cover 100% of the potential offsets or adjustments that can be made to a payee's taxation responsibility and does not take into account your personal, financial and taxation situation. You should always verify any calculations with a registered tax agent.

Manage It Pty Ltd accepts no responsibility for miscalculations or assumptions that are made through the use of this the library that result in you withholding too much or too little tax in an earning.

## License

This library is dual-licensed. For non-commercial usage, this software is available under the terms of the GNU General Public Licence (GNU GPL) version 3. The terms of this license are available in the `LICENSE` document included in this library.

For commercial usage - for example but not limited to, implementation in a commercial product or usage for a commercial service, please get in touch with Manage It Pty Ltd on info@manageit.com.au or by phone on (+61) 08 9380 0271 to discuss a commercial license.
