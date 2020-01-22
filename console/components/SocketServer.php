<?php
/**
 *
 */
namespace console\components;

use frontend\models\ChatLog;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * Class SocketServer
 * @package console\components
 */
class SocketServer implements MessageComponentInterface
{
    protected $clients;

    /**
     * SocketServer constructor.
     */
    public function __construct()
    {
        $this->clients = new \SplObjectStorage; // Для хранения технической информации об присоединившихся клиентах используется технология SplObjectStorage, встроенная в PHP
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $this->sendHelloMessage($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $msgArray = json_decode($msg, true);

        ChatLog::create($msgArray);

        if ($msgArray['type'] === ChatLog::SHOW_HISTORY) {
            $this->showHistory($from);
        } else {
            foreach ($this->clients as $client) {
                $client->send($msg);
            }
        }
    }

    /**
     * @param ConnectionInterface $conn
     */
    private function showHistory(ConnectionInterface $conn) {
        $chatLogsQuery = ChatLog::find()->orderBy('created_at ASC');
        foreach($chatLogsQuery->each() as $chatLog) {
            $this->sendMessage($conn, ['message' => $chatLog->message, 'username' => $chatLog->username]);
        }
    }


    /**
     * @param $conn
     * @param $msg
     */
    private function sendMessage(ConnectionInterface $conn, array $msg){
        $conn->send(json_encode($msg));
    }

    /**
     * @param ConnectionInterface $conn
     */
    private function sendHelloMessage(ConnectionInterface $conn)
    {
        //data - дернуть из монго
        $this->sendMessage($conn, ['message' => 'Всем привет', 'username' => 'Чат студентов geekbrains.ru']);
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}