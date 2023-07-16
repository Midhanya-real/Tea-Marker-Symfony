<?php

namespace App\Repository;

use App\Entity\Product;
use App\Services\ProductFilterService\Entity\Filter;
use App\Services\ProductFilterService\FilterService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findByFilters(Filter $filter, FilterService $filterService): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $filterQuery = $filterService->setQueryBuilder($queryBuilder)
            ->setFilter($filter)
            ->getCategory('p.category IN (:categories)')
            ->getBrand('p.brand IN (:brands)')
            ->getType('p.type IN (:types)')
            ->getCountry('p.country IN (:countries)')
            ->getWeight('p.weight IN (:weights)')
            ->getPrice('p.price = IN (:prices)')
            ->getFilters();

        return $filterQuery
            ->setParameters([
                'categories' => $filter->getCategories(),
                'brands' => $filter->getBrands(),
                'types' => $filter->getTypes(),
                'countries' => $filter->getCountries(),
            ])
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
