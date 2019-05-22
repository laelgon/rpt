<?php

namespace Rpt;

use Rpt\Background\Background;
use Rpt\Event\EventsConnector;
use Rpt\Event\EventsSource;
use Rpt\Event\IEventsSource;
use Rpt\Klass\Klass;
use Rpt\Race\Race;
use Rpt\Weapon\Unarmed;
use Rpt\Weapon\Weapon;

class Character implements IEventsSource {

  use EventsSource;

  private $name;
  private $race;
  private $klass;
  private $background;
  private $experience = 0;
  private $abilities;
  private $skills;
  private $equipment = [];

  private $damage = 0;

  public function __construct(string $name, Race $race, Klass $klass, Background $background, Abilities $abilities)
  {
    $this->initializeEventSourcing();

    $this->name = $name;
    $this->abilities = $abilities;
    $this->race = $race;
    $this->klass = $klass;
    $this->background = $background;
    $this->skills = new Skills($this->abilities, $this->klass);

    EventsConnector::connect([$this, $this->race, $this->abilities, $this->skills, $this->klass, $this->background]);

    $this->equip(new Unarmed());
  }

  public function getName() {
    return $this->name;
  }

  public function getSkills() {
    return $this->skills;
  }

  public function getAbilityScore($ability) {
    return $this->abilities->getScore($ability);
  }

  public function getAbilityModifier($ability) {
    return $this->abilities->getModifier($ability);
  }

  public function getArmorClass() {
    $values = $this->fireEvent('set.armor.class');

    $armor_class = 0;
    foreach ($values as $value) {
      if ($value > $armor_class) {
        $armor_class = $value;
      }
    }

    return ($armor_class == 0) ? $this->getNaturalArmorClass() : $armor_class;
  }

  private function getNaturalArmorClass() {
    return 10 + $this->abilities->getModifier('dexterity');
  }

  public function getInitiative() {
    return $this->abilities->getModifier('dexterity');
  }

  private function getHitPointMaximum() {
    return $this->klass->getHitDice()->getDie()->numberOfSides() + $this->abilities->getModifier('constitution');
  }

  public function equip($equipment) {
    EventsConnector::connect([$this, $equipment]);
    $this->equipment[] = $equipment;
  }

  public static function events(): array
  {
    return [
      'set.armor.class',
      'get.proficiencies.weapon',
      'get.proficiencies.saving_throws',
      'get.languages',
    ];
  }

  public function alive() {
    return $this->damage < $this->getHitPointMaximum();
  }

  public function haveWeapon(Weapon $weapon) {
    foreach ($this->getWeapons() as $weapon) {
      if ($weapon == $weapon) {
        return TRUE;
      }
    }
    return FALSE;
  }

  public function hasProficiencyWithWeapon(Weapon $weapon) {
    $proficiencies = $this->getWeaponProficiencies();

    $mine = ["{$weapon}", "type.{$weapon->getType()}"];

    foreach ($mine as $proficiency) {
      if (in_array($proficiency, $proficiencies)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  public function getWeaponProficiencies() {
    return $this->fireEvent('get.proficiencies.weapon');
  }

  public function getWeaponAbility(Weapon $weapon) {
    $weapon_range_and_ability = [
      'melee' => 'strength',
      'ranged' => 'dexterity'
    ];
    return $weapon_range_and_ability[$weapon->getRange()];
  }

  public function getWeaponModifier($weapon) {
    return $this->abilities->getModifier($this->getWeaponAbility($weapon));
  }

  public function takeDamage($damage) {
    $this->damage += $damage;
  }

  public function getWeapons(): array
  {
    $weapons = [];
    foreach ($this->equipment as $equipment) {
      if ($equipment instanceof Weapon) {
        $weapons[] = $equipment;
      }
    }
    return $weapons;
  }

  public function getCurrentHitPoints() {
    return $this->getHitPointMaximum() - $this->damage;
  }

  public function getProficiencyBonus() {
    return $this->klass->getProficiencyBonus();
  }

  public function getSavingThrowProficiencies() {
    return $this->fireEvent('get.proficiencies.saving_throws');
  }

  public function getSavingThrowModifier($ability) {
    $modifier = $this->getAbilityModifier($ability);
    $proficiencies = $this->getSavingThrowProficiencies();
    if (in_array($ability, $proficiencies)) {
      return $modifier + $this->getProficiencyBonus();
    }
    else {
      return $modifier;
    }
  }

  public function getLanguages() {
    $languages = $this->fireEvent('get.languages');
    sort($languages);
    return $languages;
  }

  public function __toString() {
    $text = [];
    $text[] = $this->name;
    $text[] = "{$this->race} {$this->klass} Lv.{$this->klass->getLevel()} ($this->experience exp.) {$this->background}";

    $abilities = [];
    foreach (Abilities::$abilities as $ability) {
      $score = $this->abilities->getScore($ability);
      $modifier = $this->abilities->getModifier($ability);
      $modifier = ($modifier >= 0) ? "+{$modifier}" : $modifier;
      $abilities[] = "{$ability}: {$modifier} ({$score})";
    }
    $text[] = "Abilities: " . implode(" ", $abilities);

    $skills = [];
    foreach (Skills::$skills as $skill) {
      $modifier = $this->skills->getModifier($skill);
      $modifier = ($modifier >= 0) ? "+{$modifier}" : $modifier;
      $skills[] = "{$skill}: {$modifier}";
    }
    $text[] = "Skills: " . implode(" ", $skills);

    $saving_throws = [];
    foreach (Abilities::$abilities as $ability) {
      $modifier = $this->getSavingThrowModifier($ability);
      $modifier = ($modifier >= 0) ? "+{$modifier}" : $modifier;
      $saving_throws[] = "{$ability}: {$modifier}";
    }
    $text[] = "Saving Throws: " . implode(" ", $saving_throws);

    $text[] = "Proficiency Bonus: +{$this->getProficiencyBonus()}";
    $text[] = "Armor Class (AC): {$this->getArmorClass()}";
    $text[] = "Initiative: +{$this->getInitiative()}";
    $text[] = "Hit Points: {$this->getCurrentHitPoints()}/{$this->getHitPointMaximum()}";
    $text[] = "Hit Dice: {$this->klass->getHitDice()}";

    $weapon_proficiencies = implode(", ", $this->getWeaponProficiencies());
    $text[] = "Proficiencies: {$weapon_proficiencies}";

    $languages = implode(", ", $this->getLanguages());
    $text[] = "Languages: {$languages}";

    return implode(PHP_EOL, $text);
  }

}