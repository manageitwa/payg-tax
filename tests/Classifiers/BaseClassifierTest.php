<?php

namespace ManageIt\PaygTax\Tests\Classifiers;

use ManageIt\PaygTax\Classifiers\BaseClassifier;
use ManageIt\PaygTax\TaxScales\Nat1004;
use ManageIt\PaygTax\TaxScales\Nat3539;
use ManageIt\PaygTax\TaxScales\Nat4466;
use ManageIt\PaygTax\TaxScales\Nat75331;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ManageIt\PaygTax\Classifiers\BaseClassifier
 */
class BaseClassifierTest extends TestCase
{
    protected BaseClassifier $classifier;

    public function setUp(): void
    {
        $this->classifier = new BaseClassifier();
    }

    public function testNat1004(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat1004::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat3539(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = true;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat3539::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat4466(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_SINGLE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat4466::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat75331(): void
    {
        $payer = new Payer();
        $payer->whmEmployer = true;

        $payee = new Payee();
        $payee->residencyStatus = Payee::WORKING_HOLIDAY_MAKER;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat75331::class, $this->classifier->getTaxScale($payer, $payee, $earning));

        $payer->whmEmployer = false;
        Assert::assertInstanceOf(Nat1004::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }
}
