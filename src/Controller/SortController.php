<?php

namespace App\Controller;

use App\Facade\SortFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortController extends AbstractController
{
    /**
     * @Route("/sort", name="home")
     */
    public function index(): Response
    {
        return $this->render('sort/index.html.twig', [
            'controller_name' => 'SortController',
        ]);
    }

    /**
     * @Route("/sort-submit", name="sort_submit")
     */
    public function sortSubmit(Request $request, SortFacade $sortSubmitService): Response
    {
        $file = $request->files->get('input_csv');

        $sortSubmitService->proceedFile($file);

        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);
        return $response;
    }
}
