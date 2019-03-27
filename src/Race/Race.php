<?php

namespace Rpt\Race;

use Rpt\Event\IEventsConsumer;
use Rpt\Utility\EnumCheck;

abstract class Race implements IEventsConsumer
{

  private $sizesEnum = ["Small", "Medium"];
  private $languagesEnum = ["Abyssal", "Aquan", "Auran", "Celestial", "Common", "Deep Speech", "Draconic",
    "Druidic", "Dwarvish", "Elvish", "Giant", "Gnomish", "Goblin", "Gnoll", "Halfling", "Ignan", "Infernal",
    "Orc", "Primordial", "Sylvan", "Terran", "Undercommon"];

  private $size;
  private $speed;

  private $languages = [];

  abstract public function subscribeMeToEvents(): array;

  public function __construct(string $size, int $speed)
  {
    $this->size = EnumCheck::check("size", $this->sizesEnum, $size);

    $this->speed = $speed;
  }

  protected function addLanguage(string $language) {
    $this->languages[] = EnumCheck::check("language", $this->languagesEnum, $language);
  }

  public function getLanguages() {
    return $this->languages;
  }

}