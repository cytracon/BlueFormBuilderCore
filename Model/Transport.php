<?php
/**
 * Cytracon
 *
 * This source file is subject to the Cytracon Software License, which is available at https://www.cytracon.com/license.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.cytracon.com for more information.
 *
 * @category  BlueFormBuilder
 * @package   Cytracon_BlueFormBuilderCore
 * @copyright Copyright (C) 2019 Cytracon (https://www.cytracon.com)
 */

namespace Cytracon\BlueFormBuilderCore\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\TransportInterface;
use Magento\Framework\Phrase;
use Magento\Store\Model\ScopeInterface;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Sendmail;

class Transport implements TransportInterface
{
    /**
     * Configuration path to source of Return-Path and whether it should be set at all
     * @see \Magento\Config\Model\Config\Source\Yesnocustom to possible values
     */
    const XML_PATH_SENDING_SET_RETURN_PATH = 'system/smtp/set_return_path';

    /**
     * Configuration path for custom Return-Path email
     */
    const XML_PATH_SENDING_RETURN_PATH_EMAIL = 'system/smtp/return_path_email';

    /**
     * Whether return path should be set or no.
     *
     * Possible values are:
     * 0 - no
     * 1 - yes (set value as FROM address)
     * 2 - use custom value
     *
     * @var int
     */
    private $isSetReturnPath;

    /**
     * @var string|null
     */
    private $returnPathValue;

    /**
     * @var Sendmail
     */
    private $zendTransport;

    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * @param MessageInterface $message Email message object
     * @param ScopeConfigInterface $scopeConfig Core store config
     * @param null|string|array|\Traversable $parameters Config options for sendmail parameters
     */
    public function __construct(
        MessageInterface $message,
        ScopeConfigInterface $scopeConfig,
        $parameters = null
    ) {
        $this->isSetReturnPath = (int) $scopeConfig->getValue(
            self::XML_PATH_SENDING_SET_RETURN_PATH,
            ScopeInterface::SCOPE_STORE
        );
        $this->returnPathValue = $scopeConfig->getValue(
            self::XML_PATH_SENDING_RETURN_PATH_EMAIL,
            ScopeInterface::SCOPE_STORE
        );

        $this->zendTransport = new Sendmail($parameters);
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function sendMessage()
    {
        try {
            $zendMessage = Message::fromString($this->message->getRawMessage())->setEncoding('utf-8');
            if (2 === $this->isSetReturnPath && $this->returnPathValue) {
                $zendMessage->setSender($this->returnPathValue);
            } elseif (1 === $this->isSetReturnPath && $zendMessage->getFrom()->count()) {
                $fromAddressList = $zendMessage->getFrom();
                $fromAddressList->rewind();
                $zendMessage->setSender($fromAddressList->current()->getEmail());
            }

            $this->zendTransport->send($zendMessage);
        } catch (\Exception $e) {
            throw new MailException(new Phrase($e->getMessage()), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function getMessage()
    {
        return $this->message;
    }
}
