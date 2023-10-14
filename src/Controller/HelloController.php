<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function index(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $input = $request->request->get('input');
            $msg = "こんにちは、" .  $input . "さん！";
        } else {
            $msg = 'お名前を入力してください。';
        }

        return $this->render('hello/index.html.twig', [
            'title' => 'Hello',
            'msg' => $msg,
        ]);
    }
}
