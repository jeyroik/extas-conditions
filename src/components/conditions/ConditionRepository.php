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
    protected $idAs = '';
    protected $pk = Condition::FIELD__NAME;
    protected $name = 'conditions';
    protected $scope = 'extas';
    protected $itemClass = Condition::class;
}
