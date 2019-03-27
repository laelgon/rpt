<?php

require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketGameController implements MessageComponentInterface
{
  const ASSIGN_PLAYERS = 0;

  private $clients;
  private $game;

  private $state;

  public function __construct(\Rpt\Game $game)
  {
    $this->game = $game;
    $this->clients = new \SplObjectStorage;
    $this->state = self::ASSIGN_PLAYERS;
  }

  public function onOpen(ConnectionInterface $conn)
  {
    // Store the new connection to send messages to later
    $this->clients->attach($conn);

    $conn->send($this->game->chooseACharacterMessage());

    echo "New connection! ({$conn->resourceId})\n";
  }

  public function onMessage(ConnectionInterface $from, $msg)
  {
    $numRecv = count($this->clients) - 1;
    echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
      , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

    foreach ($this->clients as $client) {
      if ($from !== $client) {
        /* @var $client ConnectionInterface */
        $client->send($msg);
      }
    }
  }

  public function onClose(ConnectionInterface $conn)
  {
    // The connection is closed, remove it, as we can no longer send it messages
    $this->clients->detach($conn);

    echo "Connection {$conn->resourceId} has disconnected\n";
  }

  public function onError(ConnectionInterface $conn, \Exception $e)
  {
    echo "An error has occurred: {$e->getMessage()}\n";

    $conn->close();
  }
}

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
  new HttpServer(
    new WsServer(
      new WebSocketGameController(\Rpt\Game::get())
    )
  ),
  8080
);

$server->run();