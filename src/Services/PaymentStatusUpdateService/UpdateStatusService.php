<?php

namespace App\Services\PaymentStatusUpdateService;

use App\config\Enums\OrderStatus;
use App\Repository\PaymentRepository;
use App\Services\PaymentService\PaymentService;
use Symfony\Component\Uid\Uuid;
use YooKassa\Common\Exceptions\BadApiRequestException;

class UpdateStatusService
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private readonly PaymentService    $paymentService,
    )
    {
    }

    private function update(array $payments): void
    {
        foreach ($payments as $payment) {
            try {
                $apiPayment = $this->paymentService->getInfo(Uuid::fromString($payment->getYookassaId()));

                if ($apiPayment->getStatus() != $payment->getStatus()->value) {
                    $updatedStatus = OrderStatus::getStatus($apiPayment->getStatus());

                    $payment->setStatus($updatedStatus);

                    $this->paymentRepository->save($payment, true);
                }

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