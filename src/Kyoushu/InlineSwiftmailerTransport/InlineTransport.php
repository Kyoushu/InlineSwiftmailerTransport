<?php

namespace Kyoushu\InlineSwiftmailerTransport;

use Kyoushu\InlineSwiftmailerTransport\CssInliner\CssInlinerInterface;
use Kyoushu\InlineSwiftmailerTransport\CssInliner\TijsverkoyenCssInliner;

class InlineTransport implements \Swift_Transport
{

    const HEADER_CSS_INLINED = 'X-KyoushuInlineTransport-CssInlined';

    /**
     * @var \Swift_Transport
     */
    protected $deliveryTransport;

    /**
     * @var \Swift_Events_EventDispatcher
     */
    protected $dispatcher;

    /**
     * @param \Swift_Transport $deliveryTransport
     * @param \Swift_Events_EventDispatcher $dispatcher
     */
    public function __construct(\Swift_Transport $deliveryTransport, \Swift_Events_EventDispatcher $dispatcher)
    {
        $this->deliveryTransport = $deliveryTransport;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return \Swift_Transport
     */
    public function getDeliveryTransport()
    {
        return $this->deliveryTransport;
    }

    /**
     * @return mixed
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
     * @param \Swift_Mime_Message $message
     * @param null|string[] $failedRecipients
     * @return int
     */
    public function send(\Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $this->inlineMessage($message);
        return $this->deliveryTransport->send($message, $failedRecipients);
    }

    /**
     * @param \Swift_Events_EventListener $plugin
     */
    public function registerPlugin(\Swift_Events_EventListener $plugin)
    {
        $this->dispatcher->bindEventListener($plugin);
    }

    /**
     * @param \Swift_Mime_Message $message
     */
    public function inlineMessage(\Swift_Mime_Message $message)
    {
        $this->inlineMessageCss($message);
    }

    /**
     * @return CssInlinerInterface
     */
    protected function createCssInliner()
    {
        return new TijsverkoyenCssInliner();
    }

    /**
     * @param \Swift_Mime_Message $message#
     */
    public function inlineMessageCss(\Swift_Mime_Message $message)
    {
        if($message->getContentType() !== 'text/html') return;

        // Don't inline CSS more than once
        if($message->getHeaders()->has(self::HEADER_CSS_INLINED)) return;
        $message->getHeaders()->addTextHeader(self::HEADER_CSS_INLINED);

        $body = $message->getBody();
        $body = $this->createCssInliner()->inlineCss($body);
        $message->setBody($body);
    }

}