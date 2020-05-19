<?php
namespace extas\components\conditions;

use extas\components\plugins\Plugin;
use extas\interfaces\conditions\ICondition;
use extas\interfaces\conditions\IConditionDispatcher;

/**
 * Class ConditionGreaterOrEqualLength
 *
 * @package extas\components\conditions
 * @author jeyroik@gmail.com
 */
class ConditionGreaterOrEqualLength extends Plugin implements IConditionDispatcher
{
    /**
     * @param mixed $compareWith
     * @param mixed $compareTo
     *
     * @return bool
     */
    public function __invoke($compareWith, $compareTo): bool
    {
        $one = (string) $compareWith;
        $two = (string) $compareTo;

        return strlen($one) >= strlen($two);
    }
}
