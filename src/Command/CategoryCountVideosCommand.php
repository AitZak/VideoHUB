<?php

namespace App\Command;

use App\Manager\CategoryManager;
use App\Manager\VideoManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CategoryCountVideosCommand extends Command
{
    protected static $defaultName = 'app:category-count-videos';
    private $videoManager;
    private $categoryManager;

    public function __construct(VideoManager $videoManager, CategoryManager $categoryManager)
    {
        $this->videoManager = $videoManager;
        $this->categoryManager = $categoryManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Gives you number of videos by category.')
            ->addArgument('title', InputArgument::REQUIRED, 'title of category')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $title = $input->getArgument('title');
        $category = $this->categoryManager->getCategoryByTitle($title);

        if ($category){
            $counter = $this->videoManager->countVideosByCategory($category->getId());
            $io->success(sprintf('Il y a %d videos pour la catégorie %s', $counter, $category->getTitle()));
        } else {
            $io->error(sprintf('La catégorie %s n\'existe pas.',$title));
        }
    }
}
