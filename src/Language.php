<?php


namespace Rpt;


use Rpt\Utility\EnumCheck;

trait Language
{
  private $languagesEnum = ["Abyssal", "Aquan", "Auran", "Celestial", "Common", "Deep Speech", "Draconic",
    "Druidic", "Dwarvish", "Elvish", "Giant", "Gnomish", "Goblin", "Gnoll", "Halfling", "Ignan", "Infernal",
    "Orc", "Primordial", "Sylvan", "Terran", "Undercommon"];

  private $languages = [];

  protected function addLanguage(string $language) {
    $this->languages[] = EnumCheck::check("language", $this->languagesEnum, $language);
  }

  protected function getLanguages() {
    return $this->languages;
  }

}