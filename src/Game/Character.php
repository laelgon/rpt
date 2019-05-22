<?php


namespace Rpt\Game;


abstract class Character implements IProcess
{
  protected $characters = [];

  public function __construct(array $characters)
  {
    $this->characters = $characters;
  }

  protected function getCharacterOptions() {
    $options = [];
    $index = 1;

    /** @var  $character Character */
    foreach ($this->characters as $character) {
      $options[$index] = "{$character->getName()}";
      $index++;
    }

    return $options;
  }

}