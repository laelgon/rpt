<?php

namespace Rpt;


use Rpt\Event\EventsSource;
use Rpt\Event\IEventsSource;


class Abilities implements IEventsSource
{
  use EventsSource;

  static $abilities = ['strength', 'dexterity', 'constitution', 'intelligence', 'wisdom', 'charisma'];

  private $scores;

  public function __construct(array $scores)
  {
    $this->scores = $scores;
    $this->initializeEventSourcing();
  }

  public function getScore($ability) {

    $base_score = $this->scores[array_search($ability, self::$abilities)];
    $score = $base_score;
    foreach ($this->fireEvent("increase.ability.{$ability}") as $value) {
      $score += $value;
    }
    return $score;
  }

  public function getModifier($ability) {
    $score = $this->getScore($ability);
    if ($score == 1) {
      $modifier = -5;
    }
    elseif ($score == 30) {
      $modifier = 10;
    }
    else {
      $modifier = floor($score/2) - 5;
    }
    return $modifier;
  }

  public static function events(): array
  {
    $events = [];
    foreach (self::$abilities as $ability) {
      $events[] = "increase.ability.{$ability}";
    }
    return $events;
  }

}