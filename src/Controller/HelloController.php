<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Finder\Finder;

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
     * @Route("/find", name="find")
     */
    public function find(Request $request, EntityManagerInterface $em)
    {
        $formobj = new FindForm();
        $form = $this->createFormBuilder($formobj)
            ->add('find', TextType::class)
            ->add('save', SubmitType::class, array("label" => "Click"))
            ->getForm();

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            $findstr = $form->getData()->getFind();
            $repository = $em->getRepository(Person::class);
            $result = $repository->findRsults($findstr);
            // $result = $repository->findAllwithSort($findstr);
            // $result = $repository->findByNameOrMail($findstr);
            // $result = $repository->findByAgeBetween($findstr);
            // $result = $repository->findByAgeIn($findstr);
            // $result = $repository->findByNameAndSearch($findstr);
            // $result = $repository->findByAge($findstr);
            // $result = $repository->findByNameAimai($findstr);
        } else {
            $result = null;
        }

        return $this->render('/hello/find.html.twig', [
            'title' => 'Hello',
            'form' => $form->createView(),
            'data' => $result,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($request->getMethod() == 'POST') {
            $person = $form->getData();

            $errors = $validator->validate($person);

            if (count($errors) == 0) {
                $manager = $em->persist($person);
                $em->flush();
                return $this->redirect('/hello');
            } else {
                return $this->render('hello/create.html.twig', [
                    'title' => 'Hello',
                    'message' => 'ERROR!!',
                    'form' => $form->createView(),
                ]);
            }
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


    #[Route('/filelist', name: 'filelist')]
    public function filelist()
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__);

        return $this->render('hello/filelist.html.twig', [
            'title' => 'Hello',
            'message' => __DIR__,
            'finder' => $finder,
        ]);
    }

    #[Route('/dirlist', name: 'dirlist')]
    public function dirlist()
    {
        $finder = new Finder();
        $finder->directories()->in('../src/');

        return $this->render('hello/filelist.html.twig', [
            'title' => 'Hello',
            'message' => __DIR__,
            'finder' => $finder,
        ]);
    }

    // 複数箇所のFileを検索
    #[Route('/filelist2', name: 'filelist2')]
    public function filelist2()
    {
        $finder = new Finder();
        $finder->files()->in(['../src/Controller', '../src/Entity', '../src/Form', '../src/Repository']);

        return $this->render('hello/filelist.html.twig', [
            'title' => 'Hello',
            'message' => __DIR__,
            'finder' => $finder,
        ]);
    }
}

class FindForm
{
    private $find;


    public function getFind()
    {
        return $this->find;
    }
    public function setFind($find)
    {
        $this->find = $find;
    }
}
