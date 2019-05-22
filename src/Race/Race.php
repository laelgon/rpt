<?php

namespace Rpt\Race;

use Rpt\Event\IEventsConsumer;
use Rpt\Language;
use Rpt\Utility\EnumCheck;

abstract class Race implements IEventsConsumer
{
  use Language;

  private $sizesEnum = ["Small", "Medium"];

  private $size;
  private $speed;

  abstract public function subscribeMeToEvents(): array;

  public function __construct(string $size, int $speed)
  {
    $this->size = EnumCheck::check("size", $this->sizesEnum, $size);

    $this->speed = $speed;
  }

}