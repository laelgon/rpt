<?php


namespace Rpt\Game;

use Rpt\Abilities;
use Rpt\Character;
use Rpt\Dice\Di;
use Rpt\Dice\Dice;
use Rpt\Game\Character as CharacterProcess;

class AbilityChecks extends CharacterProcess
{
  use Active;

  /**
   * @var Character
   */
  private $character;

  public function prompt()
  {
    if (!$this->character) {
      return ["message" => ["Ability Checks > Choose a Character"], "options" => $this->getCharacterOptions()];
    }
    else {
      $options = [];
      $index = 1;
      foreach (Abilities::$abilities as $ability) {
        $options[$index] = $ability;
        $index++;
      }
      return ["message" => ["Ability Checks > Choose an Ability"], "options" => $options];
    }
  }

  public function input($input)
  {
    if (!$this->character) {
      $this->character = $this->characters[$input - 1];
    }
    else {
      $abilities = Abilities::$abilities;
      $ability = $abilities[$input - 1];
      $modifier = $this->character->getAbilityModifier($ability);
      $roll = (new Dice(1, new Di(20)))->roll();
      $check = $roll + $modifier;
      $this->active = FALSE;
      return ["Check: {$check} (roll {$roll} + {$ability}'s modifier $modifier)"];
    }
  }

}