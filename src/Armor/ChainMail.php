<?php
namespace Rpt\Armor;

use Rpt\Event\Collector;
use Rpt\Event\IEventsConsumer;

class ChainMail implements IEventsConsumer
{
  public function subscribeMeToEvents(): array
  {
    return ['set.armor.class'];
  }

  public function respondToEvent(Collector $event)
  {
    $event->addValue(16);
  }
}