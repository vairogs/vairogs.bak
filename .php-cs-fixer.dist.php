<?php declare(strict_types = 1);

if (!file_exists(filename: __DIR__ . '/src')) {
    exit;
}

return (new PhpCsFixer\Config())->setRules(rules: [
    '@PHP80Migration:risky' => true,
    '@PHP81Migration' => true,
    '@Symfony' => true,
    '@Symfony:risky' => true,
    '@PSR12' => true,
    '@PSR12:risky' => true,
    'protected_to_private' => false,
    'nullable_type_declaration_for_default_null_value' => ['use_nullable_type_declaration' => true],
    'declare_strict_types' => true,
    'linebreak_after_opening_tag' => false,
    'blank_line_after_opening_tag' => false,
    'binary_operator_spaces' => true,
    'concat_space' => ['spacing' => 'one'],
    'declare_equal_normalize' => ['space' => 'single'],
    'yoda_style' => true,
    'native_function_invocation' => false,
    'native_constant_invocation' => false,
    'fopen_flags' => [
        'b_mode' => true,
    ],
    'increment_style' => [
        'style' => 'post',
    ],
    'comment_to_phpdoc' => true,
    'types_spaces' => [
        'space' => 'none',
    ],
    'native_function_type_declaration_casing' => true,
    'magic_constant_casing' => true,
    'ordered_imports' => [
        'sort_algorithm' => 'alpha',
        'imports_order' => [
            'class',
            'function',
            'const',
        ],
    ],
    'multiline_whitespace_before_semicolons' => [
        'strategy' => 'no_multi_line',
    ],
    'method_chaining_indentation' => false,
    'return_assignment' => true,
    'ordered_class_elements' => [
        'sort_algorithm' => 'none',
    ],
    'simple_to_complex_string_variable' => true,
    'explicit_indirect_variable' => true,
    'no_superfluous_phpdoc_tags' => [
        'allow_mixed' => true,
        'remove_inheritdoc' => true,
    ],
    'no_useless_sprintf' => true,
    'global_namespace_import' => [
        'import_constants' => null,
        'import_functions' => true,
        'import_classes' => true,
    ],
])
    ->setRiskyAllowed(isRiskyAllowed: true)
    ->setCacheFile(cacheFile: '.php-cs-fixer.cache');
