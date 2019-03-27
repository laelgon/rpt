<?php

namespace Rpt\Utility;

class EnumCheck
{
  public static function check($name, $enumeration, $value) {
    if (in_array($value, $enumeration)) {
      return $value;
    }
    else {
      throw new \Exception("Invalid {$name} {$value}");
    }
  }

}