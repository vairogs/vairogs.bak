<?php declare(strict_types = 1);

namespace Vairogs\Utils\Locator;

use PhpParser\Lexer;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Parser\Php7;

use function array_values;
use function in_array;

class Reader
{
    private array $names = [];

    public function __construct(string $snippet, string $namespace, private array $types = [])
    {
        if ([] === $this->types) {
            $this->types = [Class_::class, Interface_::class, Trait_::class];
        }

        $this->findDefinitions(stmts: (new Php7(lexer: new Lexer(options: ['usedAttributes' => []])))->parse(code: $snippet) ?? [], namespace: new Name(name: $namespace));
    }

    public function getDefinitionNames(): array
    {
        return array_values(array: $this->names);
    }

    private function findDefinitions(array $stmts, Name $namespace): void
    {
        foreach ($stmts as $stmt) {
            if ($stmt instanceof Namespace_) {
                $this->findDefinitions(stmts: $stmt->stmts, namespace: new Name(name: (string) $stmt->name));
                continue;
            }

            if (in_array(needle: $stmt::class, haystack: $this->types, strict: true)) {
                $defName = new Name(name: "$namespace\\$stmt->name");
                $this->names[$defName->key()] = $defName->normalize();
            }
        }
    }
}
