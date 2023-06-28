<?php

namespace App\Services\PaymentBuilder;

use YooKassa\Client;
use YooKassa\Common\Exceptions\ApiConnectionException;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\AuthorizeException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ExtensionNotFoundException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\InternalServerError;
use YooKassa\Common\Exceptions\NotFoundException;
use YooKassa\Common\Exceptions\ResponseProcessingException;
use YooKassa\Common\Exceptions\TooManyRequestsException;
use YooKassa\Common\Exceptions\UnauthorizedException;
use YooKassa\Model\Payment\PaymentInterface;
use YooKassa\Request\Payments\CancelResponse;
use YooKassa\Request\Payments\CreateCaptureResponse;
use YooKassa\Request\Payments\CreatePaymentResponse;
use YooKassa\Request\Payments\PaymentResponse;
use YooKassa\Request\Payments\PaymentsResponse;

class YookassaAPI
{
    public function __construct(
        private readonly array $config,
        private readonly Client $client,
    )
    {
        $this->client->setAuth($this->config['market_id'], $this->config['secret_key']);
    }

    /**
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws ApiException
     * @throws ExtensionNotFoundException
     * @throws BadApiRequestException
     * @throws AuthorizeException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws ApiConnectionException
     */
    public function createPayment(array $paymentData): CreatePaymentResponse
    {
        return $this->client->createPayment($paymentData, uniqid('', true));
    }

    /**
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws ApiException
     * @throws ExtensionNotFoundException
     * @throws BadApiRequestException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function createRefund(string $paymentId, array $paymentData): CreateCaptureResponse
    {
        return $this->client->capturePayment($paymentData, $paymentId, uniqid('', true));
    }

    /**
     * @throws NotFoundException
     * @throws ApiException
     * @throws ResponseProcessingException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function canceledPayment(string $paymentId): CancelResponse
    {
        return $this->client->cancelPayment($paymentId);
    }

    /**
     * @throws NotFoundException
     * @throws ApiException
     * @throws ResponseProcessingException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function getPayments(): PaymentsResponse
    {
        return $this->client->getPayments();
    }

    /**
     * @throws NotFoundException
     * @throws ApiException
     * @throws ResponseProcessingException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function getPaymentInfo(string $paymentId): ?PaymentInterface
    {
        return $this->client->getPaymentInfo($paymentId);
    }
}