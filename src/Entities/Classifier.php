<?php

namespace ManageIt\PaygTax\Entities;

/**
 * Classifier entity.
 *
 * The role of the classifier is to determine the tax scale to use for a given payer, payee and earning. The normal
 * operation of this library expects only one tax scale to be returned per scenario.
 */
interface Classifier
{
    /**
     * Get the tax scale for the given payer, payee and earning.
     *
     * Only one tax scale should be returned per scenario. If the scenario provided either has no applicable tax scale,
     * or more than one applicable tax scale, it must either return a fallback tax scale, or throw an exception.
     *
     * @throws \ManageIt\PaygTax\Exceptions\NoTaxScalesException If no tax scale is applicable to the given scenario.
     * @throws \ManageIt\PaygTax\Exceptions\MultipleTaxScalesException If more than one tax scale is applicable to the
     *    given scenario.
     */
    public function getTaxScale(Payer $payer, Payee $payee, Earning $earning): TaxScale;
}
