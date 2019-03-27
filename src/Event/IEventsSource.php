<?php

namespace Rpt\Event;


interface IEventsSource
{
  public function subscribe($event_name, $callable);
  public static function events(): array;
}