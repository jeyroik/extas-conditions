<?php
namespace extas\components\conditions;

use extas\components\repositories\Repository;
use extas\interfaces\conditions\IConditionRepository;

/**
 * Class ConditionRepository
 *
 * @package extas\components\conditions
 * @author jeyroik <jeyroik@gmail.com>
 */
class ConditionRepository extends Repository implements IConditionRepository
{
    protected string $pk = Condition::FIELD__NAME;
    protected string $name = 'conditions';
    protected string $scope = 'extas';
    protected string $itemClass = Condition::class;
}
