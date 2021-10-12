<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function latest(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findBy([], ['id' => 'DESC']);

        return $this->render('movie/latest.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("/{id}", name="show", requirements={"id": "\d+"})
     */
    public function show($id): Response
    {
        return $this->render('movie/show.html.twig');
    }

    /**
     * @Route("/{imdbId}/import", requirements={"id": "tt\d+"})
     */
    public function import($imdbId, EntityManagerInterface $entityManager, OmdbApi $omdbApi): Response
    {
        $movieData = $omdbApi->requestOneById($imdbId);

        dump($movieData);
        $movie = Movie::fromApi($movieData);
        $entityManager->persist($movie);
        $entityManager->flush();

        return $this->redirectToRoute('movie_latest');
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
