<?php

namespace App\Services\EntityBuilderService;

use App\config\Enums\OrderStatus;
use App\Entity\Order;
use App\Entity\User;
use App\Services\PaymentService\PaymentProcess\CreatePaymentProcess;
use Doctrine\ORM\EntityManagerInterface;

class EntityBuilderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CreatePaymentProcess   $createPaymentProcess,
    )
    {
    }

    public function buildOrder(?User $user, int $productId): Order
    {
        $orderBuilder = new OrderBuilder($this->entityManager);

        return $orderBuilder->build(user: $user, productId: $productId);
    }

    public function buildPayment(int $orderId, OrderStatus $status): array
    {
        $paymentBuilder = new PaymentBuilder($this->entityManager, $this->createPaymentProcess);

        return $paymentBuilder->build(orderId: $orderId, status: $status);
    }
}