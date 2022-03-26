<?php
namespace MyApp;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface {

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
        $conn->send(json_encode(['data'=>'new user subscribed']));
    }
    public function onOpen(ConnectionInterface $conn) {
        $conn->send(json_encode(['data'=>'new user connected']));
    }
    public function onClose(ConnectionInterface $conn) {
        $conn->send(json_encode(['data'=>'new user disconnected']));
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    /**
     * A lookup of all the topics clients have subscribed to
     */
    protected $subscribedTopics = ['my-cat','kitten-cat'];

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        $this->subscribedTopics[$topic->getId()] = $topic;
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onBlogEntry($entry) {
        $entryData = json_decode($entry, true);

        // file_put_contents('dump-entry.txt', $entry);
        // echo "<OnEntry>\n$entry\n</OnEntry>\n";

        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists($entryData['category'], $this->subscribedTopics)) {
            return;
        }

        $topic = $this->subscribedTopics[$entryData['category']];

        // file_put_contents('dump-topc.txt', $topic);

        echo "Broadcast: $topic\n";

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entryData);
    }

    /* The rest of our methods were as they were, omitted from docs to save space */
}