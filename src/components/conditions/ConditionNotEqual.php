<?php
namespace extas\components\conditions;

use extas\interfaces\conditions\ICondition;

/**
 * Class ConditionNotEqual
 *
 * @package extas\components\conditions
 * @author jeyroik@gmail.com
 */
class ConditionNotEqual extends ConditionEqual
{
    /**
     * @param mixed $compareWith
     * @param ICondition $condition
     * @param mixed $compareTo
     * @return bool
     */
    public function __invoke($compareWith, ICondition $condition, $compareTo): bool
    {
        return !parent::__invoke($compareWith, $condition, $compareTo);
    }
}