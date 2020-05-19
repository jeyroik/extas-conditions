<?php
namespace extas\components\exceptions\conditions;

use extas\interfaces\conditions\IHasCondition;
use Throwable;

/**
 * Class ExceptionMissedConditionParameter
 *
 * @package extas\components\exceptions\conditions
 * @author jeyroik <jeyroik@gmail.com>
 */
class ExceptionMissedConditionParameter extends \Exception
{
    /**
     * ExceptionMissedConditionParameter constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Missed ' . IHasCondition::FIELD__CONDITION . ' parameter';
        parent::__construct($message, $code, $previous);
    }
}
