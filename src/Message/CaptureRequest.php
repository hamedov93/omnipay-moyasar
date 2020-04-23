<?php

namespace Omnipay\Moyasar\Message;

class CaptureRequest extends AbstractRequest {

	public function getData()
	{
		// Validate parameters required for capture
		$this->validate('paymentId');

		$amount = $this->getParameter('amount');

		$data = [];

		// Check if we should capture a different amount
		// It should be equal to or less than the authorized
		// amount, otherwise the request will fail
		if (is_numeric($amount))
		{
			$data['amount'] = $amount * 100;
		}
	}

	public function getEndpoint()
	{
		$id = $this->getParameter('paymentId');
		return parent::getEndpoint() . '/payments/'.$id.'/capture';
	}
}
