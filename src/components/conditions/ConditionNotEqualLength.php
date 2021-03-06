<?php
namespace extas\components\conditions;

use extas\interfaces\conditions\ICondition;

/**
 * Class ConditionNotEqualLength
 *
 * @package extas\components\conditions
 * @author jeyroik@gmail.com
 */
class ConditionNotEqualLength extends ConditionEqualLength
{
    /**
     * @param mixed $compareWith
     * @param mixed $compareTo
     * @return bool
     */
    public function __invoke($compareWith, $compareTo): bool
    {
        return !parent::__invoke($compareWith, $compareTo);
    }
}
