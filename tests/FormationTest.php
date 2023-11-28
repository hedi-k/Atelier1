<?php

namespace App\Tests;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

/**
 * Description of FormationTest
 *
 * @author hedi
 */
class FormationTest extends TestCase {
    
    /**
     * Test de la méthode getPublishedAtString()de Formation
     */
    public function testGetPublishedAtString(){
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2023-01-04 17:00:12"));
        $this->assertEquals("04/01/2023", $formation->getPublishedAtString());
    }
}
