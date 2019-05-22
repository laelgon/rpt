<?php


namespace Rpt\Magic\Spells;


class MageHand extends Spell
{
  public function __construct()
  {
    parent::__construct(0);
  }

  public function __toString()
  {
    return "Mage Hand";
  }

}