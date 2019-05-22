<?php

namespace Rpt;

use Rpt\Dice\Di;
use Rpt\Dice\Dice;
use Rpt\Weapon\Weapon;
use Rpt\Weapon\Unarmed;

class Combat {

  /**
   * @param mixed $attacker
   */
  public function setAttacker($attacker): void
  {
    $this->attacker = $attacker;
  }

  /**
   * @param mixed $defender
   */
  public function setDefender($defender): void
  {
    $this->defender = $defender;
  }

  public function toTheDeath() {
    while ($this->attacker->alive() && $this->defender->alive()) {
      $this->oneRound();
      $attacker = $this->getAttacker();
      $defender = $this->getDefender();
      $this->setAttacker($defender);
      $this->setDefender($attacker);
    }
  }

  public function oneRound(Weapon $weapon) {

    if ($this->attacker->alive() && $this->defender->alive()) {
      $output = ["Starting Combat"];

      $roll = $this->attackRoll();
      $output[] = "Attack Roll: {$roll}";


      $output = array_merge($output, $this->attack($weapon, $roll));

      $copy = array_values($output);
      $last = array_pop($copy);

      if ($last == "Hit") {
        $roll = $weapon->getDamageDice()->roll();
        $output = array_merge($output, $this->dealDamage($weapon, $roll));
      }

      return $output;
    }
    else {
      return ["Can't battle the dead"];
    }
  }

  protected function attackRoll() {
    return (new Dice(1, new Di(20)))->roll();
  }


  private function attack(Weapon $weapon, $roll) {
    $output = [];

    $posfix = "";

    $proficiency = 0;
    if ($this->attacker->hasProficiencyWithWeapon($weapon)) {
      $proficiency = $this->attacker->getProficiencyBonus();
      $posfix = " + Proficiency Bonus {$proficiency}";
    }

    $ability_modifier = $this->attacker->getWeaponModifier($weapon);

    $final = $roll + $proficiency + $ability_modifier;

    $weapon_prefix = "";
    if ($weapon instanceof Unarmed) {
    }
    else {
      $weapon_prefix = "with a ";
    }

    $output[] = "{$this->attacker->getName()} is attacking {$weapon_prefix}{$weapon->getName()}. Attack roll {$roll} + {$this->attacker->getWeaponAbility($weapon)} modifier {$ability_modifier}{$posfix} = {$final}";

    $armor_class = $this->defender->getArmorClass();
    $output[] = "{$this->defender->getName()} armor class is {$armor_class}";

    if ($final >= $armor_class) {
      $output[] = "Hit";
    }
    else {
      $output[] = "Missed";
    }
    return $output;
  }

  private function dealDamage(Weapon $weapon, $roll) {
    $damage = $weapon->getDamage($roll);
    $modifier = $this->attacker->getWeaponModifier($weapon);

    $total_damage = $damage + $modifier;
    $this->defender->takeDamage($total_damage);
    return ["{$this->defender->getName()} received {$total_damage} damage ({$damage} + {$this->attacker->getWeaponAbility($weapon)} Modifier {$modifier} ). Current hit points: {$this->defender->getCurrentHitPoints()}"];
  }

}