<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\FilterType;
use App\Repository\ProductRepository;
use App\Services\EntityBuilderService\EntityBuilderService;
use App\Services\ProductFilterService\FilterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product', name: 'product_')]
class ProductController extends AbstractController
{
    public function __construct(
        private readonly FilterService        $filterService,
        private readonly EntityBuilderService $entityBuilderService,
    )
    {
    }

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $filter = $this->entityBuilderService->buildFilter($productRepository);

        $form = $this->createForm(FilterType::class, $filter);
        $form->handleRequest($request);

        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findByFilters($filter, $this->filterService),
            'form' => $form,
        ]);
    }

    #[Route('/{name}', name: 'show')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
