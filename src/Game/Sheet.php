<?php


namespace Rpt\Game;


class Sheet extends Character
{
  use Active;

  public function prompt()
  {
    return ['message' => ["Character Sheet > Choose a Character"], 'options' => $this->getCharacterOptions()];
  }

  public function input($input)
  {
    $character = $this->characters[$input - 1];
    $this->active = FALSE;
    return ["{$character}"];
  }

}