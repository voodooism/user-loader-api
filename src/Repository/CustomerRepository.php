<?php

namespace App\Repository;

use App\Entity\Collection\CustomerCollection;
use App\Entity\Customer;
use ArrayIterator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\MappingException;
use Throwable;

/**
 * @psalm-suppress LessSpecificImplementedReturnType
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    private const BATCH_SIZE = 20;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    // /**
    //  * @return Customer[] Returns an array of Customer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByEmail(string $email): ?Customer
    {
        /** @var Customer|null $customer */
        $customer = $this->createQueryBuilder('c')
            ->andWhere('c.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        return $customer;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Customer $customer): void
    {
        $this->getEntityManager()->persist($customer);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws ConnectionException
     * @throws MappingException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveCollection(CustomerCollection $customers, int $batchSize = self::BATCH_SIZE): void
    {
        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();
        try {
            /** @var ArrayIterator $iterator */
            $iterator = $customers->getIterator();
            $i = 0;
            while ($iterator->valid()) {
                /** @var Customer $customer */
                $customer = $iterator->current();

                $em->persist($customer);

                $i++;

                if (($i % $batchSize) === 0) {
                    $em->flush();
                    $em->clear();
                }
                $iterator->next();
            }

            $em->flush();
            $em->clear();

            $em->getConnection()->commit();
        } catch (Throwable $t) {
            $em->getConnection()->rollBack();
            throw $t;
        }
    }

    public function getQueryForPagination(): Query
    {
        $query = $this->createQueryBuilder('c')
            ->getQuery();

        return $query;
    }
}
