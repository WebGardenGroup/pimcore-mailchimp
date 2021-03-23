<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setUsingCache(false)
    ->setRules([
        '@Symfony' => true,
        'phpdoc_var_without_name' => false,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'normalize_index_brace' => false,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
    ])
    ->setFinder(
        Finder::create()
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->in(__DIR__.'/src')
    );
