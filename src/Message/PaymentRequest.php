<?php

namespace Omnipay\Moyasar\Message;

class PaymentRequest extends AbstractRequest {

	public function getData()
	{
		// Validate parameters required for payment
		$this->validate('amount', 'source', 'currency', 'callbackUrl', 'description');

		$manual = (bool) $this->getParameter('isManual');

		return [
			'amount' => $this->getAmount() * 100,
			'currency' => $this->getCurrency(),
			'source' => $this->getParameter('source'),
			'description' => $this->getParameter('description'),
			'callback_url' => $this->getParameter('callbackUrl'),
			'manual' => $manual,
		];
	}

	public function getEndpoint()
	{
		return parent::getEndpoint() . '/payments';
	}
}
