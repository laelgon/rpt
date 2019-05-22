<?php


namespace Rpt;


use Rpt\Dice\Di;
use Rpt\Event\EventsSource;
use Rpt\Event\IEventsSource;
use Rpt\Klass\Klass;

class Skills implements IEventsSource
{
  use EventsSource;

  static $skills = [
    0 => 'acrobatics',
    1 => 'animal handling',
    2 => 'arcana',
    3 => 'athletics',
    4 => 'deception',
    5 => 'history',
    6 => 'insight',
    7 => 'intimidation',
    8 => 'investigation',
    9 => 'medicine',
    10 => 'nature',
    11 => 'perception',
    12 => 'performance',
    13 => 'persuasion',
    14 => 'religion',
    15 => 'sleight of hand',
    16 => 'stealth',
    17 => 'survival',
  ];

  private $abilities;
  private $klass;

  public function __construct(Abilities $abilities, Klass $klass) {
    $this->initializeEventSourcing();
    $this->abilities = $abilities;
    $this->klass = $klass;
  }

  public function getModifier($skill) {
    $ability = $this->getSkillsAbility($skill);
    $ability_modifier = $this->abilities->getModifier($ability);
    $proficiencies = $this->getProficiencies();
    if (in_array($skill, $proficiencies)) {
      return $ability_modifier + $this->klass->getProficiencyBonus();
    }
    else {
      return $ability_modifier;
    }
  }

  public function getProficiencies() {
    return $this->fireEvent('get.proficiencies.skill');
  }

  public function check($skill, $proficiency_bonus) {
    $roll = (new Dice(1, new Di(20)))->roll();

  }

  public function getSkillsAbility($skill) {
    $abilities = Abilities::$abilities;

    $map = [
      $abilities[0] => [
        self::$skills[3],
      ],
      $abilities[1] => [
        self::$skills[0],
        self::$skills[15],
        self::$skills[16],
      ],
      $abilities[3] => [
        self::$skills[2],
        self::$skills[5],
        self::$skills[8],
        self::$skills[10],
        self::$skills[14],
        self::$skills[3],
      ],
      $abilities[4] => [
        self::$skills[1],
        self::$skills[6],
        self::$skills[9],
        self::$skills[11],
        self::$skills[17],
      ],
      $abilities[5] => [
        self::$skills[4],
        self::$skills[7],
        self::$skills[12],
        self::$skills[13],
      ],
    ];

    foreach ($map as $ability => $skills) {
      if (in_array($skill, $skills)) {
        return $ability;
      }
    }
  }

  public static function events(): array
  {
    return ['get.proficiencies.skill'];
  }




}