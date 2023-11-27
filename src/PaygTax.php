<?php

declare(strict_types=1);

namespace ManageIt\PaygTax;

use ManageIt\PaygTax\Classifiers\BaseClassifier;
use ManageIt\PaygTax\Entities\Classifier;
use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;

/**
 * Tax calculator.
 *
 * This is the main entrypoint for this library, and is used to provide a scenario in which the tax to withhold will
 * be calculated for.
 */
class PaygTax
{
    protected Classifier $classifier;

    protected Payer $payer;

    protected Payee $payee;

    protected Earning $earning;

    final public function __construct(Classifier $classifier = null)
    {
        $this->classifier = $classifier ?? new BaseClassifier();
    }

    /**
     * Creates a new tax calculation scenario.
     *
     * @return static
     */
    public static function new(Classifier $classifier = null)
    {
        return new static($classifier);
    }

    /**
     * Sets the classifier to use for this tax calculation.
     *
     * Fluent method.
     *
     * @return static
     */
    public function withClassifier(Classifier $classifier)
    {
        $this->classifier = $classifier;

        return $this;
    }

    /**
     * Get the payer.
     */
    public function getPayer(): Payer
    {
        return $this->payer;
    }

    /**
     * Sets the payer for this tax calculation.
     *
     * Fluent method.
     *
     * @return static
     */
    public function setPayer(Payer $payer)
    {
        $this->payer = $payer;

        return $this;
    }

    /**
     * Get the payee.
     */
    public function getPayee(): Payee
    {
        return $this->payee;
    }

    /**
     * Sets the payee for this tax calculation.
     *
     * Fluent method.
     *
     * @return static
     */
    public function setPayee(Payee $payee)
    {
        $this->payee = $payee;

        return $this;
    }

    /**
     * Get the earning.
     */
    public function getEarning(): Earning
    {
        return $this->earning;
    }

    /**
     * Sets the earning for this tax calculation.
     *
     * Fluent method.
     *
     * @return static
     */
    public function setEarning(Earning $earning)
    {
        $this->earning = $earning;

        return $this;
    }

    /**
     * Get the tax withheld amount for the given scenario.
     *
     * @throws \ManageIt\PaygTax\Exceptions\NoTaxScalesException If no tax scale could be found for this scenario.
     * @throws \ManageIt\PaygTax\Exceptions\MultipleTaxScalesException If multiple tax scales were found for this scenario.
     */
    public function getTaxWithheldAmount(): float
    {
        $scale = $this->classifier->getTaxScale($this->payer, $this->payee, $this->earning);
        $tax = $scale->getTaxWithheldAmount($this->payer, $this->payee, $this->earning);

        foreach ($this->payee->getAdjustments() as $adjustment) {
            if ($adjustment->isEligible($this->payer, $this->payee, $scale, $this->earning)) {
                $tax += $adjustment->getAdjustmentAmount($this->payer, $this->payee, $scale, $this->earning);
            }
        }

        return $tax;
    }
}
