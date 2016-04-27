<?php

namespace Kyoushu\InlineSwiftmailerTransport\Tests;

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
     * @param \Swift_Mime_Message $message
     * @param string[] $failedRecipients
     * @return int
     */
    public function send(\Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $this->sendCount++;
        return 1;
    }

    /**
     * @param \Swift_Events_EventListener $plugin
     */
    public function registerPlugin(\Swift_Events_EventListener $plugin)
    {
    }

}