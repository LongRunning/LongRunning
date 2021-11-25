<?php

$finder = PhpCsFixer\Finder::create()
    ->in('packages')
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@PhpCsFixer' => true,
        'array_syntax' => [
            'syntax' => 'short'
        ],
        'list_syntax' => [
            'syntax' => 'short'
        ],
        'method_chaining_indentation' => false,
        'multiline_whitespace_before_semicolons' => false,
        'php_unit_test_class_requires_covers' => false,
        'php_unit_internal_class' => false,
        'php_unit_method_casing' => [
            'case' => 'snake_case'
        ],
    ])
    ->setFinder($finder);
