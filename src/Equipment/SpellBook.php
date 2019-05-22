<?php


namespace Rpt\Equipment;


use Rpt\Magic\MagicSource;
use Rpt\Magic\SpellPool;

class SpellBook extends MagicSource
{
  public function __construct()
  {
    parent::__construct(new SpellPool());
  }
}