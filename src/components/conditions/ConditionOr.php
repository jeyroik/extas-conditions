<?php
namespace extas\components\conditions;

use extas\interfaces\conditions\IConditionDispatcher;
use extas\interfaces\conditions\IHasCondition;

/**
 * Class ConditionOr
 *
 * @package extas\components\conditions
 * @author jeyroik@gmail.com
 */
class ConditionOr extends ConditionAnd implements IConditionDispatcher
{
    /**
     * @param $result
     * @param IHasCondition $sub
     * @param $compareWith
     * @return bool
     */
    protected function updateResult($result, IHasCondition $sub, $compareWith)
    {
        return is_null($result)
            ? $sub->isConditionTrue($compareWith)
            : ($result || $sub->isConditionTrue($compareWith));
    }
}
