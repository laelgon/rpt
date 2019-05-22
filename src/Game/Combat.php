<?php


namespace Rpt\Game;


use Rpt\Character;
use Rpt\Weapon\Weapon;
use Rpt\Game\Character as CharacterProcess;

class Combat extends CharacterProcess
{
  use Active;

  /**
   * @var Character
   */
  private $attacker;

  /**
   * @var Weapon
   */
  private $weapon;

  /**
   * @var Character
   */
  private $defender;

  public function prompt()
  {
    if (!$this->attacker) {
      return ["message" => ["Combat > Choose an Attacker"], "options" => $this->getCharacterOptions()];
    }
    elseif (!$this->weapon) {
      return ["message" => ["Combat > Choose a Weapon"], "options" => $this->getWeaponOptions()];
    }
    else {
      return ["message" => ["Combat > Choose a Defender"], "options" => $this->getCharacterOptions()];
    }
  }

  public function input($input)
  {
    if (!$this->attacker) {
      $this->attacker = $this->characters[$input - 1];
    }
    elseif (!$this->weapon) {
      $weapons = $this->attacker->getWeapons();
      $this->weapon = $weapons[$input - 1];
    }
    elseif (!$this->defender) {
      $this->defender = $this->characters[$input - 1];
      $this->active = FALSE;
      return $this->runCombat();
    }
  }

  private function getWeaponOptions() {
    $weapons = $this->attacker->getWeapons();

    $options = [];

    foreach ($weapons as $key => $weapon) {
      $final_key = $key + 1;
      $options[$final_key] = "{$weapon}";
    }

    return $options;
  }

  private function runCombat() {
    $combat = new \Rpt\Combat();
    $combat->setAttacker($this->attacker);
    $combat->setDefender($this->defender);
    return $combat->oneRound($this->weapon);
  }

}