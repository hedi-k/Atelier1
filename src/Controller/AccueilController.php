<?php
namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur de l'accueil
 * Essais commentaire pour le déploiment en continue
 *
 * @author emds modifiée par hédi
 */
class AccueilController extends AbstractController{
      
    /**
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * 
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository) {
        $this->repository = $repository;
    }   
    
    /**
     * Méthode pour la page d'accueil.
     * @Route("/", name="accueil")
     * @return Response
     */
    public function index(): Response {
        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/accueil.html.twig", [
            'formations' => $formations
        ]); 
    }
    
    /**
     * Méthode pour la page CGU.
     * @Route("/cgu", name="cgu")
     * @return Response
     */
    public function cgu(): Response{
        return $this->render("pages/cgu.html.twig"); 
    }
}
