<?php

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of PlaylistRepositoryTest
 *
 * @author hedi
 */
class PlaylistRepositoryTest extends KernelTestCase {
    /*
     * Permet d’accéder au kernel et de récupérer l’instance du repository
     */

    public function recupRepository(): PlaylistRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }

    /**
     * Permet de crée une playlist pour les tests.
     * @return Playlist
     */
    public function newPlaylist(): Playlist {
        $playlist = (new Playlist())
                ->setName("Playlist test");
        return $playlist;
    }
    
    /**
     *  Permet de tester la méthode add()de Playlistrepository
     */
    public function testAddPlaylist() {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylist = $repository->count([]);
        $repository->add($playlist, true);
        $this->assertEquals($nbPlaylist + 1, $repository->count([]), "erreur sur testAddPlaylist()");
    }
    
    /**
     * Permet de tester la méthode remove()de Playlistrepository
     */
    public function testRemovePlaylist() {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $nbPlaylist = $repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylist - 1, $repository->count([]), "erreur sur testRemovePlaylist()");
    }

    /**
     * Permet de tester la méthode findAllOrderByName de Playlistrepository
     */
    public function testFindAllOrderByName() {
        $repository = $this->recupRepository();
        $nbPlaylist = $repository->count([]);
        $playlist = $repository->findAllOrderByName('ASC');
        $nb2Playlist = count($playlist);
        $this->assertEquals($nbPlaylist, $nb2Playlist, "ereur sur le test testFindAllOrderByName()");
    }

    /**
     * Permet de tester la méthode findByContaineValue Playlistrepository
     */
    public function testFindByContainValue() {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist()
                    ->setName("Playlist Essai de containeValue");
        $repository->add($playlist, true);
        $uneplaylist = $repository->findByContainValue('name', "Playlist Essai de containeValue","");
        $nbplaylist= count($uneplaylist);
        $this->assertEquals($nbplaylist, 1, "erreur sur le test de testFindByContainValue()");
        $repository->remove($playlist,true);
    }
}
