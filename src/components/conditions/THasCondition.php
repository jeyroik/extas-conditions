<?php
namespace extas\components\conditions;

use extas\components\SystemContainer;
use extas\interfaces\conditions\ICondition;
use extas\interfaces\conditions\IConditionRepository;
use extas\interfaces\conditions\IHasCondition;

/**
 * Trait THasCondition
 *
 * @property $config
 * @method getValue()
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
        /**
         * @var $repo IConditionRepository
         */
        $repo = SystemContainer::getItem(IConditionRepository::class);

        return $repo->one([ICondition::FIELD__ALIASES => $this->getConditionName()]);
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
        $conditionDispatcher = $condition->buildClassWithParameters();

        return $conditionDispatcher($compareWith, $condition, $this->getValue());
    }
}
