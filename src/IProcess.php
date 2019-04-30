<?php


namespace Rpt;


interface IProcess
{
  public function isActive();

  public function prompt();

  public function input($input);

}