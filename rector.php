<?php declare(strict_types = 1);

use Rector\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector;
use Rector\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector;
use Rector\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\Identical\GetClassToInstanceOfRector;
use Rector\CodeQuality\Rector\If_\ConsecutiveNullCompareReturnsToNullCoalesceQueueRector;
use Rector\CodeQuality\Rector\If_\ShortenElseIfRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php73\Rector\FuncCall\ArrayKeyFirstLastRector;
use Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\PostRector\Rector\NameImportingPostRector;
use Rector\Set\ValueObject\SetList;
use Rector\Strict\Rector\AbstractFalsyScalarRuleFixerRector;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\Symfony\Set\SymfonySetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayParamDocTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByParentCallTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector;
use Rector\TypeDeclaration\Rector\Closure\AddClosureReturnTypeRector;
use Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Param\ParamTypeFromStrictTypedPropertyRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictGetterMethodReturnTypeRector;
use Rector\Visibility\Rector\ClassMethod\ExplicitPublicClassMethodRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths(paths: [
        __DIR__ . '/.php-cs-fixer.php',
        __DIR__ . '/rector.php',
        __DIR__ . '/src',
        __DIR__ . '/tests/assets',
        __DIR__ . '/tests/config',
        __DIR__ . '/tests/entity',
        __DIR__ . '/tests/src',
    ]);

    $rectorConfig->phpVersion(phpVersion: PhpVersion::PHP_82);

    $rectorConfig->rule(rectorClass: AddClosureReturnTypeRector::class);
    $rectorConfig->rule(rectorClass: AddVoidReturnTypeWhereNoReturnRector::class);
    $rectorConfig->rule(rectorClass: ArrayKeyFirstLastRector::class);
    $rectorConfig->rule(rectorClass: ConsecutiveNullCompareReturnsToNullCoalesceQueueRector::class);
    $rectorConfig->rule(rectorClass: ExplicitPublicClassMethodRector::class);
    $rectorConfig->rule(rectorClass: FlipTypeControlToUseExclusiveTypeRector::class);
    $rectorConfig->rule(rectorClass: ForRepeatedCountToOwnVariableRector::class);
    $rectorConfig->rule(rectorClass: ForeachItemsAssignToEmptyArrayToAssignRector::class);
    $rectorConfig->rule(rectorClass: GetClassToInstanceOfRector::class);
    $rectorConfig->rule(rectorClass: InlineArrayReturnAssignRector::class);
    $rectorConfig->rule(rectorClass: NameImportingPostRector::class);
    $rectorConfig->rule(rectorClass: ParamTypeByMethodCallTypeRector::class);
    $rectorConfig->rule(rectorClass: ParamTypeByParentCallTypeRector::class);
    $rectorConfig->rule(rectorClass: ParamTypeFromStrictTypedPropertyRector::class);
    $rectorConfig->rule(rectorClass: ReturnTypeDeclarationRector::class);
    $rectorConfig->rule(rectorClass: ReturnTypeFromStrictTypedPropertyRector::class);
    $rectorConfig->rule(rectorClass: ShortenElseIfRector::class);
    $rectorConfig->rule(rectorClass: TypedPropertyFromStrictConstructorRector::class);
    $rectorConfig->rule(rectorClass: TypedPropertyFromStrictGetterMethodReturnTypeRector::class);

    $rectorConfig->sets(sets: [
        SetList::DEAD_CODE,
        SetList::PHP_74,
        SetList::PHP_80,
        SetList::PHP_81,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        SymfonySetList::SYMFONY_60,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_STRICT,
    ]);

    $rectorConfig->importNames();

    $rectorConfig->ruleWithConfiguration(rectorClass: DisallowedEmptyRuleFixerRector::class, configuration: [
        AbstractFalsyScalarRuleFixerRector::TREAT_AS_NON_EMPTY => false,
    ]);

    $rectorConfig->skip(criteria: [
        AddArrayParamDocTypeRector::class,
        AddArrayReturnDocTypeRector::class,
        AddLiteralSeparatorToNumberRector::class,
        FirstClassCallableRector::class,
        __DIR__ . '/tests/config/bundles.php',
    ]);
};
