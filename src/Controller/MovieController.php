<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\OmdbApi;

/**
 * @Route("/movie", name="movie_")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/latest", name="latest")
     */
    public function latest(): Response
    {
        return $this->render('movie/latest.html.twig');
    }

    /**
     * @Route("/{id}", name="show", requirements={"id": "\d+"})
     */
    public function show($id): Response
    {
        return $this->render('movie/show.html.twig');
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(OmdbApi $omdbApi, Request $request): Response
    {
        $keyword = $request->query->get('keyword', 'Harry Potter');
        $movies = $omdbApi->requestAllBySearch($keyword);
        dump($movies);

        return $this->render('movie/search.html.twig', [
            'movies' => $movies['Search'],
            'keyword' => $keyword,
        ]);
    }
}
