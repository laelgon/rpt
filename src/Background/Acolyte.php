<?php


namespace Rpt\Background;

use Rpt\Event\Collector;
use Rpt\Event\IEventsConsumer;
use Rpt\Language;

class Acolyte extends Background implements IEventsConsumer
{
  use Language;

  public function __construct(array $languages)
  {
    $number_of_languages = count($languages);
    if ($number_of_languages != 2) {
      throw new \Exception("Acolytes get 2 languages. {$number_of_languages} given.");
    }

    foreach ($languages as $language) {
      $this->addLanguage($language);
    }
  }

  public function subscribeMeToEvents(): array
  {
    return [
      'get.proficiencies.skill',
      'get.languages',
    ];
  }

  public function respondToEvent(Collector $event)
  {
    if ($event->getName() === 'get.proficiencies.skill') {
      $event->addValue('insight');
      $event->addValue('religion');
    }
    elseif ($event->getName() === 'get.languages') {
      foreach ($this->getLanguages() as $language) {
        $event->addValue($language);
      }
    }
  }

  public function __toString()
  {
    return "Acolyte";
  }


}