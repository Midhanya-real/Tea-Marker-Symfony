<?php

namespace App\Services\PaymentStatusUpdateService;

use App\config\Enums\OrderStatus;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use App\Services\PaymentService\PaymentService;
use Symfony\Component\Uid\Uuid;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Model\Payment\PaymentInterface;

class UpdateStatusService
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private readonly PaymentService    $paymentService,
    )
    {
    }

    private function isEqual(string $currenStatus, string $apiOrderStatus): bool
    {
        return $currenStatus === $apiOrderStatus;
    }

    private function isPaid(bool $paidStatus): bool
    {
        return $paidStatus;
    }

    private function setNewStatus(OrderStatus $status, Payment $payment, PaymentRepository $paymentRepository): void
    {
        $payment->setStatus($status);
        $paymentRepository->save($payment, true);
    }

    private function getNewStatus(?PaymentInterface $apiPayment, Payment $payment): bool|OrderStatus
    {
        if (!$this->isPaid($apiPayment->getPaid())) {
            return OrderStatus::NoPaid;
        }

        if (!$this->isEqual($payment->getStatus()->value, $apiPayment->getStatus())) {
            return OrderStatus::getStatus($apiPayment->getStatus());
        }

        return true;
    }

    private function update(array $payments): void
    {
        foreach ($payments as $payment) {
            try {
                $apiPayment = $this->paymentService->getInfo(Uuid::fromString($payment->getYookassaId()));

                $updatedStatus = $this->getNewStatus($apiPayment, $payment);

                $this->setNewStatus($updatedStatus, $payment, $this->paymentRepository);

            } catch (BadApiRequestException $exception) {
                $this->paymentRepository->remove($payment, true);
            }
        }
    }

    public function refresh(): void
    {
        $payments = $this->paymentRepository->getIndefinitePayments();

        $this->update($payments);
    }
}