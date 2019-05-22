<?php


namespace Rpt\IO;


class Console
{
  public function prompt(array $prompt) {
    foreach ($prompt['message'] as $line) {
      print_r($line . "\n");
    }

    while (TRUE) {
      foreach ($prompt['options'] as $index => $option) {
        print_r("{$index}) {$option} \n");
      }
      $user_input = readline(":");

      $options = array_keys($prompt['options']);
      if (in_array($user_input, $options)) {
        return $user_input;
      }
      else {
        print_r("Invalid Option {$user_input}. Try again. \n");
      }
    }
  }

  public function out(array $output) {
    print_r(PHP_EOL);
    foreach ($output as $line) {
      print_r($line . PHP_EOL);
    }
    print_r(PHP_EOL);
  }
}