<?php

namespace App\Command;

use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'addArticles',
    description: 'Ajouter un article',
)]
class CreateArticlesCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('nbArticles', InputArgument::REQUIRED, 'Nombre d\'article')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $nbArticles = $input->getArgument('nbArticles');

        //$io->warning('Erreur au niveau du nombre d\'article ' . $nbArticles);

        if ($nbArticles < 1){
            return Command::FAILURE;
        }

        for ($compteur = 0; $compteur < $nbArticles; $compteur++){
            $io->comment('Creation article ' . $compteur);
            $article = new Articles();
            $article->setTitle("Article numéro : " . $compteur);
            $article->setDescription("Article Description");
            $article->setDate(new \DateTime());
            $this->entityManager->persist($article);
        }

        $this->entityManager->flush();
        $io->success($compteur . ' articles crées !!!' );

        return Command::SUCCESS;
    }
}
