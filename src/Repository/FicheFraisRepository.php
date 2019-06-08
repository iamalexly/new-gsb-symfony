<?php
/**
 * GSB Frais Symfony 4.3 - 2019
 * @author Alexandre Lebailly <alexlebaillypro@gmail.com> <http://iamalex.fr>
 */

namespace App\Repository;

use App\Entity\FicheFrais;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FicheFrais|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheFrais|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheFrais[]    findAll()
 * @method FicheFrais[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheFraisRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FicheFrais::class);
    }

    /**
     * Récupère les fiches de frais en fonction de l'ID de l'utilisateur
     * @param int $userId
     * @return mixed
     */
    public function findByUser(int $userId)
    {
        return $this->createQueryBuilder('ff')
            ->andWhere('ff.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('ff.moisFiche', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère une fiche de frais en fonction de l'utilisateur et d'une date
     * @param int $userId
     * @param $date
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByUserAndDate(int $userId, $date)
    {
        $newDate = DateTime::createFromFormat('Y-m-d', $date);
        $month = $newDate->format('m');
        $year = $newDate->format('Y');

        $nbrDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $firstDay = $newDate->format($year . '-' . $month . '-' . '01');
        $lastDay = $newDate->format($year . '-' . $month . '-' . $nbrDays);

        $parameters = [
            'userId' => $userId,
            'firstDay' => $firstDay,
            'lastDay' => $lastDay
        ];

        return $this->createQueryBuilder('ff')
            ->andWhere('ff.user = :userId')
            ->andWhere('ff.moisFiche BETWEEN :firstDay AND :lastDay')
            ->setParameters($parameters)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $userId
     * @param int $idFiche
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByUserAndId(int $userId, int $idFiche)
    {
        $parameters = [
            'userId' => $userId,
            'idFiche' => $idFiche
        ];

        return $this->createQueryBuilder('ff')
            ->andWhere('ff.user = :userId')
            ->andWhere('ff.id = :idFiche')
            ->setParameters($parameters)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
