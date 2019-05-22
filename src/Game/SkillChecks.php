<?php


namespace Rpt\Game;

use Rpt\Abilities;
use Rpt\Character;
use Rpt\Dice\Di;
use Rpt\Dice\Dice;
use Rpt\Game\Character as CharacterProcess;
use Rpt\Skills;

class SkillChecks extends CharacterProcess
{
  use Active;

  /**
   * @var Character
   */
  private $character;

  public function prompt()
  {
    if (!$this->character) {
      return ["message" => ["Skill Checks > Choose a Character"], "options" => $this->getCharacterOptions()];
    }
    else {
      $options = [];
      $index = 1;
      foreach (Skills::$skills as $skill) {
        $options[$index] = $skill;
        $index++;
      }
      return ["message" => ["Checks > Choose a Skill"], "options" => $options];
    }
  }

  public function input($input)
  {
    if (!$this->character) {
      $this->character = $this->characters[$input - 1];
    }
    else {
      $skills = Skills::$skills;
      $skill = $skills[$input - 1];
      $modifier = $this->character->getSkills()->getModifier($skill);
      $roll = (new Dice(1, new Di(20)))->roll();
      $check = $roll + $modifier;
      $this->active = FALSE;
      return ["Check: {$check} (roll {$roll} + {$skill}'s modifier $modifier)"];
    }
  }

}