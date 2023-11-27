<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Entities;

interface Payer
{
    /**
     * Determines if the payer is a registered Working Holiday Maker employer.
     *
     * In order to withhold tax for employees at a Working Holiday Maker rate, the payer must be registered as a
     * Working Holiday Maker employer with the ATO. Otherwise, the employees are classified as foreign workers.
     *
     * @return bool
     */
    public function isRegisteredWhmEmployer(): bool;
}
