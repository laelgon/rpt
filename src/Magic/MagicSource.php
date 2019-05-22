<?php

namespace Rpt\Magic;

use Rpt\Magic\Spells\Spell;

abstract class MagicSource
{
  private $pool;
  private $spells = [];

  public function __construct(SpellPool $pool)
  {
    $this->pool = $pool;
  }

  public function acquireSpell($name) {
    $this->spells[] = $this->pool->retrieveSpell($name);
  }

  public function __toString()
  {
    $spells_by_level = [];

    /** @var $spell Spell */
    foreach ($this->spells as $spell) {
      $spells_by_level[$spell->getLevel()][] = "{$spell}";
    }

    $strings = [];
    foreach ($spells_by_level as $level => $spells) {
      $strings[] = "{$level} Level: " . implode(", ", $spells);
    }

    return implode(PHP_EOL, $strings);
  }
}