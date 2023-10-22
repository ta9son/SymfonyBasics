<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Componet\Form\Extension\Core\Type\IntegerType;
use Symfony\Componet\Form\Extension\Core\Type\SubmitType;

use App\Form\PersonType;
use App\Form\MessageType;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\Validator\Constraints as Assert;



class MessageController extends AbstractController
{
    #[Route('/message', name: 'message')]
    public function index(EntityManagerInterface $em): Response
    {
        $repository = $em->getRepository(Message::class);
        $data = $repository->findAll();
        return $this->render('message/index.html.twig', [
            'title' => 'Message',
            'data' => $data,
        ]);
    }

    #[Route('/message/create', name: 'message/create')]
    public function create(Request $request, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($request->getMethod() == 'POST') {
            $message = $form->getData();
            $errors = $validator->validate($message);
            if (count($errors) == 0) {
                $em->persist($message);
                $em->flush();
                return $this->redirect('/message');
            } else {
                $msg = 'エラーがあります。再入力してください。';
            }
        } else {
            $msg = 'フォームを入力してください。';
        }

        return $this->render('message/create.html.twig', [
            'title' => 'Message Create',
            'message' => $msg,
            'form' => $form->createView(),
        ]);
    }
}
