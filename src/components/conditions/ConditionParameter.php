<?php
namespace extas\components\conditions;

use extas\components\samples\parameters\SampleParameter;
use extas\interfaces\conditions\IConditionParameter;

/**
 * Class ConditionParameter
 *
 * @package extas\components\conditions
 * @author jeyroik <jeyroik@gmail.com>
 */
class ConditionParameter extends SampleParameter implements IConditionParameter
{
    use THasCondition;

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return 'extas.condition.parameter';
    }
}
