<?php


namespace Rpt\Magic\Spells;


class ShockingGrasp extends Spell
{
  public function __construct()
  {
    parent::__construct(0);
  }

  public function __toString()
  {
    return "Shocking Grasp";
  }
}