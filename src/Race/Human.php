<?php

namespace Rpt\Race;

use Rpt\Abilities;
use Rpt\Event\Collector;

class Human extends Race
{

  public function __construct(string $language)
  {
    parent::__construct("Medium", 30);
    $this->addLanguage("Common");
    $this->addLanguage($language);
  }

  public function subscribeMeToEvents(): array
  {
    $events = [];
    foreach (Abilities::$abilities as $ability) {
      $events[] = "increase.ability.{$ability}";
    }
    return $events;
  }

  public function respondToEvent(Collector $event) {
    $event->addValue(1);
  }

  public function __toString()
  {
    return "Human";
  }

}