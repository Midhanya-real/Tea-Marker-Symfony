<?php

namespace App\Repository;

use App\Entity\Product;
use App\Services\ProductFilterService\Entity\Filter;
use App\Services\ProductFilterService\FilterService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findByBordersPrice(): array
    {
        return $this->createQueryBuilder('p')
            ->select('MIN(p.price) as min_price, MAX(p.price) as max_price')
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findByBordersWeight(): array
    {
        return $this->createQueryBuilder('p')
            ->select('MIN(p.weight) as min_weight, MAX(p.weight) as max_weight')
            ->getQuery()
            ->getSingleResult();
    }

    public function findByFilters(Filter $filter, FilterService $filterService): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $filterQuery = $filterService->setQueryBuilder($queryBuilder)
            ->setFilter($filter)
            ->getCategory('p.category IN (:categories)', $filter->getCategories())
            ->getBrand('p.brand IN (:brands)', $filter->getBrands())
            ->getType('p.type IN (:types)', $filter->getTypes())
            ->getCountry('p.country IN (:countries)', $filter->getCountries())
            ->getWeight('p.weight BETWEEN :minWeight AND :maxWeight', [
                'min' => $filter->getMinWeight(),
                'max' => $filter->getMaxWeight()
            ])
            ->getPrice('p.price BETWEEN :minPrice AND :maxPrice', [
                'min' => $filter->getMinPrice(),
                'max' => $filter->getMaxPrice()
            ])
            ->getFilters();

        return $filterQuery
            ->getQuery()
            ->getResult();
    }
}
