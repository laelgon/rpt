<?php


namespace Rpt;


class Combat implements IProcess
{
  private $active = TRUE;

  private $characters = [];

  /**
   * @var Character
   */
  private $attacker;

  /**
   * @var Character
   */
  private $defender;

  public function __construct(array $characters)
  {
    $this->characters = $characters;
  }

  public function isActive()
  {
    return $this->active;
  }

  public function prompt()
  {
    $options = [];
    $index = 1;

    /** @var  $character Character */
    foreach ($this->characters as $character) {
      $options[$index] = "{$character->getName()}";
      $index++;
    }
    return ["message" => ["Combat > Choose an Attacker"], "options" => $options];
  }

  public function input($input)
  {
    $this->active = FALSE;
  }

}