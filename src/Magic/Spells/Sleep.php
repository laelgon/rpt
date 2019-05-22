<?php


namespace Rpt\Magic\Spells;


class Sleep extends Spell
{
  public function __construct()
  {
    parent::__construct(1);
  }

  public function __toString()
  {
    return "Sleep";
  }
}