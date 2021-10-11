<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", defaults={"_format": "json"})
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/movie", name="api_movie")
     */
    public function index(): JsonResponse
    {
        $movies = [
            ['title' => 'Mission impossible 1'],
            ['title' => 'Mission impossible 2'],
            ['title' => 'Intouchable'],
        ];

        //throw new \InvalidArgument('DB error');

        //return new Response(json_encode($movies));
        //return new Response(json_encode($movies), 200, ['Content-Type' => 'application/json']);
        //return $this->json($movies);
        return new JsonResponse($movies);
    }
}
