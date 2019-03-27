<?php

namespace Rpt;

use Rpt\Race\Human;
use Rpt\Klass\Fighter;
use Rpt\Klass\Wizard;

class Game
{
  private $characters;

  public function __construct(array $characters)
  {
    $this->characters = $characters;
  }

  public function getCharacters(): array
  {
    return $this->characters;
  }

  public function chooseACharacterMessage() {
    $message = (object) [];

    $message->type = "select";
    $message->prompt = "Choose a character:";
    $message->options = [];

    /* @var $character \Rpt\Character */
    foreach ($this->characters as $character) {
      $message->options[] = $character->getName();
    }

    return json_encode($message);
  }

  public static function get()
  {
    $characters = [];

    $abilities = new Abilities([15, 8, 14, 10, 12, 13]);
    $lacross = new Character("Lacross", new Human("Draconic"), new Fighter(), $abilities);
    $lacross->equip(new \Rpt\Armor\ChainMail());

    $abilities = new Abilities([10, 13, 14, 16, 12, 8]);
    $jadis = new Character("Jadis", new \Rpt\Race\HighElf("Dwarvish"), new Wizard(), $abilities);
    $jadis->equip(new \Rpt\Weapon\ShortSword());
    $jadis->equip(new \Rpt\Weapon\GreatAxe());

    $characters[] = $lacross;
    $characters[] = $jadis;

    return new self($characters);
  }



}