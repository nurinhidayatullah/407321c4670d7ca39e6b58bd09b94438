<?php

require __DIR__ . '/../../vendor/autoload.php';

use Nhida\LevartTest\Config\Database;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$exchange = 'router';
$queue = 'msgs';
$consumerTag = 'consumer';

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

/*
The following code is the same both in the consumer and the producer.
In this way we are sure we always have a queue to consume from and an
exchange where to publish messages.
 */

/*
name: $queue
passive: false
durable: true // the queue will survive server restarts
exclusive: false // the queue can be accessed in other channels
auto_delete: false //the queue won't be deleted once the channel is closed.
 */
$channel->queue_declare($queue, false, true, false, false);

/*
name: $exchange
type: direct
passive: false
durable: true // the exchange will survive server restarts
auto_delete: false //the exchange won't be deleted once the channel is closed.
 */

$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

$channel->queue_bind($queue, $exchange);

/**
 * @param \PhpAmqpLib\Message\AMQPMessage $message
 */
function process_message($message)
{
    echo "\n--------\n";
    echo $message->body;
    echo "\n--------\n";
    $payload_email = json_decode($message->body);
    $message->ack();

    // Send a message with the string "quit" to cancel the consumer.
    if ($message->body === 'quit') {
        $message->getChannel()->basic_cancel($message->getConsumerTag());
    }

    try {
        include_once __DIR__ . "/../../config/email.php";
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '283446158bd360';
        $mail->Password = '4298aeaf4aa18d';

        //Recipients
        $mail->setFrom($payload_email->email, $payload_email->name);
        $mail->addAddress($payload_email->to_email, $payload_email->to_name);
        $mail->addReplyTo($payload_email->email, $payload_email->name);

        //Content
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = $payload_email->subject;
        $mail->Body = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    try {
        $connection = Database::getConnection();
        $statement = $connection->prepare("INSERT INTO sent_emails(sender_email, sender_name, recipient_email, recipient_name, subject) VALUES(?,?,?,?,?)");
        $statement->execute([$payload_email->email, $payload_email->name, $payload_email->to_email, $payload_email->to_name, $payload_email->subject]);
    } catch (Exception $e) {
        echo "Database Error";
    }
}

/*
queue: Queue from where to get the messages
consumer_tag: Consumer identifier
no_local: Don't receive messages published by this consumer.
no_ack: If set to true, automatic acknowledgement mode will be used by this consumer. See https://www.rabbitmq.com/confirms.html for details.
exclusive: Request exclusive consumer access, meaning only this consumer can access the queue
nowait:
callback: A PHP Callback
 */

$channel->basic_consume($queue, $consumerTag, false, false, false, false, 'process_message');

/**
 * @param \PhpAmqpLib\Channel\AMQPChannel           $channel
 * @param \PhpAmqpLib\Connection\AbstractConnection $connection
 */
function shutdown($channel, $connection)
{
    $channel->close();
    $connection->close();
}

register_shutdown_function('shutdown', $channel, $connection);

// Loop as long as the channel has callbacks registered
$channel->consume();
