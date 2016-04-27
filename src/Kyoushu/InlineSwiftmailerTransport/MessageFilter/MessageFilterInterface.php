<?php

namespace Kyoushu\InlineSwiftmailerTransport\MessageFilter;

interface MessageFilterInterface
{

    const HEADER_FILTER_LIST = 'X-KyoushuInlineTransport-FilterList';

    /**
     * @return MessageFilterInterface
     */
    public static function create();

    /**
     * @param \Swift_Mime_Message $message
     * @return bool
     */
    public function supportsMessage(\Swift_Mime_Message $message);

    /**
     * @param \Swift_Mime_Message $message
     */
    public function filterMessage(\Swift_Mime_Message $message);

    /**
     * @param \Swift_Mime_Message $message
     * @return mixed
     */
    public function isMessageFiltered(\Swift_Mime_Message $message);

    /**
     * Flags a \Swift_Mime_Message instance as filtered
     *
     * @param \Swift_Mime_Message $message
     * @param bool $filtered
     * @return $this
     */
    public function setMessageFiltered(\Swift_Mime_Message $message, $filtered);

}