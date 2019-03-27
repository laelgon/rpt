<?php

namespace Rpt\Dice;

class Dice {

  private $number;
  private $d;

  public function __construct($number, Di $d) {
    $this->number = $number;
    $this->d = $d;
  }

  public function getDie() {
    return $this->d;
  }

  public function roll() {
    $value = 0;

    for ($i=0; $i<$this->number; $i++) {
      $value += $this->d->roll();
    }

    return $value;
  }

  public function maxRoll() {
    return $this->d->numberOfSides() * $this->number;
  }

  public function __toString()
  {
    return "{$this->number}d{$this->d->numberOfSides()}";
  }

}