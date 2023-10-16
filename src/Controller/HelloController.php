<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpFoudation\Response;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function index(Request $request)
    {
        return $this->render('hello/index.html.twig', [
            'title' => 'Hello',
            'message' => 'テンプレートで計算を行います。',
        ]);
    }
}
