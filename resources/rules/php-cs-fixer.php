<?php
$project_name = 'ReactiveLaravelJobs';
$config = require __DIR__.'/../../vendor/reyesoft/ci/php/rules/php-cs-fixer.dist.php';

// rules override
$rules = array_merge(
    $config->getRules(),
    [
        'return_assignment' => false,    // problem on ObjectsEloquentBuilder
        'final_internal_class' => false,
        'php_unit_test_case_static_method_calls' => ['call_type' => 'static'],
        // '@PHP74Migration:risky' => true,
        // '@PHP80Migration:risky' => true,
    ]
);

return $config
    ->setRules($rules)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([
                './src',
                // './config',
                './tests'
            ])
            ->notPath('./bootstrap/*.php')
    );
