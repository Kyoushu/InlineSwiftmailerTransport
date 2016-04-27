<?php

namespace Kyoushu\InlineSwiftmailerTransport\Tests;

use Kyoushu\InlineSwiftmailerTransport\InlineTransport;

class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Swift_Transport
     */
    protected function createMockDeliveryTransport()
    {
        /** @var \Swift_Transport|\PHPUnit_Framework_MockObject_MockObject $transport */
        $transport = $this->getMock('\Swift_Transport');

        $transport->method('send')->willReturn(1);
        $transport->method('isStarted')->willReturn(true);

        return $transport;
    }

    /**
     * @param \Swift_Transport|null $deliveryTransport
     * @param \Swift_Events_EventDispatcher|null $dispatcher
     * @return InlineTransport
     */
    protected function createInlineTransport(\Swift_Transport $deliveryTransport = null, \Swift_Events_EventDispatcher $dispatcher = null)
    {
        if($deliveryTransport === null) $deliveryTransport = $this->createMockDeliveryTransport();
        if($dispatcher === null) $dispatcher = new \Swift_Events_SimpleEventDispatcher();

        return new InlineTransport($deliveryTransport, $dispatcher);
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getHtml($name)
    {
        $path = sprintf('%s/Resources/html/%s.html', __DIR__, $name);
        return file_get_contents($path);
    }

}