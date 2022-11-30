<?php
return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'yoda_style' => false,
        'native_function_invocation' => ['include' => ['@all']],
        'phpdoc_no_package' => true,
        'no_empty_phpdoc' => true,
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'class_attributes_separation' => ['elements' => ['property' => 'only_if_meta', 'const' => 'only_if_meta']],
    ])
    ->setRiskyAllowed(true);
