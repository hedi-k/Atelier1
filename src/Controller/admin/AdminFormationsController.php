<?php

namespace App\Controller\admin;

/**
 * Description of AdminFormationsController
 *
 * @author hedi
 */
use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminFormationsController extends AbstractController {

    CONST PAGE_AFORMATIONS = "admin/admin.formations.html.twig";
    CONST PAGE_AFORMATIONSBIS = 'admin.formations';

    /**
     * 
     * @var FormationRepository
     */
    private FormationRepository $formationRepository;

    /**
     * 
     * @var CategorieRepository
     */
    private CategorieRepository $categorieRepository;

    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_AFORMATIONS, [
                    'formations' => $formations,
                    'categories' => $categories
        ]);
    }

    /**
     * Action des boutons tri dans l'onglet formations du back-office
     * @Route("/admin/tri/{champ}/{ordre}/{table}", name="admin.formations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    public function sort($champ, $ordre, $table = ""): Response {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_AFORMATIONS, [
                    'formations' => $formations,
                    'categories' => $categories
        ]);
    }

    /**
     *  Action du bouton filtrer dans l'onglet formations du back-office
     * @Route("/admin/recherche/{champ}/{table}", name="admin.formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table = ""): Response {
        if ($this->isCsrfTokenValid('filtre_' . $champ, $request->get('_token'))) {
            $valeur = $request->get("recherche");
            $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
            $categories = $this->categorieRepository->findAll();
            return $this->render(self::PAGE_AFORMATIONS, [
                        'formations' => $formations,
                        'categories' => $categories,
                        'valeur' => $valeur,
                        'table' => $table
            ]);
        }
        return $this->redirectToRoute(self::PAGE_AFORMATIONSBIS);
    }

    /**
     * Action du bouton supprimer dans l'onglet formations du back-office
     * @Route("admin/suppr/{id}", name="admin.formation.suppr")
     * @param type $id
     * @return Response
     */
    public function suppr($id): Response {
        $formation = $this->formationRepository->find($id);
        $this->formationRepository->remove($formation, true);
        return $this->redirectToRoute(self::PAGE_AFORMATIONSBIS);
    }

    /**
     * Action du bouton Ajouter une formation dans l'onglet formations du back-office
     * @Route("/admin/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response {
        $formation = New Formation();
        $formformation = $this->createForm(FormationType::class, $formation);
        $formformation->handleRequest($request);
        if ($formformation->isSubmitted() && $formformation->isValid()) {
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute(self::PAGE_AFORMATIONSBIS);
        }
        return $this->render("admin/admin.formation.ajout.html.twig", [
                    'formation' => $formation,
                    'formformation' => $formformation->createView()
        ]);
    }

    /**
     * Action du bouton Editer dans l'onglet formations du back-office
     * @Route("/admin/edit/{id}", name="admin.formation.edit")
     * @param type $id
     * @param Request $request
     * @return Response
     */
    public function edit($id, Request $request): Response {
        $formation = $this->formationRepository->find($id);
        $formformation = $this->createForm(FormationType::class, $formation);
        $formformation->handleRequest($request);
        if ($formformation->isSubmitted() && $formformation->isValid()) {
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute(self::PAGE_AFORMATIONSBIS);
        }
        return $this->render("admin/admin.formation.edit.html.twig", [
                    'formation' => $formation,
                    'formformation' => $formformation->createView()
        ]);
    }
}
