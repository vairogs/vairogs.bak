<?php declare(strict_types = 1);

if (!file_exists(__DIR__ . '/src')) {
    exit(0);
}

return (new PhpCsFixer\Config())->setRules([
    '@PHP80Migration:risky' => true,
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
])
    ->setRiskyAllowed(true)
    ->setCacheFile('.php-cs-fixer.cache');
