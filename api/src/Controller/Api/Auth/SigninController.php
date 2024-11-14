<?php

namespace App\Controller\Api\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SigninController extends AbstractController
{
    #[Route('/api/auth/signin', methods: ['POST'])]
    public function signin(): Response
    {
        return new Response(
            '<html><body>Hello, World!</body></html>'
        );
    }
}