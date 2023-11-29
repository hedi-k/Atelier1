<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of CategorieRepositoryTest
 *
 * @author hedi
 */
class CategorieRepositoryTest extends KernelTestCase {
    
    public function recupRepository(): CategorieRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }
    
    public function newCategorie(): Categorie{
        $categorie = (new Categorie())
                    ->setName("Categorie test");
        return $categorie;
    } 
    
    public function testAddCategorie(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategorie = $repository->count([]);
        $repository->add($categorie, true);
        $this->assertEquals($nbCategorie +1, $repository->count([]),"erreur sur testAddCategorie()");
    }
    
    public function testRemoveCategorie(){
        $repository=$this->recupRepository();
        $categorie =$this->newCategorie();
        $repository->add($categorie, true);
        $nbCategorie= $repository->count([]);
        $repository->remove($categorie,true);
        $this->assertEquals($nbCategorie -1, $repository->count([]),"erreur sur testRemoveCategorie");
    }
    
    public function testFindAllOrderByName() {
        $repository = $this->recupRepository();
        $nbCategorie = $repository->count([]);
        $categorie= $repository->findAllOrderByName('ASC');
        $nb2Categorie = count($categorie);
        $this->assertEquals($nbCategorie, $nb2Categorie, "ereur sur le test testFindAllOrderByName()");
    }
}
