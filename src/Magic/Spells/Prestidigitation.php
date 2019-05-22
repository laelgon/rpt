<?php


namespace Rpt\Magic\Spells;


class Prestidigitation extends Spell
{
  public function __construct()
  {
    parent::__construct(0);
  }

  public function __toString()
  {
    return "Prestidigitation";
  }

}