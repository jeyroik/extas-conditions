<?php
namespace extas\components\plugins\uninstall;

use extas\components\conditions\Condition;

/**
 * Class UninstallConditions
 *
 * @package extas\components\plugins\uninstall
 * @author jeyroik <jeyroik@gmail.com>
 */
class UninstallConditions extends UninstallSection
{
    protected string $selfSection = 'conditions';
    protected string $selfName = 'condition';
    protected string $selfRepositoryClass = 'conditionRepository';
    protected string $selfUID = Condition::FIELD__NAME;
    protected string $selfItemClass = Condition::class;
}
