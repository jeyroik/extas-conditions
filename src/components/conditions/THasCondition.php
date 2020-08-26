<?php
namespace extas\components\conditions;

use extas\components\exceptions\conditions\ExceptionUnknownCondition;
use extas\interfaces\conditions\ICondition;
use extas\interfaces\conditions\IHasCondition;
use extas\interfaces\repositories\IRepository;

/**
 * Trait THasCondition
 *
 * @property $config
 * @method getValue()
 * @method IRepository conditions()
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
        return $this->conditions()->one([ICondition::FIELD__ALIASES => $this->getConditionName()]);
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
     * @throws ExceptionUnknownCondition
     */
    public function isConditionTrue($compareWith): bool
    {
        $condition = $this->getCondition();

        if (!$condition) {
            throw new ExceptionUnknownCondition($this->getConditionName());
        }
        $conditionDispatcher = $condition->buildClassWithParameters($condition->__toArray());

        return $conditionDispatcher($compareWith, $this->getValue());
    }
}
