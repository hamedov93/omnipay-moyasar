<?php

namespace Omnipay\Moyasar\Message;

class VoidRequest extends AbstractRequest {

	public function getData()
	{
		// Validate parameters required for capture
		$this->validate('paymentId');

		return [];
	}

	public function getEndpoint()
	{
		$id = $this->getParameter('paymentId');
		return parent::getEndpoint() . '/payments/'.$id.'/void';
	}
}
