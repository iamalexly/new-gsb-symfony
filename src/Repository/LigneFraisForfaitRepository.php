<?php
/**
 * GSB Frais Symfony 4.3 - 2019
 * @author Alexandre Lebailly <alexlebaillypro@gmail.com> <http://iamalex.fr>
 */

namespace App\Repository;

use App\Entity\LigneFraisForfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LigneFraisForfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneFraisForfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneFraisForfait[]    findAll()
 * @method LigneFraisForfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneFraisForfaitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LigneFraisForfait::class);
    }

    /**
     * Calcul le montant total des frais forfait pour une fiche de frais donnée
     * @param int $idFiche
     * @return mixed
     */
    public function getTotalByFiche(int $idFiche)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT SUM(ff.montant * lff.quantite) as montant
                FROM App\Entity\FraisForfait ff, App\Entity\LigneFraisForfait lff
                WHERE lff.ficheFrais = :idFiche AND ff.id = lff.fraisForfait'
        )->setParameter('idFiche', $idFiche);

        $result = $query->execute();

        return $result[0];
    }
}
