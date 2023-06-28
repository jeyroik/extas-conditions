<?php
namespace extas\interfaces\conditions;

use extas\interfaces\IHasValue;

/**
 * Interface IHasCondition
 *
 * @package extas\interfaces\conditions
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IHasCondition extends IHasValue
{
    public const FIELD__CONDITION = 'condition';

    /**
     * @return string
     */
    public function getConditionName(): string;

    /**
     * @return ICondition|null
     */
    public function getCondition(): ?ICondition;

    /**
     * @param string $name
     * @return $this
     */
    public function setConditionName(string $name);

    /**
     * Expect getValue() method is exist.
     *
     * @param mixed $compareWith
     * @return bool
     */
    public function isConditionMet($compareWith): bool;
}
