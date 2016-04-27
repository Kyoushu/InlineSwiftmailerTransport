<?php

namespace Kyoushu\InlineSwiftmailerTransport\Tests\MessageFilter;

use Kyoushu\InlineSwiftmailerTransport\MessageFilter\InlineEmbeddedCssMessageFilter;

class InlineEmbeddedCssMessageFilterTest extends MessageFilterTestCase
{

    public function testFilterMessage()
    {
        $message = new \Swift_Message('Foo', $this->loadHtml('original'), 'text/html');

        $transport = $this->createInlineTransport();
        $transport->addMessageFilter(new InlineEmbeddedCssMessageFilter());
        $transport->send($message);

        $this->assertBodyElementAttributesEquals($message, 'div', 'style', 'padding: 10px;');
        $this->assertBodyElementAttributesEquals($message, 'p', 'style', 'font-family: sans-serif;');
    }

}