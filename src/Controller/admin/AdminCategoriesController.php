<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminCategoriesController
 *
 * @author hedi
 */
class AdminCategoriesController extends AbstractController {

    const PAGE_ACATEGORIES = "admin/admin.categories.html.twig";
    const PAGE_ACATEGORIESBIS = 'admin.categories';

    /**
     * 
     * @var CategorieRepository
     */
    private CategorieRepository $categorieRepository;

    function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * @Route("/admin/categories", name="admin.categories")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response {
        $categories = $this->categorieRepository->findAll();
        $categorie = new Categorie();
        $formcategorie = $this->createForm(CategorieType::class, $categorie);
        $formcategorie->handleRequest($request);
        if ($formcategorie->isSubmitted() && $formcategorie->isValid()) {
            $this->categorieRepository->add($categorie, true);
            return $this->redirectToRoute(self::PAGE_ACATEGORIESBIS);
        }
        return $this->render(self::PAGE_ACATEGORIES, [
                    'categories' => $categories,
                    'formcategorie' => $formcategorie->createView()
        ]);
    }

    /**
     * Action du bouton supprimer dans l'onglet categorie du back-office
     * @Route("admin/categorie/sippr/{id}", name="admin.categorie.suppr")
     * @param type $id
     * @return Reponse
     */
    public function suppr($id): Response {
        $categorie = $this->categorieRepository->find($id);
        if (count($categorie->getFormations()) == 0) {
            $this->categorieRepository->remove($categorie, true);
            return $this->redirectToRoute(self::PAGE_ACATEGORIESBIS);
        } else {
            $this->addFlash('echec', 'impossible de supprimer une categorie qui contient des formations');
            return $this->redirectToRoute(self::PAGE_ACATEGORIESBIS);
        }
    }
}
