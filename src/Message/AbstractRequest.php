<?php
/**
 * Moyasar Abstract Request
 */

namespace Omnipay\Moyasar\Message;

use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;
use Omnipay\Common\Exception\InvalidResponseException;
use GuzzleHttp\RequestOptions;

/**
 * Moyasar Abstract Request
 *
 * This class forms the base class for Moyasar credit
 * or debit card (e.g., Visa, MasterCard or Mada)
 * and Sadad payments.
 *
 *
 * @link https://moyasar.com/docs/api/#http-request
 */
abstract class AbstractRequest extends OmnipayAbstractRequest
{
	const API_VERSION = 'v1';

    private $endpoint = 'https://api.moyasar.com';

    public function setApiKey(string $apiKey) : void
    {
    	$this->setParameter('apiKey', $apiKey);
    }

    public function getApiKey() : string
    {
    	return $this->getParameter('apiKey');
    }

    public function getSource()
    {
    	return $this->getParameter('source');
    }

    public function setSource($source)
    {
    	$this->setParameter('source', $source);
    }

    public function getCallbackUrl() : ?string
    {
    	return $this->getParameter('callbackUrl');
    }

    public function setCallbackUrl(string $url) : void
    {
    	$this->setParameter('callbackUrl', $url);
    }

    public function getPaymentId()
    {
    	return $this->getParameter('paymentId');
    }

    public function setPaymentId($id)
    {
    	$this->setParameter('paymentId', $id);
    }

    public function getIsManual() : bool
    {
    	return $this->getParameter('isManual');
    }

    /**
     * Specify whether the payment should be captured manually
     * or immediately
     * 
     * @param boolean $manual
     */
    public function setIsManual(bool $manual) : void
    {
    	$this->setParameter('isManual', $manual);
    }

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'POST';
    }

    public function getEndpoint()
	{
		return $this->endpoint . '/' . self::API_VERSION;
	}

    public function sendData($data)
    {
        $method = strtoupper($this->getHttpMethod());
    	$endpoint = $this->getEndpoint();
    	if (! empty($data)) {
    		$endpoint .= '?' . http_build_query($data);
    	}

    	$headers = [
    		'Authorization' => 'Basic '.base64_encode($this->getApiKey().':'),
    		// 'Content-Type' => 'application/json',
    		// 'Accept' => 'application/json',
    	];

        try {
            $httpResponse = $this->httpClient->request(
            	$method,
            	$endpoint,
            	$headers,
            	json_encode($data)
            );

            // Empty response body should be parsed also as and empty array
            $body = (string) $httpResponse->getBody()->getContents();
            $arrayResponse = !empty($body) ? json_decode($body, true) : [];
            return $this->response = $this->createResponse($arrayResponse, $httpResponse->getStatusCode());
        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new Response($this, $data, $statusCode);
    }
}
