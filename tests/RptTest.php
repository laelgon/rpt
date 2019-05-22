<?php

class RptTest extends \PHPUnit\Framework\TestCase
{

  private $characters = [];

  protected function setUp(): void
  {
    parent::setUp();

    $this->characters[1] = new \Rpt\Character("Blah", new \Rpt\Race\Human("Infernal"), new \Rpt\Klass\Fighter(), new \Rpt\Background\Acolyte(["Elvish", "Dwarvish"]), new \Rpt\Abilities([10, 10, 10, 10, 10, 10]));
    $this->characters[1]->equip(new \Rpt\Armor\ChainMail());
    $this->characters[1]->equip(new \Rpt\Weapon\ShortSword());

    $this->characters[2] = new \Rpt\Character("Bluh", new \Rpt\Race\HighElf("Infernal"), new \Rpt\Klass\Wizard(['investigation', 'arcana']), new \Rpt\Background\Acolyte(["Elvish", "Dwarvish"]), new \Rpt\Abilities([10, 10, 10, 10, 10, 10]));
    $this->characters[2]->equip(new \Rpt\Weapon\GreatAxe());
  }

  public function testBadHuman() {
    $this->expectExceptionMessage("Humans naturally speak Common, choose another language.");
    new \Rpt\Character("Blah", new \Rpt\Race\Human("Common"), new \Rpt\Klass\Fighter(), new \Rpt\Background\Acolyte(["Elvish", "Dwarvish"]), new \Rpt\Abilities([10, 10, 10, 10, 10, 10]));
  }

  public function testBadLanguage() {
    $this->expectExceptionMessage("Invalid language French");
    new \Rpt\Race\Human("French");
  }

  public function testBadBackground() {
    $this->expectExceptionMessage("Acolytes get 2 languages. 1 given.");
    new \Rpt\Background\Acolyte(["Blah"]);
  }

  public function testCharacter1()
  {
    $character = $this->characters[1];

    $file = __DIR__ . "/character_1_info.txt";
    $info = file_get_contents($file);
    $this->assertEquals($info, "{$character}");
  }

  public function testCharacter2()
  {
    $character = $this->characters[2];

    $file = __DIR__ . "/character_2_info.txt";
    $info = file_get_contents($file);
    $this->assertEquals($info, "{$character}");
  }

  public function testDice() {
    $dice = new \Rpt\Dice\Dice(2, new \Rpt\Dice\Di(4));
    $roll = $dice->roll();

    $this->assertGreaterThanOrEqual(2, $roll);
    $this->assertLessThanOrEqual(8, $roll);
    $this->assertEquals(8, $dice->maxRoll());
  }

  public function testCombat() {
    $combat = $this->getMockBuilder(\Rpt\Combat::class)
      ->setMethods([
        'attackRoll',
      ])
      ->getMock();

    $combat->expects($this->once())
      ->method("attackRoll")
      ->willReturn('14');

    $combat->setAttacker($this->characters[1]);
    $combat->setDefender($this->characters[2]);

    $response = json_encode(
      [
        "Starting Combat",
        "Attack Roll: 14",
        "Blah is attacking unarmed. Attack roll 14 + strength modifier 0 = 14",
        "Bluh armor class is 11",
        "Hit",
        "Bluh received 1 damage (1 + strength Modifier 0 ). Current hit points: 5",
      ]
    );

    $this->assertEquals($response, json_encode($combat->oneRound(new \Rpt\Weapon\Unarmed())));
  }

  public function testSpells() {
    $pool = new \Rpt\Magic\SpellPool();
    $this->assertInstanceOf(\Rpt\Magic\Spells\Spell::class, $pool->retrieveSpell("Mage Hand"));

    $spell_book = new \Rpt\Equipment\SpellBook();
    $spell_book->acquireSpell("Mage Hand");
    $spell_book->acquireSpell("Burning Hands");

    $this->assertEquals("0 Level: Mage Hand\n1 Level: Burning Hands", "{$spell_book}");
  }

  public function testBadSpellPool() {
    $this->expectExceptionMessage("The spell Mage Foot is not present in this pool.");
    $pool = new \Rpt\Magic\SpellPool();
    $this->assertInstanceOf(\Rpt\Magic\Spells\Spell::class, $pool->retrieveSpell("Mage Foot"));
  }

}