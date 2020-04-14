<?php
namespace extas\interfaces\conditions;

use extas\interfaces\plugins\IPlugin;

/**
 * Interface IConditionDispatcher
 *
 * @package extas\interfaces\conditions
 * @author jeyroik@gmail.com
 */
interface IConditionDispatcher extends IPlugin
{
    /**
     * @param mixed $compareWith
     * @param ICondition $condition
     * @param mixed $compareTo
     *
     * @return bool
     */
    public function __invoke($compareWith, ICondition $condition, $compareTo): bool;
}
