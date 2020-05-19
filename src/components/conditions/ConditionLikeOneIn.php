<?php
namespace extas\components\conditions;

use extas\components\plugins\Plugin;
use extas\interfaces\conditions\ICondition;
use extas\interfaces\conditions\IConditionDispatcher;

/**
 * Class ConditionLikeOneIn
 *
 * @package extas\components\conditions
 * @author jeyroik@gmail.com
 */
class ConditionLikeOneIn extends Plugin implements IConditionDispatcher
{
    /**
     * @param mixed $compareWith
     * @param mixed $compareTo
     *
     * @return bool
     */
    public function __invoke($compareWith, $compareTo): bool
    {
        $one = (string) $compareWith;
        $compareTo = is_array($compareTo) ? $compareTo : [$compareTo];
        foreach ($compareTo as $two) {
            $two = (string) $two;
            if (strpos($one, $two) !== false) {
                return true;
            }
        }

        return false;
    }
}
