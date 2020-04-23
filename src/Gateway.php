<?php

namespace Omnipay\Moyasar;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\GatewayInterface;

/**
 * Moyasar payment gateway
 */
class Gateway extends AbstractGateway implements GatewayInterface
{
	/**
	 * Get gateway identifier
	 * @return string
	 */
	public function getName()
    {
        return 'Moyasar';
    }

    public function getDefaultParameters()
    {
        return [
        	'apiKey' => '',
        ];
    }

    public function setApiKey(string $apiKey) : void
    {
    	$this->setParameter('apiKey', $apiKey);
    }

    public function getApiKey() : string
    {
    	return $this->getParameter('apiKey');
    }

    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Moyasar\Message\PaymentRequest', $parameters);
    }

    public function authorize(array $parameters = [])
    {
    	$parameters['isManual'] = true;
    	return $this->createRequest('\Omnipay\Moyasar\Message\PaymentRequest', $parameters);
    }

    public function capture(array $parameters = [])
    {
    	$parameters['paymentId'] = $parameters['id'];
    	return $this->createRequest('\Omnipay\Moyasar\Message\CaptureRequest', $parameters);
    }

    public function void(array $parameters = [])
    {
    	$parameters['paymentId'] = $parameters['id'];
    	return $this->createRequest('\Omnipay\Moyasar\Message\VoidRequest', $parameters);
    }

    public function refund(array $parameters = [])
    {
    	$parameters['paymentId'] = $parameters['id'];
    	return $this->createRequest('\Omnipay\Moyasar\Message\RefundRequest', $parameters);
    }

    public function fetch($id)
    {
    	return $this->createRequest('\Omnipay\Moyasar\Message\FetchRequest', [
    		'paymentId' => $id,
    	]);
    }
}
