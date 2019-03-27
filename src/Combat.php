<?php

namespace Rpt;

use Rpt\Dice\Di;
use Rpt\Dice\Dice;
use Rpt\Weapon\Weapon;
use Rpt\Weapon\Unarmed;

class Combat {

  /**
   * @var Character
   */
  private $attacker;

  /**
   * @var Character
   */
  private $defender;

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

  /**
   * @return mixed
   */
  public function getAttacker()
  {
    return $this->attacker;
  }

  /**
   * @return mixed
   */
  public function getDefender()
  {
    return $this->defender;
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

  public function oneRound() {
    print_r("COMBAT" . PHP_EOL . PHP_EOL);

    if ($this->attacker->alive() && $this->defender->alive()) {
      $weapon = $this->chooseAWeapon($this->attacker);
      $roll = $this->attackRoll();

      if ($this->attack($weapon, $roll)) {
        $roll = $this->damageRoll($weapon);
        $this->dealDamage($weapon, $roll);
      }
    }
    else {
      print_r("Can't battle the dead");
    }

    print_r(PHP_EOL);
  }

  private function chooseAWeapon(Character $character) {
    $weapons = $character->getWeapons();

    $weapon_id = -1;

    while (!in_array($weapon_id, array_keys($weapons))) {
      echo 'Choose a weapon:' . PHP_EOL;
      foreach ($weapons as $key => $weapon) {
        echo "{$key}) {$weapon}" . PHP_EOL;
      }

      $weapon_id = readline("");
    }

    return $weapons[$weapon_id];

  }

  private function roll($type, Dice $dice) {
    while (true) {
      echo "{$type} Roll:" . PHP_EOL;
      echo "0) Roll for me" . PHP_EOL;
      echo "Or tell us what you rolled" . PHP_EOL;
      $input = readline("");
      if ($input == 0) {
        $roll = $dice->roll();
        echo "You rolled: {$roll}" . PHP_EOL;
        return $roll;
      }
      if ($input > 0 && $input <= $dice->maxRoll()) {
        return $input;
      }
    }
  }

  private function attackRoll() {
    return $this->roll("Attack", new Dice(1, new Di(20)));
  }

  private function attack(Weapon $weapon, $roll) {

    if (!$this->attacker->haveWeapon($weapon))
    {
      throw new \Exception("{$this->getName()} does not have a {$weapon}");
    }

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

    print_r("{$this->attacker->getName()} is attacking {$weapon_prefix}{$weapon->getName()}. Attack roll {$roll} + {$this->attacker->getWeaponAbility($weapon)} modifier {$ability_modifier}{$posfix} = {$final}" . PHP_EOL);

    $armor_class = $this->defender->getArmorClass();
    print_r("{$this->defender->getName()} armor class is {$armor_class}" . PHP_EOL);

    if ($final >= $armor_class) {
      print_r("Is a hit" . PHP_EOL);
      return TRUE;
    }
    else {
      print_r("Missed" . PHP_EOL);
      return FALSE;
    }
  }

  private function damageRoll(Weapon $weapon) {
    return $this->roll("Damage", $weapon->getDamageDice());

  }

  private function dealDamage(Weapon $weapon, $roll) {
    $damage = $weapon->getDamage($roll);
    $modifier = $this->attacker->getWeaponModifier($weapon);

    $total_damage = $damage + $modifier;
    $this->defender->takeDamage($total_damage);
    print_r("{$this->defender->getName()} received {$total_damage} damage ({$damage} + {$this->attacker->getWeaponAbility($weapon)} Modifier {$modifier} ). Current hit points: {$this->defender->getCurrentHitPoints()}" . PHP_EOL);
  }

}