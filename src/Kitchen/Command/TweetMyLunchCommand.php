<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

$console = new Application('Kitchen', 'n/a');
$console
    ->register('tweet-lunch')
    ->setDescription('Tweet my lunch for today')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $output->writeln('<info>Hold on... Tweeting now!</info>');

        $recipes = $app['recipe.repository']->getRecipesForLunch();
        $results = array();

        foreach ($recipes as $recipe) {
            $results[] = $recipe['title'];
            $output->writeln($recipe['title']);
        }

        $text = "We are having " . implode(", ", $results) . " for today's lunch!";
        $output->writeln($text);
        $app['twitter.api']->send($text);
    })
;

return $console;
