<?php

namespace App\Command;

use App\Entity\Comments;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'addComments',
    description: 'Ajouter un commentaire sur un article',
)]
class CreateCommentsCommand extends Command
{
    private EntityManagerInterface $entityManager;
    protected ArticlesRepository $articlesRepository;


    public function __construct(EntityManagerInterface $entityManager, ArticlesRepository $articlesRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->articlesRepository = $articlesRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('nbComment', InputArgument::OPTIONAL, 'Nombre de commentaire')
            ->addArgument('idArticle', InputArgument::OPTIONAL, 'Id de l\'article')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $nbComment = $input->getArgument('nbComment');
        $idArticle = $input->getArgument('idArticle');
        $article = $this->articlesRepository->find($idArticle);

        if ($nbComment < 1){
            return Command::FAILURE;
        }
        if(!$article){
            $io-> error("Cet article n'existe pas !!!");
            return Command::FAILURE;
        }else{
            for ($compteur = 0; $compteur < $nbComment; $compteur++){
                $io->comment('Creation de commentaires ' . $compteur);
                $comments = new Comments();
                $comments->setTitle("Commentaire numéro : " . $compteur);
                $comments->setDate(new \DateTime());
                $comments->setArticles($article);
                $this->entityManager->persist($comments);
            }
        }

        $this->entityManager->flush();
        $io->success(' Les commentaire ont été ajouté à l\'article ' . $idArticle . ' !!!' );

        return Command::SUCCESS;
    }
}
