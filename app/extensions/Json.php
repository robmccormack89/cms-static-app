<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq as Jsonq;

// This is an extension of Jsonq in order to use a custom conditional (overwrites the match conditional to use regex across the fill key)
class Json extends Jsonq {
  
  /**
   * For custom ConditionalFactory.php
   * Build or generate a function for applies condition from operator
   * @param $condition
   * @return array
   * @throws ConditionNotAllowedException
   */
  protected function makeConditionalFunctionFromOperator($condition)
  {
    if (!isset(self::$_conditionsMap[$condition])) {
      throw new Nahid\QArray\Exceptions\ConditionNotAllowedException("Exception: {$condition} condition not allowed");
    }

    $function = self::$_conditionsMap[$condition];
    if (!is_callable($function)) {
      if (!method_exists(Condition_model::class, $function)) {
        throw new Nahid\QArray\Exceptions\ConditionNotAllowedException("Exception: {$condition} condition not allowed");
      }

      $function = [Condition_model::class, $function];
    }

    return $function;
  }
  
}

use Nahid\QArray\Exceptions\KeyNotPresentException;

final class Condition_model
{
  /**
   * Match with pattern*** OVERWRITE from Qarray
   *
   * @param mixed $value
   * @param string $comparable
   *
   * @return bool
   */
  public static function match($value, $comparable)
  {
      if (is_array($comparable) || is_array($value) || is_object($comparable) || is_object($value)) {
          return false;
      }

      $comparable = trim($comparable);

      if (preg_match("/$comparable/", $value)) {
          return true;
      }

      return false;
  }
}