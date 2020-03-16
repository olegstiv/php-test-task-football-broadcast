<?php

namespace App\Command;

use App\Service\HtmlSaver;
use App\Service\JsonParser;
use App\Service\MatchBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class BuildMatchPageCommand extends Command
{
    protected static $defaultName = 'app:build-match-page';

    private JsonParser $jsonParser;
    private MatchBuilder $matchBuilder;
    private HtmlSaver $htmlSaver;
    private InputInterface $input;
    private OutputInterface $output;
    private SymfonyStyle $io;

    public function __construct(JsonParser $jsonParser, MatchBuilder $matchBuilder, HtmlSaver $htmlSaver)
    {
        parent::__construct(null);

        $this->jsonParser = $jsonParser;
        $this->matchBuilder = $matchBuilder;
        $this->htmlSaver = $htmlSaver;
    }

    protected function configure()
    {
        $this->setDescription('Parse match json log and generate user friendly html match broadcast page.');
        $this->addArgument('id', InputArgument::OPTIONAL, 'Match Id');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->input = $input;
        $this->output = $output;
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $matchId = $this->extractMatchId();
        $logs = $this->jsonParser->parse($matchId);
        $match = $this->matchBuilder->build($matchId, $logs);
        $resultPath = $this->htmlSaver->save($match);

        $this->io->success('Match saved to: '.$resultPath);

        return 0;
    }

    private function extractMatchId(): string
    {
        $matchId = $this->input->getArgument('id');
        if($matchId) {
            return $matchId;
        }

        return $this->askMatchId();
    }

    private function askMatchId(): string
    {
        $helper = $this->getHelper('question');
        $question = new Question('Please set match id: ');

        $matchId = $helper->ask($this->input, $this->output, $question);

        if(!$matchId) {
            return $this->askMatchId();
        }

        return $matchId;
    }
}