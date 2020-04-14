<?php
namespace extas\components\conditions;

use extas\components\plugins\Plugin;
use extas\interfaces\conditions\ICondition;
use extas\interfaces\conditions\IConditionDispatcher;

/**
 * Class ConditionIn
 *
 * @package extas\components\conditions
 * @author jeyroik@gmail.com
 */
class ConditionIn extends Plugin implements IConditionDispatcher
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
        $compareTo = is_array($compareTo) ? $compareTo : [$compareTo];

        if (is_array($compareWith)) {
            $intersect = array_intersect($compareWith, $compareTo);
            return count($intersect) >= count($compareWith);
        } else {
            return in_array($compareWith, $compareTo);
        }
    }
}
