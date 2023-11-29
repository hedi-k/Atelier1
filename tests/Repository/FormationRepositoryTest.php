<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of FormationRepositoryTest
 *
 * @author hedi
 */
class FormationRepositoryTest extends KernelTestCase {
    /*
     * Permet d’accéder au kernel et de récupérer l’instance du repository
     */

    public function recupRepository(): FormationRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }

    /*
     * Permet de compter le nombre l'élements dans la table formation
      public function test(){
      $repository =$this->recupRepository();
      $nb = $repository->count([]);
      $this->assertEquals(237, $nb);
      }
     */

    /**
     * Permet de crée une formation pour les tests.
     * @return FormationRepository
     */
    public function newFormation(): Formation {
        $formation = (new Formation())
                ->setTitle("Formation test");
        return $formation;
    }

    /**
     * Permet de tester la méthode add()de FormationRepository.
     */
    public function testAddFormation() {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormation = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormation + 1, $repository->count([]), "erreur sur testAddFormation()");
    }

    /**
     * Permet de tester la méthode remove()de FormationRepository.
     */
    public function testRemoveFormation() {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormation = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormation - 1, $repository->count([]), "erreur sur testRemoveFormation()");
    }

    /**
     * Permet de tester la méthode findAllOrderBy() de FormationRepository.
     */
    public function testFindAllOrderBy() {
        $repository = $this->recupRepository();
        $nbformation = $repository->count([]);
        $formation = $repository->findAllOrderBy('title', 'ASC', "");
        $nb2formation = count($formation);
        $this->assertEquals($nbformation, $nb2formation, "erreur sur testFindAllOrderBy()");
    }

    /**
     * Permet de tester la méthode findByContainValue() de FormationRepository.
     */
    public function testFindByContainValue() {
        $repository = $this->recupRepository();
        $formation = $this->newFormation()
                ->setTitle("Essai de containeValue");
        $repository->add($formation, true);
        $uneformation = $repository->findByContainValue('title', 'Essai de containeValue', "");
        $nbformation = count($uneformation);
        $this->assertEquals($nbformation, 1, "erreur sur testFindAllOrderBy()");
        $repository->remove($formation, true);
    }
}
