<?php
namespace extas\interfaces\extensions;

use extas\interfaces\conditions\ICondition;
use extas\interfaces\conditions\IHasCondition;

/**
 * Interface IExtensionHasCondition
 *
 * @package extas\interfaces\extensions
 * @author jeyroik@gmail.com
 */
interface IExtensionHasCondition
{
    /**
     * @return string
     * @throws \Exception
     */
    public function getConditionName(): string;

    /**
     * @return ICondition|null
     * @throws \Exception
     */
    public function getCondition(): ?ICondition;

    /**
     * @param string $name
     * @return $this
     * @throws \Exception
     */
    public function setConditionName(string $name);

    /**
     * Expect getValue() method is exist.
     *
     * @param mixed $compareWith
     * @return bool
     * @throws \Exception
     */
    public function isConditionMet($compareWith): bool;
}
