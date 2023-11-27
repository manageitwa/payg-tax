<?php

namespace ManageIt\PaygTax\Tests\Classifiers;

use ManageIt\PaygTax\Classifiers\BaseClassifier;
use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale1;
use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale2;
use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale3;
use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale4;
use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale5;
use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale6;
use ManageIt\PaygTax\TaxScales\October2020\Nat3539Scale1;
use ManageIt\PaygTax\TaxScales\October2020\Nat3539Scale2;
use ManageIt\PaygTax\TaxScales\October2020\Nat3539Scale3;
use ManageIt\PaygTax\TaxScales\October2020\Nat3539Scale5;
use ManageIt\PaygTax\TaxScales\October2020\Nat3539Scale6;
use ManageIt\PaygTax\TaxScales\October2020\Nat4466Couple;
use ManageIt\PaygTax\TaxScales\October2020\Nat4466IllnessSeparated;
use ManageIt\PaygTax\TaxScales\October2020\Nat4466Single;
use ManageIt\PaygTax\TaxScales\October2020\Nat75331;
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

    public function testAvailableTaxScales(): void
    {
        $scales = $this->classifier->availableTaxScales();

        Assert::assertIsArray($scales);
        Assert::assertCount(25, $scales);
        Assert::assertContainsOnlyInstancesOf(\ManageIt\PaygTax\Entities\TaxScale::class, $scales);
    }

    public function testNat1004Scale1(): void
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

        Assert::assertInstanceOf(Nat1004Scale1::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat1004Scale2(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat1004Scale2::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat1004Scale3(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat1004Scale3::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat1004Scale4(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        $payee->tfn = false;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat1004Scale4::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat1004Scale5(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat1004Scale5::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat1004Scale6(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat1004Scale6::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat3539Scale1(): void
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

        Assert::assertInstanceOf(Nat3539Scale1::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat3539Scale2(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = true;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat3539Scale2::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat3539Scale3(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = true;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat3539Scale3::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat3539Scale5(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = true;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat3539Scale5::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat3539Scale6(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = true;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat3539Scale6::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat4466Single(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = true;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_SINGLE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat4466Single::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat4466IllnessSeparated(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = true;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_ILLNESS_SEPARATED;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat4466IllnessSeparated::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat4466Couple(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = true;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_COUPLE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat4466Couple::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }

    public function testNat75331(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::WORKING_HOLIDAY_MAKER;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = true;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2020-10-15');
        $earning->gross = 1000;

        Assert::assertInstanceOf(Nat75331::class, $this->classifier->getTaxScale($payer, $payee, $earning));
    }
}
