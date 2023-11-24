<?php

namespace ManageIt\PaygTax\Exceptions;

/**
 * No applicable tax scales exception.
 *
 * This should be thrown by a Classifier if no tax scale is applicable to the given scenario.
 */
class NoTaxScalesException extends \Exception
{
}
