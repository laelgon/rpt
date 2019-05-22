<?php
namespace Rpt\Armor;

use Rpt\Event\Collector;
use Rpt\Event\IEventsConsumer;

abstract class Armor implements IEventsConsumer
{
  abstract function getArmorClass();

  public function subscribeMeToEvents(): array
  {
    return ['set.armor.class'];
  }

  public function respondToEvent(Collector $event)
  {
    $event->addValue($this->getArmorClass());
  }
}