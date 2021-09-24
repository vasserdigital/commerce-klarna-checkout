<?php


namespace ellera\commerce\klarna\klarna\session;

use ellera\commerce\klarna\gateways\Base;
use ellera\commerce\klarna\klarna\KlarnaResponse;
use ellera\commerce\klarna\models\forms\HostedForm;

class Create extends KlarnaResponse
{
    public function isSuccessful(): bool
    {
        return 200 <= $this->raw_response->getStatusCode() && $this->raw_response->getStatusCode() < 300;
    }

    public function isProcessing(): bool
    {
        return false;
    }

    public function isRedirect(): bool
    {
        return true;
    }

    /**
     * Create constructor.
     * @param Base $gateway
     * @param HostedForm $form
     * @param string $payment_session_url
     * @param string $reference
     * @throws \yii\base\ErrorException
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct(Base $gateway, HostedForm $form, string $payment_session_url, string $reference)
    {
        parent::__construct($gateway);

        $this->endpoint = '/hpp/v1/sessions';

        $this->body = $form->generateCreateSessionRequestBody($payment_session_url);

        if(isset($reference)) $this->setTransactionReference($reference);
        else $this->setTransactionReference('!No Ref');

        $this->post();
    }
}