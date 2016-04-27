<?php

namespace Kyoushu\InlineSwiftmailerTransport\Tests;

use Swift_Events_EventListener;
use Swift_Mime_Message;
use Symfony\Component\DomCrawler\Crawler;

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

    public function testInlinedMessageCss()
    {
        $message = new \Swift_Message('Foo bar', $this->getHtml('original'), 'text/html');

        $transport = $this->createInlineTransport();
        $transport->inlineMessageCss($message);

        $body = $message->getBody();

        $this->assertElementsHaveStyle($body, 'div', 'padding: 10px;');
        $this->assertElementsHaveStyle($body, 'p', 'font-family: sans-serif;');
    }

    protected function assertElementsHaveStyle($html, $selector, $style)
    {
        $crawler = new Crawler($html);

        /** @var \DOMElement[] $elements */
        $elements = $crawler->filter($selector);

        $this->assertGreaterThan(0, count($elements));

        foreach($elements as $element){
            $elementStyle = $element->getAttribute('style');
            $this->assertEquals($style, $elementStyle);
        }
    }

}

class MockCounterTransport implements \Swift_Transport
{

    /**
     * @var int
     */
    protected $sendCount = 0;

    /**
     * @return int
     */
    public function getSendCount()
    {
        return $this->sendCount;
    }

    /**
     * @return bool
     */
    public function isStarted()
    {
        return true;
    }

    public function start()
    {
    }

    public function stop()
    {
    }

    /**
     * @param Swift_Mime_Message $message
     * @param string[] $failedRecipients
     * @return int
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $this->sendCount++;
        return 1;
    }

    /**
     * @param Swift_Events_EventListener $plugin
     */
    public function registerPlugin(Swift_Events_EventListener $plugin)
    {
    }

}