<?php

namespace Rpt\Magic\Spells;

abstract class Spell
{
  private $level;

  public function __construct(int $level)
  {
    if ($level >= 0 && $level <= 9) {
      $this->level = $level;
    }
  }

  public function getLevel() {
    return $this->level;
  }

  abstract public function __toString();
}