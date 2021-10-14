<?php

namespace App\Command;

use App\OmdbApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImdbCommand extends Command
{
    protected static $defaultName = 'app:imdb';
    protected static $defaultDescription = 'Permet de chercher un film sur IMDB';
    private $omdbApi;

    public function __construct(OmdbApi $omdbApi)
    {
        parent::__construct();

        $this->omdbApi = $omdbApi;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('keyword', InputArgument::OPTIONAL, 'Mot-clé qui servira pour la recherche IMDB')
            ->addOption('movie', null, InputOption::VALUE_IS_ARRAY|InputOption::VALUE_OPTIONAL, '', [])
            ->setHelp('
    $ bin/console app:imdb "Harry Potter"
    $ bin/console app:imdb
            ');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        dump($input->getOption('movie'));
        $keyword = $input->getArgument('keyword');
        if (!$keyword) {
            $keyword = $io->ask('Veuillez saisir un mot-clé ?', 'Harry Potter', function($answer) {
                $answer = strtolower($answer);
                $blackList = ['shit', 'hassle', 'connard'];

                foreach ($blackList as $word) {
                    if (false !== strpos($answer, $word)) {
                        throw new \InvalidArgumentException('Veuillez reformuler votre recherche');
                    }
                }

                return $answer;
            });
        }

        $movies = $this->omdbApi->requestAllBySearch($keyword);
        //dump($movies);

        $io->success(sprintf('Nous avons trouvé %s films correspondant à la recherche "%s"', $movies['totalResults'], $keyword));

        $rows = [];
        $io->progressStart(count($movies['Search']));
        foreach ($movies['Search'] as $movie) {
            $io->progressAdvance(1);
            usleep(100000);
            $rows[] = [$movie['Title'], $movie['Year'], 'https://www.imdb.com/title/'.$movie['imdbID'].'/', $movie['Type']];
        }

        $command = $this->getApplication()->find('debug:autowiring');


        $output->write("\r");
        $io->table(['TITRE', 'SORTIE', 'URL', 'TYPE'], $rows);

        return Command::SUCCESS;
    }
}