<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Person>
 *
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    // 完全一致
    public function findByName($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.name = ?1')
            ->setParameter(1, $value)
            ->getQuery()
            ->getResult();
    }

    // あいまい検索
    public function findByNameAimai($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.name like ?1')
            ->setParameter(1, '%' . $value . '%')
            ->getQuery()
            ->getResult();
    }

    // 〇〇歳以上
    public function findByAge($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.age >= ?1')
            ->setParameter(1, $value)
            ->getQuery()
            ->getResult();
    }

    // 複数の検索テキストを指定 (検索時にカンマ区切りで指定)
    public function findByNameAndSearch($value)
    {
        $arr = explode(',', $value);
        return $this->createQueryBuilder('p')
            ->where('p.name in (?1, ?2)')
            ->setParameters(array(1 => $arr[0], 2 => $arr[1]))
            ->getQuery()
            ->getResult();
    }

    // Exprでin式を利用
    public function findByAgeIn($value)
    {
        $arr = explode(',', $value);
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->where($builder->expr()->in('p.age', $arr))
            ->getQuery()
            ->getResult();
    }

    // 複数条件のAND検索
    // 〇〇歳以上××歳以下の検索
    public function findByAgeBetween($value)
    {
        $arr = explode(',', $value);
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->where($builder->expr()->gte('p.age', '?1'))
            ->andWhere($builder->expr()->lte('p.age', '?2'))
            ->setParameters(array(1 => $arr[0], 2 => $arr[1]))
            ->getQuery()
            ->getResult();
    }

    // 複数条件のORの検索
    public function findByNameOrMail($value)
    {
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->where($builder->expr()->like('p.name', '?1'))
            ->orWhere($builder->expr()->like('p.mail', '?2'))
            ->setParameters(array(1 => '%' . $value . '%', 2 => '%' . $value . '%'))
            ->getQuery()
            ->getResult();
    }

    // ソート
    // 年齢の上から順に並べる
    public function findAllwithSort($value)
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.age', 'DESC')
            ->getQuery()
            ->getResult();
    }


    // レコードの取得範囲
    // QueryBuilderのgetResultで取得されるのは、
    // 基本的に植索された全レコードです。が、数があまりに大量だったりすると、一度に全部取り出して処理するのは大変です。
    // そのようなとき、「どこからどこまで取り出すか」を指定するためのメソッドも用意されています。
    //  setFirstResult($offset) で、取得開始位置を指定します。
    //  setMaxResults($limit) で、取得件数を指定します。
    public function findRsults($value)
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.age', 'DESC')
            ->setFirstResult(1)
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Person[] Returns an array of Person objects
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

    //    public function findOneBySomeField($value): ?Person
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
