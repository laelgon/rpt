<?php

include 'vendor/autoload.php';

$characters =

$game = \Rpt\Game\Game::get();
$cli = new \Rpt\IO\Console();

while ($game->isActive()) {
  $user_input = $cli->prompt($game->prompt());
  $result = $game->input($user_input);
  if ($result) {
    $cli->out($result);
  }
}









