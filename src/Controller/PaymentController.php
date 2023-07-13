<?php

namespace App\Controller;

use App\config\Enums\OrderStatus;
use App\Entity\Order;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use App\Services\PaymentService\PaymentProcess\CreatePaymentProcess;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
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
        private readonly CreatePaymentProcess $createPaymentProcess,
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
     * @throws UnauthorizedException
     * @throws ApiConnectionException
     */
    #[Route('/new', name: 'app_payment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, PaymentRepository $paymentRepository): Response
    {
        $order = $em
            ->getRepository(Order::class)
            ->findOneBy(['id' => $request->query->get('order')]);

        $createResponse = $this->createPaymentProcess->execute($order);

        $payment = new Payment();

        $payment->setPrice($createResponse->amount->value);
        $payment->setYookassaId(Uuid::fromString($createResponse->getId()));
        $payment->setUserId($order->getUserId());
        $payment->setOrderId($order);
        $payment->setStatus(OrderStatus::Pending);

        $paymentRepository->save($payment, true);

        return $this->redirect($createResponse->getConfirmation()->getConfirmationUrl());
    }
}
