<?php
namespace extas\components\conditions;

use extas\interfaces\conditions\ICondition;
use extas\interfaces\conditions\IHasCondition;

/**
 * Trait THasCondition
 *
 * @property $config
 * @method getValue()
 * @method conditionRepository()
 *
 * @package extas\components\conditions
 * @author jeyroik <jeyroik@gmail.com>
 */
trait THasCondition
{
    /**
     * @return string
     */
    public function getConditionName(): string
    {
        return $this->config[IHasCondition::FIELD__CONDITION] ?? '';
    }

    /**
     * @return ICondition|null
     */
    public function getCondition(): ?ICondition
    {
        return $this->conditionRepository()->one([ICondition::FIELD__ALIASES => $this->getConditionName()]);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setConditionName(string $name)
    {
        $this->config[IHasCondition::FIELD__CONDITION] = $name;

        return $this;
    }

    /**
     * @param mixed $compareWith
     * @return bool
     * @throws \Exception
     */
    public function isConditionTrue($compareWith): bool
    {
        $condition = $this->getCondition();

        if (!$condition) {
            throw new \Exception('Unknown condition "' . $this->getConditionName() . '"');
        }
        $conditionDispatcher = $condition->buildClassWithParameters($condition->__toArray());

        return $conditionDispatcher($compareWith, $this->getValue());
    }
}
