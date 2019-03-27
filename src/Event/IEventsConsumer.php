<?php

namespace Rpt\Event;

interface IEventsConsumer
{
  public function subscribeMeToEvents(): array;

  public function respondToEvent(Collector $event);
}