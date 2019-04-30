<?php

namespace Rpt;

use Rpt\Race\Human;
use Rpt\Klass\Fighter;
use Rpt\Klass\Wizard;

class Game2
{

  private $active = TRUE;



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

}