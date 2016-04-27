<?php

namespace Kyoushu\InlineSwiftmailerTransport;

use Kyoushu\InlineSwiftmailerTransport\MessageFilter\InlineCssMessageFilter;
use Kyoushu\InlineSwiftmailerTransport\MessageFilter\MessageFilterInterface;

class InlineTransport implements \Swift_Transport
{

    const HEADER_BODY_INLINED = 'X-KyoushuInlineTransport-BodyInlined';

    /**
     * @var \Swift_Transport
     */
    protected $deliveryTransport;

    /**
     * @var \Swift_Events_EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var MessageFilterInterface[]
     */
    protected $messageFilters;

    /**
     * @param \Swift_Transport $deliveryTransport
     * @param \Swift_Events_EventDispatcher $dispatcher
     */
    public function __construct(\Swift_Transport $deliveryTransport, \Swift_Events_EventDispatcher $dispatcher)
    {
        $this->deliveryTransport = $deliveryTransport;
        $this->dispatcher = $dispatcher;

        $this->clearMessageFilters();
        $this->addMessageFilter(new InlineCssMessageFilter());
    }

    /**
     * @return \Swift_Transport
     */
    public function getDeliveryTransport()
    {
        return $this->deliveryTransport;
    }

    public function clearMessageFilters()
    {
        $this->messageFilters = array();
    }

    /**
     * @return MessageFilterInterface[]
     */
    public function getMessageFilters()
    {
        return $this->messageFilters;
    }

    /**
     * @param MessageFilterInterface $filter
     * @return $this
     */
    public function addMessageFilter(MessageFilterInterface $filter)
    {
        $this->messageFilters[] = $filter;
        return $this;
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
        foreach($this->getMessageFilters() as $filter){
            if(!$filter->supportsMessage($message)) continue;
            $filter->filterMessage($message);
        }

        return $this->deliveryTransport->send($message, $failedRecipients);
    }

    /**
     * @param \Swift_Events_EventListener $plugin
     */
    public function registerPlugin(\Swift_Events_EventListener $plugin)
    {
        $this->dispatcher->bindEventListener($plugin);
        $this->deliveryTransport->registerPlugin($plugin);
    }

}