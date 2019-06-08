<?php
/**
 * GSB Frais Symfony 4.3 - 2019
 * @author Alexandre Lebailly <alexlebaillypro@gmail.com> <http://iamalex.fr>
 */

namespace App\Repository;

use App\Entity\LigneFraisHorsForfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LigneFraisHorsForfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneFraisHorsForfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneFraisHorsForfait[]    findAll()
 * @method LigneFraisHorsForfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneFraisHorsForfaitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LigneFraisHorsForfait::class);
    }

    /**
     * Calcul le montant total des frais hors forfait pour une fiche de frais donnée
     * @param int $idFiche
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTotalByFiche(int $idFiche)
    {
        return $this->createQueryBuilder('lfhf')
            ->andWhere('lfhf.ficheFrais = :idFiche')
            ->setParameter('idFiche', $idFiche)
            ->select('SUM(lfhf.montant) as montant')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
