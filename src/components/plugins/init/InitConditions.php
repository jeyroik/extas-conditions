<?php
namespace extas\components\plugins\init;

use extas\components\conditions\Condition;

/**
 * Class InitConditions
 *
 * @package extas\components\plugins\init
 * @author jeyroik <jeyroik@gmail.com>
 */
class InitConditions extends InitSection
{
    protected string $selfSection = 'conditions';
    protected string $selfName = 'condition';
    protected string $selfRepositoryClass = 'conditionRepository';
    protected string $selfUID = Condition::FIELD__NAME;
    protected string $selfItemClass = Condition::class;
}
