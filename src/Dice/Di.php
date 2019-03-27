<?php

namespace Rpt\Dice;

class Di
{
  private $sides;

  public function __construct($sides) {
    $this->sides = $sides;
  }

  public function numberOfSides() {
    return $this->sides;
  }

  public function roll() {
    return rand(1, $this->sides);
  }

}