#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$parser = new \App\Service\JsonParser(__DIR__.'/../source/matches');
$builder = new \App\Service\MatchBuilder();
$saver = new \App\Service\HtmlSaver(__DIR__.'/../templates', __DIR__.'/../public/result');

$matchPageBuilderCommand = new \App\Command\BuildMatchPageCommand($parser, $builder, $saver);

$application = new Application();
$application->add($matchPageBuilderCommand);
$application->run();