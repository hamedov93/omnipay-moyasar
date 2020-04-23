<?php

namespace Omnipay\Moyasar\Message;

class FetchRequest extends AbstractRequest {

	public function getData()
	{
		// Validate required parameters
		$this->validate('paymentId');

		return [];

	}

	public function getEndpoint()
	{
		return parent::getEndpoint() . '/payments/' . $this->getParameter('paymentId');
	}

	public function getHttpMethod()
	{
		return 'GET';
	}
}
