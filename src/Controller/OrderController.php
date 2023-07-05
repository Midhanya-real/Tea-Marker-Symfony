<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'app_order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findByUserField($this->getUser()),
        ]);
    }

    #[Route('show/{id}', name: 'app_order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        if ($order->getUserId() !== $this->getUser()) {
            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/new', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, OrderRepository $orderRepository): Response
    {
        $order = new Order();

        $product = $em
            ->getRepository(Product::class)
            ->findOneBy(['id' => $request->query->get('product')]);

        $order->setUserId($this->getUser());
        $order->setProductId($product);

        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderRepository->save($order, true);

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);

    }

    #[Route('{id}/paymentRedirect', name: 'app_order_paymentredirect', methods: ['GET', 'POST'])]
    public function paymentRedirect(Order $order): RedirectResponse
    {
        return $this->redirectToRoute('app_payment_new', ['order' => $order->getId()], Response::HTTP_SEE_OTHER);
    }
}
