<?php
namespace extas\components\extensions;

use extas\components\conditions\WithConditions;
use extas\interfaces\conditions\ICondition;
use extas\interfaces\conditions\IHasCondition;
use extas\interfaces\extensions\IExtensionHasCondition;
use extas\interfaces\IItem;

/**
 * Class ExtensionHasCondition
 *
 * @package extas\components\extensions
 * @author jeyroik@gmail.com
 */
class ExtensionHasCondition extends Extension implements IExtensionHasCondition
{
    /**
     * @param mixed $compareWith
     * @param IItem|null $item
     * @return bool
     * @throws \Exception
     */
    public function isConditionTrue($compareWith, IItem $item = null): bool
    {
        if (isset($item[IHasCondition::FIELD__CONDITION])) {
            $withConditions = new WithConditions($item->__toArray());
            return $withConditions->isConditionTrue($compareWith);
        }

        throw new \Exception('Missed ' . IHasCondition::FIELD__CONDITION . ' parameter');
    }

    /**
     * @param IItem|null $item
     * @return string
     * @throws \Exception
     */
    public function getConditionName(IItem $item = null): string
    {
        if (isset($item[IHasCondition::FIELD__CONDITION])) {
            $withConditions = new WithConditions($item->__toArray());
            return $withConditions->getConditionName();
        }

        throw new \Exception('Missed ' . IHasCondition::FIELD__CONDITION . ' parameter');
    }

    /**
     * @param string $name
     * @param IItem|null $item
     * @return ExtensionHasCondition|IItem|null
     */
    public function setConditionName(string $name, IItem &$item = null)
    {
        $item[IHasCondition::FIELD__CONDITION] = $name;

        return $item;
    }

    /**
     * @param IItem|null $item
     * @return ICondition|null
     * @throws \Exception
     */
    public function getCondition(IItem $item = null): ?ICondition
    {
        if (isset($item[IHasCondition::FIELD__CONDITION])) {
            $withConditions = new WithConditions($item->__toArray());
            return $withConditions->getCondition();
        }

        throw new \Exception('Missed ' . IHasCondition::FIELD__CONDITION . ' parameter');
    }
}
