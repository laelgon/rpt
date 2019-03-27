<?php

namespace Rpt\Event;

use League\Event\Emitter;

trait EventsSource
{
  private $emitter;

  private function initializeEventSourcing() {
    $this->emitter = new Emitter();
  }

  private function fireEvent($event_name) {
    /* @var $event \Rpt\Collector */
    $event = $this->emitter->emit(new Collector($event_name));
    return $event->getValues();
  }

  public function subscribe($event_name, $callable) {
    $this->emitter->addListener($event_name, $callable);
  }

}