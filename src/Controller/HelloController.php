<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function index(Request $request)
    {
        $encoders = array(new XmlEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);


        $data = array(
            'name' => array(
                'first' => 'Taro',
                'second' => 'Yamada'
            ),
            'age' => 36,
            'mail' => 'taro@yamada.kun'
        );


        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        $result = $serializer->serialize($data, 'xml');
        $response->setContent($result);
        return $response;
    }

    /**
     * @Route("/other/{domain}", name="other")
     */
    public function other(Request $request, $domain = "")
    {
        if ($domain == "") {
            return $this->redirect('/hello');
        } else {
            return new RedirectResponse("http://{$domain}.com");
        }
    }
}
