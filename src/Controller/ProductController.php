<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\FilterType;
use App\Repository\ProductRepository;
use App\Services\ProductFilterService\Entity\Filter;
use App\Services\ProductFilterService\FilterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    public function __construct(
        private readonly FilterService $filterService,
    )
    {
    }

    #[Route('/', name: 'app_product_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $filter = new Filter();
        $form = $this->createForm(FilterType::class, $filter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('product/index.html.twig', [
                'products' => $productRepository->findByFilters($filter, $this->filterService),
                'form' => $form,
            ]);
        }

        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{name}/preOrder', name: 'app_product_setformfororder', methods: ['GET', 'POST'])]
    public function setFormForOrder(Product $product): Response
    {
        return $this->redirectToRoute('app_order_new', ['product' => $product->getId()], Response::HTTP_SEE_OTHER);
    }
}
