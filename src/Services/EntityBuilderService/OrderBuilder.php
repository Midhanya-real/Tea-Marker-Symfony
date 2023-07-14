<?php

namespace App\Services\EntityBuilderService;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class OrderBuilder
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    private function getProduct(int $productId): Product
    {
        return $this->entityManager
            ->getRepository(Product::class)
            ->findOneBy(['id' => $productId]);
    }

    private function getEntity(?User $user, Product $product): Order
    {
        $order = new Order();

        $order->setUserId($user);
        $order->setProductId($product);

        return $order;
    }

    public function build(?User $user, int $productId): Order
    {
        $product = $this->getProduct($productId);

        return $this->getEntity($user, $product);
    }
}