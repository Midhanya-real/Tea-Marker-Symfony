<?php

namespace App\Controller;

use App\config\Enums\OrderStatus;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use App\Services\EntityBuilderService\EntityBuilderService;
use App\Services\PaymentService\PaymentProcess\RefundPaymentProcess;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

#[Route('/payment', name: 'payment_')]
class PaymentController extends AbstractController
{
    public function __construct(
        private readonly EntityBuilderService $entityBuilder,
        private readonly RefundPaymentProcess $refundPaymentProcess,
    )
    {
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentRepository $paymentRepository): Response
    {
        $yookassaPayment = $this->entityBuilder
            ->buildPayment(orderId: $request->query->get('order'), status: OrderStatus::Pending);

        $payment = $paymentRepository->getByOrder($yookassaPayment['payment']->getOrderId());

        if (!$payment) {
            $paymentRepository->save($yookassaPayment['payment'], true);
        } else {
            $payment->setYookassaId($yookassaPayment['payment']->getYookassaId());
            $payment->setStatus($yookassaPayment['payment']->getStatus());

            $paymentRepository->save($payment, true);
        }

        return $this->redirect($yookassaPayment['redirect_url'], Response::HTTP_SEE_OTHER);
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
    #[Route('/{id}/refund', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Payment $payment, PaymentRepository $paymentRepository): Response
    {
        $refund = $this->refundPaymentProcess->execute($payment);

        if ($refund->getStatus() === OrderStatus::Succeeded->value) {
            $payment->setStatus(OrderStatus::Refunded);
            $paymentRepository->save($payment, true);
        }

        return $this->redirectToRoute('app_order_index');
    }
}
