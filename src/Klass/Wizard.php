<?php

namespace Rpt\Klass;

use Rpt\Dice\Di;
use Rpt\Dice\Dice;
use Rpt\Event\Collector;

class Wizard extends Klass
{
  public static $possibleSkillProficiencies = ['arcana', 'history', 'insight', 'investigation', 'medicine', 'Religion'];

  private $skills = [];

  public function __construct($skills)
  {
    $number_of_skills = count($skills);
    if ($number_of_skills != 2) {
      throw new \Exception("Wizards get 2 skill proficiencies. {$number_of_skills} given.");
    }

    foreach ($skills as $skill) {
      if (in_array($skill, self::$possibleSkillProficiencies)) {
        $this->skills[] = $skill;
      }
      else {
        $ps = implode(", ", self::$possibleSkillProficiencies);
        throw new \Exception("Wizards can gain proficiency in {$ps}. {$skill} given.");
      }
    }
  }

  public function getHitDice(): Dice
  {
    return new Dice($this->getLevel(), new Di(6));
  }

  public function subscribeMeToEvents(): array
  {
    return [
      'get.proficiencies.weapon',
      'get.proficiencies.skill',
      'get.proficiencies.saving_throws',
    ];
  }

  public function respondToEvent(Collector $event)
  {
    if ($event->getName() === 'get.proficiencies.weapon') {
      $event->addValue('dagger');
      $event->addValue('dart');
      $event->addValue('sling');
      $event->addValue('quarterstaff');
      $event->addValue('light crossbow');
    }
    elseif ($event->getName() === 'get.proficiencies.skill') {
      foreach ($this->skills as $skill) {
        $event->addValue($skill);
      }
    }
    elseif ($event->getName() === 'get.proficiencies.saving_throws') {
      $event->addValue('intelligence');
      $event->addValue('wisdom');
    }
  }

  public function __toString()
  {
    return "Wizard";
  }

}