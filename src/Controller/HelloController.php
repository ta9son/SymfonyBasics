<?php

namespace App\Controller;

use App\Entity\Person;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
        // Person tableから全てのデータを取得
        $repository = $em->getRepository(Person::class);
        $data = $repository->findAll();
        return $this->render('hello/index.html.twig', [
            'title' => 'Hello',
            'data' => $data,
        ]);
    }

    /**
     * @Route("/find/{id}", name="find")
     */
    public function find(Request $request, Person $person)
    {
        if ($person === null) {
            // レコードが見つからない場合
            return $this->render('hello/find.html.twig', [
                'title' => 'Error',
                'data' => 'レコードが見つかりません。',
            ]);
        }

        return $this->render('hello/find.html.twig', [
            'title' => 'hello',
            'data' => $person,
        ]);
    }
}
