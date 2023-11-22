<?php

namespace ManageIt\PaygTax\Entities;

interface Payee
{
    // Residency status

    /**
     * The payee is an Australian resident for tax purposes.
     */
    public const RESIDENT = 0;
    /**
     * The payee is a foreign resident of Australia for tax purposes.
     */
    public const FOREIGN_RESIDENT = 1;
    /**
     * The payee is a working holiday maker, working in Australia under an applicable working holiday visa.
     */
    public const WORKING_HOLIDAY_MAKER = 2;

    // Pay cycle

    /**
     * The payee is paid on a casual basis. This is the same as being paid daily.
     */
    public const PAY_CYCLE_CASUAL = 0;
    /**
     * The payee is paid on a daily basis.
     */
    public const PAY_CYCLE_DAILY = 0;
    /**
     * The payee is paid on a weekly basis.
     */
    public const PAY_CYCLE_WEEKLY = 1;
    /**
     * The payee is paid on a fortnightly basis.
     */
    public const PAY_CYCLE_FORTNIGHTLY = 2;
    /**
     * The payee is paid on a monthly basis.
     */
    public const PAY_CYCLE_MONTHLY = 3;
    /**
     * The payee is paid on a quarterly basis (every 3 months).
     */
    public const PAY_CYCLE_QUARTERLY = 4;

    /**
     * Gets the residency status of this payee.
     *
     * This method should return one of the residency status constants provided in this interface:
     *
     *  - `Payee::RESIDENT`: For Australian residents.
     *  - `Payee::FOREIGN_RESIDENT`: For foreign residents.
     *  - `Payee::WORKING_HOLIDAY_MAKER`: For employees working under working holiday visas.
     */
    public function getResidencyStatus(): int;

    /**
     * Determines if this payee has a tax file number.
     *
     * This should return `false` if the payee does not have a tax file number or is using the special TFN for not
     * providing a TFN (`000000000`).
     *
     * This should return `true` if the the payee does have a tax file number, or is using one of the following special
     * TFNs to claim an exemption or current application for a TFN: `111111111`, `333333333` or `444444444`.
     *
     * @return bool
     */
    public function hasTfnNumber(): bool;

    /**
     * Gets the pay cycle for this payee.
     *
     * This method should return one of the `PAY_CYCLE` constants from this interface.
     */
    public function getPayCycle(): int;

    /**
     * Determines if the payee is claiming the tax-free threshold.
     *
     * Note that while a payee might claim the tax-free threshold, this does not indicate that they are actually
     * eligible for claiming this threshold.
     */
    public function claimsTaxFreeThreshold(): bool;

    /**
     * Determines if the payee has a Study and Training Support Loan (STSL) debt.
     *
     * This debt can be any of the following:
     *
     * - Higher Education Loan Program (HELP)
     * - VET Student Loan (VSL)
     * - Financial Supplement (FS)
     * - Student Start-up Loan (SSL)
     * - Trade Support Loan (TSL)
     *
     * @return bool
     */
    public function hasSTSLDebt(): bool;

    /**
     * Gets the tax adjustments that this payee is claiming.
     *
     * Please note that this will return all adjustments that the payee is claiming, including adjustments that may not
     * be eligible for the payee or earning.
     *
     * @return \ManageIt\PaygTax\Entities\TaxAdjustment[]
     */
    public function getAdjustments(): array;
}
