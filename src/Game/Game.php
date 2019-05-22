<?php


namespace Rpt\Game;


use Rpt\Background\Acolyte;
use Rpt\Klass\Fighter;
use Rpt\Klass\Wizard;
use Rpt\Race\Human;
use Rpt\Abilities;
use Rpt\Character;

class Game implements IProcess
{
  private $active = TRUE;

  /**
   * @var IProcess
   */
  private $process = NULL;

  private $characters;

  public function __construct(array $characters)
  {
    $this->characters = $characters;
  }

  public function isActive() {
    return $this->active;
  }

  public function prompt() {
    if ($this->process) {
      if ($this->process->isActive()) {
        return $this->process->prompt();
      }
      else {
        $this->process = NULL;
      }
    }

    $options = [
      1 => "Character Sheet",
      2 => "Ability Checks",
      3 => "Skill Checks",
      4 => "Saving Throws",
      5 => "Combat",
      6 => "Exit"
    ];

    return ['message' => ["Menu"], 'options' => $options];
  }

  public function input($input) {
    if ($this->process) {
      if ($this->process->isActive()) {
        return $this->process->input($input);
      }
      else {
        $this->process = NULL;
      }
    }

    // Character Sheet
    if ($input == 1) {
      $this->process =  new Sheet($this->characters);
    }

    // Ability Checks
    if ($input == 2) {
      $this->process =  new AbilityChecks($this->characters);
    }

    // Skill Checks
    if ($input == 3) {
      $this->process =  new SkillChecks($this->characters);
    }

    // Saving Throws
    if ($input == 4) {
      $this->process =  new SavingThrows($this->characters);
    }

    // Combat
    if ($input == 5) {
      $this->process = new Combat($this->characters);
    }
    // Exit
    elseif ($input == 6) {
      $this->active = FALSE;
    }
  }

  public static function get() {
    $characters = [];

    $abilities = new Abilities([15, 8, 14, 10, 12, 13]);
    $lacross = new Character(
      "Lacross",
      new Human("Draconic"),
      new Fighter(),
      new Acolyte(['Dwarvish', "Goblin"]),
      $abilities
    );
    $lacross->equip(new \Rpt\Armor\Armor());

    $abilities = new Abilities([10, 13, 14, 15, 12, 8]);
    $jadis = new Character(
      "Jadis",
      new \Rpt\Race\HighElf("Draconic"),
      new Wizard(['arcana', 'investigation']),
      new Acolyte(['Dwarvish', "Goblin"]),
      $abilities
    );
    $jadis->equip(new \Rpt\Weapon\ShortSword());
    $jadis->equip(new \Rpt\Weapon\GreatAxe());

    $characters[] = $lacross;
    $characters[] = $jadis;

    return new self($characters);
  }

}