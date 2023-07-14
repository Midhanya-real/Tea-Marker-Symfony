<?php

namespace App\Controller;

use App\Actions\PaymentActions\PaymentsResponseStatuses;
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

#[Route('/payment')]
class PaymentController extends AbstractController
{
    public function __construct(
        private readonly EntityBuilderService $entityBuilder,
        private readonly RefundPaymentProcess $refundPaymentProcess,
    )
    {
    }

    #[Route('/new', name: 'app_payment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentRepository $paymentRepository): Response
    {
        $payment = $this->entityBuilder
            ->buildPayment(orderId: $request->query->get('order'), status: OrderStatus::Pending);

        $paymentRepository->save($payment['payment'], true);

        return $this->redirect($payment['redirect_url']);
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
    #[Route('/{id}/refund', name: 'app_payment_edit', methods: ['GET', 'POST'])]
    public function edit(Payment $payment, PaymentRepository $paymentRepository): Response
    {
        $refund = $this->refundPaymentProcess->execute($payment);

        if (PaymentsResponseStatuses::isSuccess($refund->getStatus())) {
            $payment->setStatus(OrderStatus::Refunded);
            $paymentRepository->save($payment, true);
        }

        return $this->redirectToRoute('app_order_index');
    }
}
