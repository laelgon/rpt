<?php


namespace Rpt\Game;

use Rpt\Abilities;
use Rpt\Dice\Di;
use Rpt\Dice\Dice;
use Rpt\Skills;

class SavingThrows extends Character
{
  use Active;

  /**
   * @var \Rpt\Character
   */
  private $character;

  public function prompt()
  {
    if (!$this->character) {
      return ["message" => ["Saving Throws > Choose a Character"], "options" => $this->getCharacterOptions()];
    }
    else {
      $options = [];
      $index = 1;
      foreach (Abilities::$abilities as $ability) {
        $options[$index] = $ability;
        $index++;
      }
      return ["message" => ["Saving Throws > Choose an Ability"], "options" => $options];
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

      $roll = (new Dice(1, new Di(20)))->roll();
      $this->active = FALSE;

      $modifier = $this->character->getSavingThrowModifier($ability);
      $check = $roll + $modifier;

      return ["Saving Throw: {$check} (roll {$roll} + {$ability}'s modifier $modifier)"];
    }
  }


}