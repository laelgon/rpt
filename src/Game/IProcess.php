<?php


namespace Rpt\Game;


interface IProcess
{
  public function isActive();

  public function prompt();

  public function input($input);

}