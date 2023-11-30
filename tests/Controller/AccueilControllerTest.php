<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AccueilControllerTest
 *
 * @author hedi
 */
class AccueilControllerTest extends WebTestCase {

    /**
     * Contrôle que la page d'accueil est accèssible.
     */
    public function testAccesAccueil() {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * Contrôle que la page formation est accèssible.
     */
    public function testAccesFormation() {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    /**
     * Contrôle la page de playlist
     */
    
    public function testAccesPagePlaylist() {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * contrôle les tris de formations
     */
    public function testTriFormation(){
        $client = static::createClient();
        $client->request('GET','/formations/tri/title/ASC');
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1)');
        
        $client->request('GET','/formations/tri/title/DESC');
        $this->assertSelectorTextContains('h5', 'UML : Diagramme de cas');
        
        $client->request('GET','/formations/tri/name/ASC/playlist');
        $this->assertSelectorTextContains('h5', 'Bases de la programmation n°74');
        
        $client->request('GET','/formations/tri/name/DESC/playlist');
        $this->assertSelectorTextContains('h5', 'C# : ListBox en couleur');
    }
    
    /**
     * Contrôle le filtre de formations
     */
     public function testFiltreFormation() {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Eclipse n°7 : Tests unitaires'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Eclipse n°7 : Tests unitaires');
    }
    
    /**
     * Contrôle le clic sur un lien.
     */
    public function testLinkFormation() {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $crawler = $client->getCrawler();
        $link = $crawler->selectLink('image de formation')->link();
        $client->click($link);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Contôle les tris de playlists
     */
    public function testTriPaylists() {
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');

        $client->request('GET', '/playlists/tri/name/DESC');
        $this->assertSelectorTextContains('h5', 'Visual Studio 2019 et C#');

        $client->request('GET', '/playlists/tri/nombreDeFormation/ASC');
        $this->assertSelectorTextContains('h5', 'Cours Informatique embarquée');

        $client->request('GET', '/playlists/tri/nombreDeFormation/DESC');
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }

    /**
     * Contôle le filtre de playlists
     */
    public function testFiltrePlaylist() {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Cours UML'
        ]);
        //2 il apparait deux fois dans cette recherche!
        $this->assertCount(2, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Cours UML');
    }

    /**
     * Contrôle le click sur un lien.
     */
    public function testLinkPlylist() {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $crawler = $client->getCrawler();
        $link = $crawler->selectLink('Voir détail')->link();
        $client->click($link);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    
}
