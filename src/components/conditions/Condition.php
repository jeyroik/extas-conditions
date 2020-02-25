<?php
namespace extas\components\conditions;

use extas\components\Item;
use extas\components\THasAliases;
use extas\components\THasClass;
use extas\components\THasDescription;
use extas\components\THasName;
use extas\interfaces\conditions\ICondition;

/**
 * Class Condition
 *
 * @package extas\components\conditions
 * @author jeyroik <jeyroik@gmail.com>
 */
class Condition extends Item implements ICondition
{
    use THasName;
    use THasDescription;
    use THasClass;
    use THasAliases;

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
