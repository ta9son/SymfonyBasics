<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function index(Request $request)
    {

        $form = $this->createFormBuilder()->add('input', TextType::class)
            ->add('save', SubmitType::class, ['label' => '送信'])
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $msg = "こんにちは、" .  $form->get('input')->getData() . "さん！";
        } else {
            $msg = 'お名前を入力してください。';
        }

        return $this->render('hello/index.html.twig', [
            'title' => 'Hello',
            'msg' => $msg,
            'form' => $form->createView(),
        ]);
    }
}
