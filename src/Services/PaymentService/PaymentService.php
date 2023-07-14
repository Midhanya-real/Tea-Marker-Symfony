<?php

namespace App\Services\PaymentService;

use App\Entity\Order;
use App\Entity\Payment;
use App\Services\PaymentService\PaymentBuilder\PaymentBuilder;
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
use YooKassa\Model\CurrencyCode;
use YooKassa\Request\Payments\CancelResponse;
use YooKassa\Request\Payments\CreateCaptureResponse;
use YooKassa\Request\Payments\CreatePaymentResponse;
use YooKassa\Request\Refunds\CreateRefundResponse;

class PaymentService
{
    public function __construct(
        private readonly YookassaAPI    $api,
        private readonly PaymentBuilder $paymentBuilder,
        private readonly string         $redirectURL,
    )
    {
    }

    /**
     * @throws NotFoundException
     * @throws ApiException
     * @throws ResponseProcessingException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws AuthorizeException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws ApiConnectionException
     * @throws UnauthorizedException
     */
    public function create(Order $order): CreatePaymentResponse
    {
        $amount = $order->getProductId()->getPrice() * $order->getCount();

        $payment = $this->paymentBuilder
            ->setAmount(price: $amount, currency: CurrencyCode::RUB)
            ->setConfirmation($this->redirectURL)
            ->setMetaData(user: $order->getUserId(), order: $order)
            ->setTestMode(mode: true)
            ->getPayment();

        return $this->api->createPayment($payment);

    }

    /**
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function cancel(Payment $payment): CancelResponse
    {
        return $this->api->canceledPayment($payment->getYookassaId());
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
    public function capture(Payment $payment): CreateCaptureResponse
    {
        $amount = $this->api->getPaymentInfo($payment->getYookassaId())->amount;

        return $this->api->capturePayment($payment->getYookassaId(), [$amount]);
    }

    /**
     * @throws ResponseProcessingException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws ApiException
     * @throws ExtensionNotFoundException
     * @throws AuthorizeException
     * @throws InternalServerError
     * @throws ApiConnectionException
     */
    public function refund(Payment $payment): CreateRefundResponse
    {
        $amount = $this->api->getPaymentInfo($payment->getYookassaId())->amount;

        $paymentData = [
            'amount' => $amount,
            'payment_id' => $payment->getYookassaId(),
        ];

        return $this->api->createRefund($paymentData);
    }
}