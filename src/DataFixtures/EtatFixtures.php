<?php
/**
 * GSB Frais Symfony 4.3 - 2019
 * @author Alexandre Lebailly <alexlebaillypro@gmail.com> <http://iamalex.fr>
 */

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $valide = new Etat();
        $valide->setLibelle('Valide');
        $manager->persist($valide);

        $attente = new Etat();
        $attente->setLibelle('Attente');
        $manager->persist($attente);

        $manager->flush();
    }
}
