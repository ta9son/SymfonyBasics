<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function index(Request $request)
    {
        // $name = $request->get('name');
        // $pass = $request->get('pass');
        $result = '<html><body>';
        $result .= '<h1>クエリパラメーターを表示</h1>';
        // $result .= '<p>name: ' . $name . '</p>';
        // $result .= '<p>pass: ' . $pass . '</p>';
        $result .= '</body></html>';

        return new Response($result);
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