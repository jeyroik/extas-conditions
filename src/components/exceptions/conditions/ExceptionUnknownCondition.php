<?php
namespace extas\components\exceptions\conditions;

use extas\interfaces\conditions\IHasCondition;
use Throwable;

/**
 * Class ExceptionUnknownCondition
 *
 * @package extas\components\exceptions\conditions
 * @author jeyroik <jeyroik@gmail.com>
 */
class ExceptionUnknownCondition extends \Exception
{
    /**
     * ExceptionMissedConditionParameter constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = 'Unknown condition "' . $message . '"';
        parent::__construct($message, $code, $previous);
    }
}
