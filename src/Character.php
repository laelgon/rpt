<?php

namespace Rpt;

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
  private $experience = 0;
  private $abilities;
  private $equipment = [];

  private $damage = 0;

  public function __construct(string $name, Race $race, Klass $klass, Abilities $abilities)
  {
    $this->initializeEventSourcing();

    $this->name = $name;
    $this->abilities = $abilities;
    $this->race = $race;
    $this->klass = $klass;

    EventsConnector::connect([$this, $this->race, $this->abilities, $this->klass]);

    $this->equip(new Unarmed());
  }

  public function getName() {
    return $this->name;
  }

  public function getAbilityScore($ability) {
    return $this->abilities->getScore($ability);
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
      'get.proficiencies.weapon'
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
    $proficiencies = $this->fireEvent('get.proficiencies.weapon');

    $mine = ["{$weapon}", "type.{$weapon->getType()}"];

    foreach ($mine as $proficiency) {
      if (in_array($proficiency, $proficiencies)) {
        return TRUE;
      }
    }
    return FALSE;
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

  public function __toString() {
    $text = [];
    $text[] = $this->name;
    $text[] = "{$this->race} {$this->klass} Lv.{$this->klass->getLevel()} ($this->experience exp.)";
    $abilities = [];
    foreach (Abilities::$abilities as $ability) {
      $score = $this->abilities->getScore($ability);
      $modifier = $this->abilities->getModifier($ability);
      $modifier = ($modifier >= 0) ? "+{$modifier}" : $modifier;
      $abilities[] = "{$ability}: {$modifier} ({$score})";
    }

    $text[] = implode(" ", $abilities);
    $text[] = "Proficiency Bonus: +{$this->getProficiencyBonus()}";
    $text[] = "Armor Class (AC): {$this->getArmorClass()}";
    $text[] = "Initiative: {$this->getInitiative()}";
    $text[] = "Hit Point Maximum: {$this->getHitPointMaximum()}";
    $text[] = "Current Hit Points: {$this->getCurrentHitPoints()}";

    $languages = implode(", ", $this->race->getLanguages());
    $text[] = "Languages: {$languages}";

    return implode(PHP_EOL, $text);
  }

}