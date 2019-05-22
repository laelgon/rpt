<?php

namespace Rpt\Weapon;

use Rpt\Dice\Dice;
use Rpt\Utility\EnumCheck;

abstract class Weapon {

  private $name;

  private $typesEnum = ['simple', 'martial', 'none'];
  private $rangesEnum = ['melee', 'ranged'];

  private $type;
  private $range;

  /**
   * @return mixed
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * @return mixed
   */
  public function getRange()
  {
    return $this->range;
  }

  abstract public function getDamageDice(): Dice;

  public function __construct($name, $type, $range) {
    $this->name = $name;
    $this->type = EnumCheck::check("type", $this->typesEnum, $type);
    $this->range = EnumCheck::check("range", $this->rangesEnum, $range);
  }

  public function getName() {
    return $this->name;
  }

  public function getDamage($roll) {

    if (!isset($roll)) {
      /* @var $di \Rpt\Dice\Dice */
      $dice = $this->getDamageDice();
      $roll = $dice->roll();
    }

    if ($this->rollIsValid($roll)) {
      return $roll;
    }
    else {
      throw new \Exception("Invalid damage roll ({$roll})");
    }
  }

  protected function rollIsValid($roll) {
    /* @var $di \Rpt\Dice\Dice */
    $dice = $this->getDamageDice();
    return $roll <= $dice->maxRoll();
  }

  public function __toString()
  {
    return "{$this->getName()} (damage: {$this->getDamageDice()})";
  }
}