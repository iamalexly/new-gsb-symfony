<?php
/**
 * GSB Frais Symfony 4.3 - 2019
 * @author Alexandre Lebailly <alexlebaillypro@gmail.com> <http://iamalex.fr>
 */

namespace App\Repository;

use App\Entity\FraisForfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FraisForfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method FraisForfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method FraisForfait[]    findAll()
 * @method FraisForfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FraisForfaitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FraisForfait::class);
    }
}
