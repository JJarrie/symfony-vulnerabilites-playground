<?php

return new PhpCsFixer\Config()
    ->setParallelConfig(\PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
    ])
    ->setFinder(new PhpCsFixer\Finder()
        ->in(__DIR__ . '/src')
        ->exclude('var')
    )
    ->setCacheFile('.php-cs-fixer.cache');
