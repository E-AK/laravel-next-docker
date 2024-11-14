<?php

namespace App\Controller\Api\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SignupController extends AbstractController
{
    #[Route('/api/auth/signup', methods: ['POST'])]
    public function signup(): Response
    {
        
    }
}