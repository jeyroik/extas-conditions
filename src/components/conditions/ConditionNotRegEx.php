<?php
namespace extas\components\conditions;

use extas\interfaces\conditions\ICondition;
use extas\interfaces\conditions\IConditionDispatcher;

/**
 * Class ConditionNotRegEx
 *
 * @package extas\components\conditions
 * @author jeyroik@gmail.com
 */
class ConditionNotRegEx extends ConditionRegEx implements IConditionDispatcher
{
    /**
     * @param mixed $compareWith
     * @param mixed $compareTo
     *
     * @return bool
     */
    public function __invoke($compareWith, $compareTo): bool
    {
        return !parent::__invoke($compareWith, $compareTo);
    }
}
