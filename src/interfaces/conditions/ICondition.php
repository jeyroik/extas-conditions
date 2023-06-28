<?php
namespace extas\interfaces\conditions;

use extas\interfaces\IHasAliases;
use extas\interfaces\IHasClass;
use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;

/**
 * Interface Condition
 *
 * @package extas\interfaces\conditions
 * @author jeyroik <jeyroik@gmail.com>
 */
interface ICondition extends IItem, IHasName, IHasDescription, IHasClass, IHasAliases, IHaveUUID
{
    public const SUBJECT = 'extas.condition';
}
