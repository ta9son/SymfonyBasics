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
        $data = [
            array('name' => '山田太郎', 'age' => 34, 'mail' => 'taro@yamada'),
            array('name' => '田中花子', 'age' => 23, 'mail' => 'hanako@flower'),
            array('name' => '鈴木幸子', 'age' => 45, 'mail' => 'sachiko@happy'),
        ];

        return $this->render('hello/index.html.twig', [
            'title' => 'Hello',
            'data' => $data,
        ]);
    }
}
