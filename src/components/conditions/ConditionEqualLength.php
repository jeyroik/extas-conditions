<?php
namespace extas\components\conditions;

use extas\components\plugins\Plugin;
use extas\interfaces\conditions\ICondition;
use extas\interfaces\conditions\IConditionDispatcher;

/**
 * Class ConditionEqualLength
 *
 * @package extas\components\conditions
 * @author jeyroik@gmail.com
 */
class ConditionEqualLength extends Plugin implements IConditionDispatcher
{
    /**
     * @param mixed $compareWith
     * @param ICondition $condition
     * @param mixed $compareTo
     *
     * @return bool
     */
    public function __invoke($compareWith, ICondition $condition, $compareTo): bool
    {
        $one = (string) $compareWith;
        $two = (string) $compareTo;

        return strlen($one) == strlen($two);
    }
}