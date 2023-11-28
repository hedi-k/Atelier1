<?php

namespace App\tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of FormationVlidationsTest
 *
 * @author hedi
 */
class FormationValidationsTest extends KernelTestCase {
    
    public function getFormation(): Formation{
        return(new Formation())
        ->setTitle("Titre de la formation");            
    }
    /**
     * Essai qu'une date ne doit pas être postérieure à la date du jour
     * (Doit rendre une erreur)
     */
    public function testValideDateFormationA(){
        $formation=$this->getFormation()->setPublishedAt((new \DateTime("2025-01-04")));
        $this->assertErrors($formation, 1);
    }
    /**
     * Essai une date antérieure  à la date du jour
     * (Doit rendre  une réussite)
     */
    public function testValideDateFormationB(){
        $formation=$this->getFormation()->setPublishedAt((new \DateTime("1900-01-04")));
        $this->assertErrors($formation, 0);
    }
    /**
     * Essai la date du jour
     * (Doit rendre  une réussite)
     */
     public function testValideDateFormationC(){
        $formation=$this->getFormation()->setPublishedAt((new \DateTime("now")));
        $this->assertErrors($formation, 0);
    }
    public function assertErrors(Formation $formation, int $nbErreursAttendues){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error);
    }
}
