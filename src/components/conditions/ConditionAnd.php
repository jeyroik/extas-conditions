<?php
namespace extas\components\conditions;

use extas\components\Item;
use extas\components\plugins\Plugin;
use extas\components\THasValue;
use extas\interfaces\conditions\ICondition;
use extas\interfaces\conditions\IConditionDispatcher;
use extas\interfaces\conditions\IHasCondition;

/**
 * Class ConditionAnd
 *
 * @package extas\components\conditions
 * @author jeyroik@gmail.com
 */
class ConditionAnd extends Plugin implements IConditionDispatcher
{
    /**
     * @param mixed $compareWith
     * @param mixed $compareTo
     * @return bool
     * @throws \Exception
     */
    public function __invoke($compareWith, $compareTo): bool
    {
        if (is_array($compareTo)) {
            $result = null;
            foreach ($compareTo as $subCondition) {
                $sub = $this->getSubCondition($subCondition);
                $result = $this->updateResult($result, $sub, $compareWith);
            }

            return (bool) $result;
        }

        throw new \Exception('Need array as argument in a condition');
    }

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
            : ($result && $sub->isConditionTrue($compareWith));
    }

    /**
     * @param array $subCondition
     * @return IHasCondition
     */
    protected function getSubCondition(array $subCondition): IHasCondition
    {
        return new class($subCondition) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;

            protected function getSubjectForExtension(): string
            {
                return 'condition';
            }
        };
    }
}
