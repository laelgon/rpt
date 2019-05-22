<?php


namespace Rpt\Magic\Spells;


class RayOfFrost extends Spell
{
  public function __construct()
  {
    parent::__construct(0);
  }

  public function __toString()
  {
    return "Ray of Frost";
  }

}