<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;




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

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($request->getMethod() == 'POST') {
            $person = $form->getData();
            $em->persist($person);
            $em->flush();
            return $this->redirect('/hello');
        } else {
            return $this->render('hello/create.html.twig', [
                'title' => 'Hello',
                'message' => 'Create Entity',
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, EntityManagerInterface $em, Person $person)
    {
        $form = $this->createFormBuilder($person)
            ->add('name', TextType::class)
            ->add('mail', TextType::class)
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'update'))
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $person = $form->getData();
            $em->flush();
            return $this->redirect('/hello');
        } else {
            return $this->render('hello/create.html.twig', [
                'title' => 'Hello',
                'message' => 'Update Entity id' . $person->getId(),
                'form' => $form->createView(),
            ]);
        }
    }


    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, Person $person, EntityManagerInterface $em)
    {
        $form = $this->createFormBuilder($person)
            ->add('name', TextType::class)
            ->add('mail', TextType::class)
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Delete'))
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $person = $form->getData();
            $em->remove($person);
            $em->flush();
            return $this->redirect('/hello');
        } else {
            return $this->render('hello/create.html.twig', [
                'title' => 'Hello',
                'message' => 'Delete Entity id' . $person->getId(),
                'form' => $form->createView(),
            ]);
        }
    }
}
