<?php
/**
 * GSB Frais Symfony 4.3 - 2019
 * @author Alexandre Lebailly <alexlebaillypro@gmail.com> <http://iamalex.fr>
 */

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('jdoe@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setNom('Doe');
        $user->setPrenom('John');
        $user->setAdresse('50 Rue Dupont');
        $user->setCp(42100);
        $user->setVille('Saint-Etienne');
        $user->setDateEmbauche(new DateTime());

        $manager->persist($user);

        $user = new User();
        $user->setEmail('ccarignan@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setNom('Carignan');
        $user->setPrenom('Céline');
        $user->setAdresse('18 Rue Simone Weil');
        $user->setCp(59210);
        $user->setVille('Coudekerque-Branche');
        $user->setDateEmbauche(new DateTime());

        $manager->persist($user);

        $user = new User();
        $user->setEmail('dtremblay@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setNom('Tremblay');
        $user->setPrenom('Didiane');
        $user->setAdresse('15 Rue Marguerite');
        $user->setCp(69100);
        $user->setVille('Villeurbanne');
        $user->setDateEmbauche(new DateTime());

        $manager->persist($user);

        $user = new User();
        $user->setEmail('hlaforge@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setNom('Laforge');
        $user->setPrenom('Hervé');
        $user->setAdresse('58 Rue de Geneve');
        $user->setCp(30100);
        $user->setVille('Alès"');
        $user->setDateEmbauche(new DateTime());

        $manager->persist($user);

        $user = new User();
        $user->setEmail('admin@gsb.com');
        $user->setRoles(['ROLE_COMPTABLE']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setNom('Lebailly');
        $user->setPrenom('Alexandre');
        $user->setAdresse('48 Rue Mandela');
        $user->setCp(42100);
        $user->setVille('Saint-Etienne');
        $user->setDateEmbauche(new DateTime());

        $manager->persist($user);

        $manager->flush();
    }
}