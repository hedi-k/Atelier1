<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Classe nécessaire au fonctionnement de keyCloak.
 */
class OAuthController extends AbstractController
{
    /**
     * Méthode appelée au moment de la demande de connexion.
     * @Route("/oauth/login", name="oauth_login")
     */
    public function index(ClientRegistry $clientRegistry):RedirectResponse 
    {
       return $clientRegistry->getClient('keycloak')->redirect();
    }
    
    /**
     * Méthode qui prend en charge la route de redirection du retour
     * @Route("/oauth/callback", name="oauth_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry){
        
    }
    
    /**
     * Méthode appelée pour la déconnexion.
     * @Route("logout",name="logout")
     */
    public function logout(){
        
    }
}
