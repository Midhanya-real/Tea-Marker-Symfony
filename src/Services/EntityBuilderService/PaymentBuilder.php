<?php

namespace App\Services\EntityBuilderService;

use App\config\Enums\OrderStatus;
use App\Entity\Order;
use App\Entity\Payment;
use App\Services\PaymentService\PaymentProcess\CreatePaymentProcess;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use YooKassa\Request\Payments\CreatePaymentResponse;

class PaymentBuilder
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CreatePaymentProcess   $createPaymentProcess,
    )
    {
    }

    private function getOrder(int $orderId): Order
    {
        return $this->entityManager
            ->getRepository(Order::class)
            ->findOneBy(['id' => $orderId]);
    }

    private function getCreatePaymentResponse(Order $order): CreatePaymentResponse
    {
        return $this->createPaymentProcess->execute($order);
    }

    private function getEntity(CreatePaymentResponse $paymentResponse, Order $order, OrderStatus $status): Payment
    {
        $payment = new Payment();

        $payment->setPrice($paymentResponse->amount->value);
        $payment->setYookassaId(Uuid::fromString($paymentResponse->getId()));
        $payment->setUserId($order->getUserId());
        $payment->setOrderId($order);
        $payment->setStatus($status);

        return $payment;
    }

    public function build(int $orderId, OrderStatus $status): array
    {
        $order = $this->getOrder($orderId);
        $paymentResponse = $this->getCreatePaymentResponse($order);
        $payment = $this->getEntity(paymentResponse: $paymentResponse, order: $order, status: $status);

        return [
            'payment' => $payment,
            'redirect_url' => $paymentResponse->getConfirmation()->getConfirmationUrl(),
        ];
    }
}