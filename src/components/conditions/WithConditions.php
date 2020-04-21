<?php
namespace extas\components\conditions;

use extas\components\Item;
use extas\components\THasValue;
use extas\interfaces\conditions\IHasCondition;

/**
 * Class WithConditions
 *
 * @package extas\components\conditions
 * @author jeyroik@gmail.com
 */
class WithConditions extends Item implements IHasCondition
{
    use THasCondition;
    use THasValue;

    protected function getSubjectForExtension(): string
    {
        return 'extas.condition.with';
    }
}
