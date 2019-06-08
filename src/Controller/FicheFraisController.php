<?php
/**
 * GSB Frais Symfony 4.3 - 2019
 * @author Alexandre Lebailly <alexlebaillypro@gmail.com> <http://iamalex.fr>
 */

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Form\FicheFraisType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FicheFraisController extends AbstractController
{
    /**
     * Affiche toutes les fiches de frais d'un utilisateur
     * @return Response
     */
    public function showAllByUser(): Response
    {
        $user = $this->getUser();

        $fichesFrais = $this->getDoctrine()
            ->getRepository(FicheFrais::class)
            ->findByUser($user->getId());

        return $this->render('fiche_frais/show-all-by-user.html.twig', [
            'fichesFrais' => $fichesFrais
        ]);
    }

    /**
     * Affiche les détails d'une fiche de frais (frais forfait et hors forfait)
     * @param int $idFiche
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function showDetailsByUser(int $idFiche): Response
    {
        $user = $this->getUser();

        // Récupère la fiche de frais en fonction de l'utilisateur et de l'id de la fiche de frais
        $ficheFrais = $this->getDoctrine()
            ->getRepository(FicheFrais::class)
            ->findByUserAndId($user->getId(), $idFiche);

        // Redirection si la fiche de frais n'a pas été trouvé
        if ($ficheFrais == null) {
            return $this->redirectToRoute('app_read_fiches_frais');
        }

        // Récupère les fiches de frais forfait et hors forfait de la fiche de frais consultée
        $lignesFraisHorsForfait = $ficheFrais->getLigneFraisHorsForfaits();
        $lignesFraisForfait = $ficheFrais->getLigneFraisForfaits();

        // Calcul le total des frais hors forfait de la fiche de frais consultée
        $montantTotalFHF = $this->getDoctrine()
            ->getRepository(LigneFraisHorsForfait::class)
            ->getTotalByFiche($idFiche);

        // Calcul le total des frais forfait de la fiche de frais consultée
        $montantTotalFF = $this->getDoctrine()
            ->getRepository(LigneFraisForfait::class)
            ->getTotalByFiche($idFiche);

        // Calcul le total des différents frais de la fiche de frais consultée
        $montantTotal = $montantTotalFHF['montant'] + $montantTotalFF['montant'];

        return $this->render('fiche_frais/show-details-by-user.html.twig', [
            'user' => $user,
            'ficheFrais' => $ficheFrais,
            'lignesFraisHorsForfait' => $lignesFraisHorsForfait,
            'lignesFraisForfait' => $lignesFraisForfait,
            'montantTotalFHF' => $montantTotalFHF['montant'],
            'montantTotalFF' => $montantTotalFF['montant'],
            'montantTotal' => $montantTotal
        ]);
    }

    /**
     * Affiche toutes les fiches de frais (seulement accessible au comptables)
     * @return Response
     */
    public function manageAll(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_COMPTABLE');

        $fichesFrais = $this->getDoctrine()
            ->getRepository(FicheFrais::class)
            ->findAll();

        return $this->render('fiche_frais/show-all-manage.html.twig', [
            'fichesFrais' => $fichesFrais
        ]);
    }

    /**
     * Affiche les details d'une fiche de frais (seulement accessible au comptable)
     * @param int $idFiche
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     */
    public function manageDetails(int $idFiche, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_COMPTABLE');

        $user = $this->getUser();

        // Récupère la fiche de frais en fonction de l'utilisateur et de l'id de la fiche de frais
        $ficheFrais = $this->getDoctrine()
            ->getRepository(FicheFrais::class)
            ->find($idFiche);

        // Redirection si la fiche de frais n'a pas été trouvé
        if ($ficheFrais == null) {
            return $this->redirectToRoute('app_manage_fiches_frais');
        }

        // Récupère les fiches de frais forfait et hors forfait de la fiche de frais consultée
        $lignesFraisHorsForfait = $ficheFrais->getLigneFraisHorsForfaits();
        $lignesFraisForfait = $ficheFrais->getLigneFraisForfaits();

        // Calcul le total des frais hors forfait de la fiche de frais consultée
        $montantTotalFHF = $this->getDoctrine()
            ->getRepository(LigneFraisHorsForfait::class)
            ->getTotalByFiche($idFiche);

        // Calcul le total des frais forfait de la fiche de frais consultée
        $montantTotalFF = $this->getDoctrine()
            ->getRepository(LigneFraisForfait::class)
            ->getTotalByFiche($idFiche);

        // Calcul le total des différents frais de la fiche de frais consultée
        $montantTotal = $montantTotalFHF['montant'] + $montantTotalFF['montant'];

        $form = $this->createForm(FicheFraisType::class, $ficheFrais);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        // Vérifie si le formulaire à bien été soumis et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Vérifie que la fiche de frais n'appartienne pas au comptable connecté
            if ($ficheFrais->getUser()->getId() == $user->getId()) {
                $this->addFlash('error', 'Vous ne pouvez valider une fiche de frais vous appartenant.');
                return $this->redirectToRoute('app_manage_fiches_frais');
            }

            // Recupère l'entité Etat "Valide"
            $etat = $this->getDoctrine()
                ->getRepository(Etat::class)
                ->find(1);

            // Vérifie que le montant validé ne soit pas supérieur au montant total de la fiche de frais
            if ($request->request->get('fiche_frais')['montantValide'] > $montantTotal) {
                $this->addFlash('error', 'Le montant validé ne peut pas être supérieur au montant total de la fiche de frais.');
                return $this->redirectToRoute('app_manage_fiches_frais');
            }

            // MAJ du montant valide, de l'état et de la date de validation de la fiche de frais
            $ficheFrais->setMontantValide($request->request->get('fiche_frais')['montantValide']);
            $ficheFrais->setEtat($etat);
            $ficheFrais->setDateModif(new DateTime());

            $entityManager->persist($ficheFrais);
            $entityManager->flush();

            $this->addFlash('success', 'L\'état de la fiche de frais à bien été mis à jour.');

            return $this->redirectToRoute('app_manage_fiches_frais');

        }

        return $this->render('fiche_frais/show-manage-details.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'ficheFrais' => $ficheFrais,
            'lignesFraisHorsForfait' => $lignesFraisHorsForfait,
            'lignesFraisForfait' => $lignesFraisForfait,
            'montantTotalFHF' => $montantTotalFHF['montant'],
            'montantTotalFF' => $montantTotalFF['montant'],
            'montantTotal' => $montantTotal
        ]);
    }
}