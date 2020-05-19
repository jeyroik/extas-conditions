<?php
namespace extas\components\conditions;

use extas\components\plugins\Plugin;
use extas\interfaces\conditions\ICondition;
use extas\interfaces\conditions\IConditionDispatcher;

/**
 * Class ConditionRegEx
 *
 * @package extas\components\conditions
 * @author jeyroik@gmail.com
 */
class ConditionRegEx extends Plugin implements IConditionDispatcher
{
    /**
     * @param mixed $compareWith
     * @param mixed $compareTo
     *
     * @return bool
     */
    public function __invoke($compareWith, $compareTo): bool
    {
        $text = (string) $compareWith;
        $regex = (string) $compareTo;

        preg_match($regex, $text, $matches);

        return !empty($matches);
    }
}
