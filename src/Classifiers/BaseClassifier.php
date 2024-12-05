<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Classifiers;

use ManageIt\PaygTax\Entities\Classifier;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\TaxScale;
use ManageIt\PaygTax\Exceptions\NoTaxScalesException;
use ManageIt\PaygTax\TaxScales\Nat1004;
use ManageIt\PaygTax\TaxScales\Nat3539;
use ManageIt\PaygTax\TaxScales\Nat4466;
use ManageIt\PaygTax\TaxScales\Nat75331;

/**
 * Base classifier.
 *
 * This is the default classifier used for this library, and simply runs through the list of tax scales included in the
 * library and finds the tax scale(s) that are applicable for the given scenario.
 */
class BaseClassifier implements Classifier
{
    public function getTaxScale(Payer $payer, Payee $payee, Earning $earning): TaxScale
    {
        // A person without a TFN must always use Nat1004.
        if ($payee->hasTfnNumber() === false) {
            return new Nat1004();
        }

        // Working Holiday Makers.
        if ($payee->getResidencyStatus() === Payee::WORKING_HOLIDAY_MAKER) {
            return new Nat75331();
        }

        // People with STSL debts.
        if ($payee->hasSTSLDebt()) {
            return new Nat3539();
        }

        // Australian Residents claiming the Seniors and Pensioners Tax Offset.
        if ($payee->getResidencyStatus() === Payee::RESIDENT && $payee->getSeniorsOffset() !== Payee::SENIORS_OFFSET_NONE) {
            return new Nat4466();
        }

        return new Nat1004();
    }
}
