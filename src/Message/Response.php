<?php

namespace Omnipay\Moyasar\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;


/**
 * Moyasar Response
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
    private $statusCode;

    public function __construct(RequestInterface $request, $data, $statusCode)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
    }

    public function getCode()
    {
        return $this->statusCode;
    }

    public function isPending()
    {
        return isset($this->data['status']) &&
            $this->data['status'] === 'initiated';
    }

    public function isSuccessful()
    {
        return isset($this->data['status']) &&
            ($this->data['status'] === 'paid' ||
            $this->data['status'] === 'succeeded');
    }

    public function isFailed()
    {
        return isset($this->data['status']) &&
            $this->data['status'] === 'failed';
    }

    public function isRedirect()
    {
        return $this->isPending();
    }

    public function isAuthorized()
    {
        return isset($this->data['status']) &&
            $this->data['status'] === 'authorized';
    }

    public function isCaptured()
    {
        return isset($this->data['status']) &&
            $this->data['status'] === 'captured';
    }

    public function isVoided()
    {
        return isset($this->data['status']) &&
            $this->data['status'] === 'voided';
    }

    public function isRefunded()
    {
        return isset($this->data['status']) &&
            $this->data['status'] === 'refunded';
    }

    public function getTransactionReference()
    {
        return isset($this->data['id']) ?
            $this->data['id'] :
            null;
    }

    public function getSource()
    {
        return isset($this->data['source']) ?
            $this->data['source'] :
            null;
    }

    public function getTransactionUrl()
    {
        $source = $this->getSource();
        return $source ? $source['transaction_url'] : null;
    }

    public function getRedirectUrl()
    {
        return $this->getTransactionUrl();
    }

    public function hasError()
    {
        return isset($this->data['message']);
    }

    public function getMessage()
    {
        return isset($this->data['message']) ?
            $this->data['message'] :
            null;
    }
}
