<?php

namespace Kyoushu\InlineSwiftmailerTransport\Tests\MessageFilter;

use Kyoushu\InlineSwiftmailerTransport\Tests\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class MessageFilterTestCase extends TestCase
{

    /**
     * @param \Swift_Transport|null $deliveryTransport
     * @param \Swift_Events_EventDispatcher|null $dispatcher
     * @return \Kyoushu\InlineSwiftmailerTransport\InlineTransport
     */
    protected function createInlineTransport(\Swift_Transport $deliveryTransport = null, \Swift_Events_EventDispatcher $dispatcher = null)
    {
        $transport = parent::createInlineTransport($deliveryTransport, $dispatcher);
        $transport->clearMessageFilters();
        return $transport;
    }

    /**
     * @param \Swift_Mime_Message $message
     * @param string $cssSelector
     * @param string $attrKey
     * @param string $expected
     */
    protected function assertBodyElementAttributesEquals(\Swift_Mime_Message $message, $cssSelector, $attrKey, $expected)
    {
        $body = $message->getBody();

        $crawler = new Crawler($body);

        /** @var \DOMElement[] $elements */
        $elements = $crawler->filter($cssSelector);

        $this->assertGreaterThan(0, count($elements));

        foreach($elements as $element){
            $elementAttrValue = $element->getAttribute($attrKey);
            $this->assertEquals($expected, $elementAttrValue);
        }
    }

}