<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicController extends AbstractController
{
    protected ArticlesRepository $articlesRepository;

    /**
     * @param ArticlesRepository $articlesRepository
     */
    public function __construct(ArticlesRepository $articlesRepository)
    {
        $this->articlesRepository = $articlesRepository;
    }

    #[Route('/', name: 'acceuil')]
    public function index(): Response
    {
        $articles = $this->articlesRepository->findAll();

        return $this->render('public/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'article')]
    public function article($id): Response
    {
        $article = $this->articlesRepository->find($id);

        return $this->render('public/article.html.twig', [
            'article' => $article,
        ]);
    }
}
