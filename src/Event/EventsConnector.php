<?php

namespace Rpt\Event;

class EventsConnector
{
  public static function connect($objects) {

    $sources = [];
    $consumers = [];

    foreach ($objects as $object) {
      if ($object instanceof IEventsSource) {
        $sources[] = $object;
      }
      if ($object instanceof IEventsConsumer) {
        $consumers[] = $object;
      }
    }

    foreach ($sources as $source) {
      foreach ($consumers as $consumer) {
        self::oneConnection($source, $consumer);
      }
    }

  }

  private static function oneConnection(IEventsSource $source, IEventsConsumer $consumer) {
    foreach ($consumer->subscribeMeToEvents() as $event_name) {
      foreach ($source->events() as $event_name2) {
        if ($event_name == $event_name2) {
          $source->subscribe($event_name, function(Collector $event) use ($consumer) {
            $consumer->respondToEvent($event);
          });
        }
      }
    }
  }
}