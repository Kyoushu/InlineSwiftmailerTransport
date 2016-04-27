<?php

namespace Kyoushu\InlineSwiftmailerTransport\Tests;

class InlineTransportTest extends TestCase
{

    public function testSend()
    {
        $counterTransport = new MockCounterTransport();

        $message = new \Swift_Message('Foo bar', 'foo');
        $message->setFrom('from@example.com');
        $message->setTo('to@example.com');

        $transport = $this->createInlineTransport($counterTransport);
        $transport->send($message);

        $this->assertEquals(1, $counterTransport->getSendCount());
    }

}