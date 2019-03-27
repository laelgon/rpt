<?php

namespace Rpt\Event;

use League\Event\AbstractEvent;

class Collector extends AbstractEvent
{
  private $name;

  private $values = [];

  public function __construct(string $name) {
    $this->name = $name;
  }

  public function getName()
  {
    return $this->name;
  }

  public function addValue($value)
  {
    $this->values[] = $value;
  }

  public function getValues() {
    return $this->values;
  }

}