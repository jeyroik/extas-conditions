<?php
namespace extas\components\conditions;

use extas\components\parameters\Param;
use extas\interfaces\conditions\IConditionParameter;

/**
 * Class ConditionParameter
 *
 * @package extas\components\conditions
 * @author jeyroik <jeyroik@gmail.com>
 */
class ConditionParameter extends Param implements IConditionParameter
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
