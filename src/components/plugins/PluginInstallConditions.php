<?php
namespace extas\components\plugins;

use extas\components\conditions\Condition;
use extas\interfaces\conditions\IConditionRepository;

/**
 * Class PluginInstallConditions
 *
 * @package extas\components\plugins
 * @author jeyroik@gmail.com
 */
class PluginInstallConditions extends PluginInstallDefault
{
    protected string $selfSection = 'conditions';
    protected string $selfName = 'condition';
    protected string $selfRepositoryClass = IConditionRepository::class;
    protected string $selfUID = Condition::FIELD__NAME;
    protected string $selfItemClass = Condition::class;
}
