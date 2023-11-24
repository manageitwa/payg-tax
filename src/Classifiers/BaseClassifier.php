<?php

namespace ManageIt\PaygTax\Classifiers;

use ManageIt\PaygTax\Entities\Classifier;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\TaxScale;
use ManageIt\PaygTax\Exceptions\NoTaxScalesException;

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
        $scales = $this->availableTaxScales();
        $applicable = [];

        foreach ($scales as $scale) {
            if ($scale->isEligible($payer, $payee, $earning)) {
                $applicable[] = $scale;
            }
        }

        if (count($applicable) === 0) {
            throw new NoTaxScalesException('Unable to find a tax scale that is applicable for the given scenario.');
        }

        if (count($applicable) > 1) {
            throw new NoTaxScalesException('More than one tax scale is applicable for the given scenario.');
        }

        return $applicable[0];
    }

    /**
     * Gets all available tax scales in the library.
     *
     * Returns a set of instances of each tax scale.
     *
     * @return TaxScale[]
     */
    public function availableTaxScales(): array
    {
        $taxScaleDir = realpath(__DIR__ . '/../TaxScales/');

        if ($taxScaleDir === false) {
            return [];
        }

        $taxScales = [];

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($taxScaleDir),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        /** @var \SplFileObject $file */
        foreach ($files as $file) {
            if ($file->isFile() && $file->getRealPath() !== false) {
                $className = $this->convertPathToClassName($file->getRealPath());

                if (is_null($className)) {
                    continue;
                }

                try {
                    $instance = new $className();
                    if (!$instance instanceof TaxScale) {
                        continue;
                    }
                } catch (\Throwable $e) {
                    continue;
                }

                $taxScales[] = $instance;
            }
        }

        return $taxScales;
    }

    protected function convertPathToClassName(string $path): string|null
    {
        $taxScaleDir = realpath(__DIR__ . '/../TaxScales/');

        if ($taxScaleDir === false) {
            return null;
        }

        return 'ManageIt\\PaygTax\\TaxScales'
            . str_replace('/', '\\', str_replace('.php', '', str_replace($taxScaleDir, '', $path)));
    }
}
