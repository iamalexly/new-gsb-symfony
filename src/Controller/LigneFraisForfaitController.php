<?php
/**
 * GSB Frais Symfony 4.3 - 2019
 * @author Alexandre Lebailly <alexlebaillypro@gmail.com> <http://iamalex.fr>
 */

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Form\LigneFraisForfaitType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LigneFraisForfaitController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        $ligneFraisForfait = new LigneFraisForfait();
        $form = $this->createForm(LigneFraisForfaitType::class, $ligneFraisForfait);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupération de la fiche de frais de l'utilisateur en fonction du mois et de l'année de consommation du frais hors forfait
            $fichesFrais = $this->getDoctrine()
                ->getRepository(FicheFrais::class)
                ->findByUserAndDate($user->getId(), $request->request->get('ligne_frais_forfait')['dateCreation']);

            $fraisForfait = $this->getDoctrine()
                ->getRepository(FraisForfait::class)
                ->find($request->request->get('ligne_frais_forfait')['fraisForfait']);

            // Si une fiche existe, on ajoute le frais hors forfait en bdd en le liant à la fiche de frais existante
            if ($fichesFrais != null) {

                // Vérifie que la fiche de frais ne soit pas déjà validé (impossible d'ajouter des frais si déjà validé)
                if ($fichesFrais->getEtat()->getLibelle() == 'Valide') {
                    $this->addFlash('error', 'Vous ne pouvez pas ajouter des frais à une fiche de frais validé.');
                    return $this->redirectToRoute('app_read_fiches_frais');
                }

                $ligneFraisForfait->setFicheFrais($fichesFrais);
                $ligneFraisForfait->setFraisForfait($fraisForfait);
                $entityManager->persist($ligneFraisForfait);
                $entityManager->flush();

                return $this->redirectToRoute('app_read_details_fiche_frais', ['idFiche' => $fichesFrais->getId()]);

            // Sinon, on crée une nouvelle fiche de frais et on ajoute le frais hors forfait en le liant à la fiche de frais créée
            } else {

                $etat = $this->getDoctrine()
                    ->getRepository(Etat::class)
                    ->find(2);

                $ficheFrais = new FicheFrais();
                $ficheFrais->setMoisFiche(DateTime::createFromFormat('Y-m-d', $request->request->get('ligne_frais_forfait')['dateCreation']));
                $ficheFrais->setNbJustificatifs(0);
                $ficheFrais->setMontantValide(0);
                $ficheFrais->setDateModif(new DateTime());
                $ficheFrais->setUser($user);
                $ficheFrais->setEtat($etat);

                $ligneFraisForfait->setFicheFrais($ficheFrais);
                $ligneFraisForfait->setFraisForfait($fraisForfait);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ficheFrais);
                $entityManager->persist($ligneFraisForfait);
                $entityManager->flush();

                return $this->redirectToRoute('app_read_details_fiche_frais', ['idFiche' => $ficheFrais->getId()]);

            }
        }

        $this->addFlash('success', 'Le frais forfait à bien été ajouté à la fiche de frais.');

        return $this->render('ligne_frais_forfait/new.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
