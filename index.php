<?php

include 'vendor/autoload.php';





echo PHP_EOL;
echo "{$lacross}";
echo PHP_EOL;



echo PHP_EOL;
echo "{$jadis}";
echo PHP_EOL;

echo PHP_EOL;

$combat = new \Rpt\Combat();
$combat->setAttacker($jadis);
$combat->setDefender($lacross);
$combat->toTheDeath();









