<?php

namespace App\Controller\admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminPlaylistsController
 *
 * @author hedi
 */
class AdminPlaylistsController extends AbstractController {

    const PAGE_APLAYLISTS = "admin/admin.playlists.html.twig";
    const PAGE_APLAYLISTSBIS = 'admin.playlists';

    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;

    function __construct(PlaylistRepository $playlistRepository,
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }

    /**
     * @Route("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    public function index(): Response {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_APLAYLISTS, [
                    'playlists' => $playlists,
                    'categories' => $categories
        ]);
    }

    /**
     * Action des boutons tri dans l'onglet playlist du back-office
     * @Route("/admin/playlists/tri/{champ}/{ordre}", name="admin.playlists.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response {
        if ($champ == "name") {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        }
        if ($champ == "nombreDeFormation") {
            $playlists = $this->playlistRepository->findAllOrderByNombre($ordre);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_APLAYLISTS, [
                    'playlists' => $playlists,
                    'categories' => $categories
        ]);
    }

    /**
     * Action du bouton filtrer dans l'onglet playlist du back-office
     * @Route("/admin/playlists/recherche/{champ}/{table}",name="admin.playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table = ""): Response {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_APLAYLISTS, [
                    'playlists' => $playlists,
                    'categories' => $categories,
                    'valeur' => $valeur,
                    'table' => $table
        ]);
    }

    /**
     * Action du bouton supprimer dans l'onglet playlist du back-office
     * @Route("admin/playlists/{id}",name="admin.playlist.suppr")
     * @param type $id
     * @return Response
     */
    public function suppr($id): Response {
        $playlist = $this->playlistRepository->find($id);
        if (count($playlist->getFormations()) == 0) {
            $this->playlistRepository->remove($playlist, true);
            return $this->redirectToRoute(self::PAGE_APLAYLISTSBIS);
        } else {
            $this->addFlash('echec', 'impossible de supprimer une playlist qui contient des formations');
            return $this->redirectToRoute(self::PAGE_APLAYLISTSBIS);
        }
    }

    /**
     * Action du bouton Ajouter playlist dans l'onglet playlist du back-office
     * @Route("/admin/playlist/ajout",name="admin.playlist.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response {
        $playlist = New Playlist();
        $formplaylist = $this->createForm(PlaylistType::class, $playlist);
        $formplaylist->handleRequest($request);
        if ($formplaylist->isSubmitted() && $formplaylist->isValid()) {
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute(self::PAGE_APLAYLISTSBIS);
        }
        return $this->render("admin/admin.playlist.ajout.html.twig", [
                    'playlist' => $playlist,
                    'formplaylist' => $formplaylist->createView()
        ]);
    }

    /**
     * Action du bouton éditer dans l'onglet playlist du back-office
     * @Route("/admin/playlist/edit/{id}", name="admin.playlist.edit")
     * @param type $id
     * @param Request $request
     * @return Response
     */
    public function edit($id, Request $request): Response {
        $playlist = $this->playlistRepository->find($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        $formplaylist = $this->createForm(PlaylistType::class, $playlist);
        $formplaylist->handleRequest($request);
        if ($formplaylist->isSubmitted() && $formplaylist->isValid()) {
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute(self::PAGE_APLAYLISTSBIS);
        }
        return $this->render("admin/admin.playlist.edit.html.twig", [
                    'playlist' => $playlist,
                    'formplaylist' => $formplaylist->createView(),
                    'playlistformations' => $playlistFormations
        ]);
    }
}
