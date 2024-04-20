<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function article($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Comments();

        $article = $this->articlesRepository->find($id);
        $form = $this->createForm(CommentsType::class, $commentaire)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commentaire->setTitle($form->get('title')->getData());
            $commentaire->setDate(new \DateTime());
            $commentaire->setArticles($article);

            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->render('public/article.html.twig', [
                'article' => $article,
                'form' => $form,
            ]);
        }

        return $this->render('public/article.html.twig', [
            'article' => $article,
                'form' => $form,
        ]);
    }

}
