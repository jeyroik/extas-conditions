<?php
namespace extas\components\conditions;

use extas\components\plugins\Plugin;
use extas\interfaces\conditions\ICondition;
use extas\interfaces\conditions\IConditionDispatcher;

/**
 * Class ConditionLower
 *
 * @package extas\components\conditions
 * @author jeyroik@gmail.com
 */
class ConditionLower extends Plugin implements IConditionDispatcher
{
    /**
     * @param mixed $compareWith
     * @param mixed $compareTo
     *
     * @return bool
     */
    public function __invoke($compareWith, $compareTo): bool
    {
        $one = (int) $compareWith;
        $two = (int) $compareTo;

        return $one < $two;
    }
}
