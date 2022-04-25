<?php declare(strict_types = 1);

if (!file_exists(filename: __DIR__ . '/src')) {
    exit;
}

return (new PhpCsFixer\Config())
    ->setRules(rules: [
        '@PHP80Migration:risky' => true,
        '@PHP81Migration' => true,
        '@PSR12' => true,
        '@PSR12:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'binary_operator_spaces' => true,
        'blank_line_after_opening_tag' => false,
        'comment_to_phpdoc' => true,
        'concat_space' => ['spacing' => 'one'],
        'date_time_create_from_format_call' => true,
        'declare_equal_normalize' => ['space' => 'single'],
        'declare_strict_types' => true,
        'explicit_indirect_variable' => true,
        'fopen_flags' => [ 'b_mode' => true,],
        'global_namespace_import' => ['import_constants' => null, 'import_functions' => true, 'import_classes' => true,],
        'increment_style' => ['style' => 'post',],
        'linebreak_after_opening_tag' => false,
        'magic_constant_casing' => true,
        'method_chaining_indentation' => false,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line',],
        'native_constant_invocation' => false,
        'native_function_invocation' => false,
        'native_function_type_declaration_casing' => true,
        'no_superfluous_phpdoc_tags' => ['allow_mixed' => true, 'remove_inheritdoc' => true,],
        'no_useless_sprintf' => true,
        'nullable_type_declaration_for_default_null_value' => ['use_nullable_type_declaration' => true],
        'ordered_class_elements' => ['sort_algorithm' => 'none',],
        'ordered_imports' => ['sort_algorithm' => 'alpha', 'imports_order' => ['class', 'function', 'const',],],
        'protected_to_private' => false,
        'return_assignment' => true,
        'simple_to_complex_string_variable' => true,
        'types_spaces' => ['space' => 'none',],
        'yoda_style' => true,
    ])
    ->setRiskyAllowed(isRiskyAllowed: true)
    ->setCacheFile(cacheFile: '.php-cs-fixer.cache');