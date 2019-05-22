<?php


namespace Rpt\Game;


trait Active
{
  private $active = TRUE;

  public function isActive()
  {
    return $this->active;
  }

}