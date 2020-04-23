<?php

namespace Omnipay\Moyasar\Message;

class RefundRequest extends AbstractRequest {

	public function getData()
	{
		// Validate parameters required for refund
		$this->validate('paymentId');

		$amount = $this->getParameter('amount');

		$data = [];

		// Check if we should refund a different amount
		// It should be equal to or less than the paid
		// amount, otherwise the request will fail
		if (is_numeric($amount))
		{
			$data['amount'] = $amount * 100;
		}
	}

	public function getEndpoint()
	{
		$id = $this->getParameter('paymentId');
		return parent::getEndpoint() . '/payments/'.$id.'/refund';
	}
}
