<?php

namespace ManageIt\PaygTax\Exceptions;

/**
 * Multiple applicable tax scales exception.
 *
 * This should be thrown by a Classifier if more than one tax scale is applicable to the given scenario.
 */
class MultipleTaxScalesException extends \Exception
{
}
