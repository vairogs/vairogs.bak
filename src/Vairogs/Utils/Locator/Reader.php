<?php declare(strict_types = 1);

namespace Vairogs\Utils\Locator;

use PhpParser\Lexer;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Parser;
use RuntimeException;

class Reader
{
    private array $defs = [];
    private array $names = [];
    private array $global;

    public function __construct(string $snippet, string $namespace, ?Parser $parser = null)
    {
        if (null === $parser) {
            $parser = new Parser\Php7(lexer: new Lexer(options: ['usedAttributes' => []]));
        }

        $this->global = $parser->parse(code: $snippet);

        $this->findDefinitions(stmts: $this->global, namespace: new Name(name: $namespace));
    }

    public function getDefinitionNames(): array
    {
        return array_values(array: $this->names);
    }

    public function hasDefinition(string $name): bool
    {
        return isset($this->defs[(new Name(name: $name))->keyize()]);
    }

    public function read(string $name): array
    {
        if (!$this->hasDefinition(name: $name)) {
            throw new RuntimeException(message: "Unable to read <$name>, not found.");
        }

        return [$this->defs[(new Name(name: $name))->keyize()]];
    }

    public function readAll(): array
    {
        return $this->global;
    }

    private function findDefinitions(array $stmts, Name $namespace): void
    {
        $useStmts = [];

        foreach ($stmts as $stmt) {
            if ($stmt instanceof Namespace_) {
                $this->findDefinitions($stmt->stmts, new Name(name: (string) $stmt->name));
            } elseif ($stmt instanceof Use_) {
                $useStmts[] = $stmt;
            } elseif ($stmt instanceof Class_ || $stmt instanceof Interface_ || $stmt instanceof Trait_) {
                $defName = new Name(name: "$namespace\\$stmt->name");
                $this->names[$defName->keyize()] = $defName->normalize();
                $this->defs[$defName->keyize()] = new Namespace_(name: $namespace->normalize() ? $namespace->createNode() : null, stmts: $useStmts, );
                $this->defs[$defName->keyize()]->stmts[] = $stmt;
            }
        }
    }
}
