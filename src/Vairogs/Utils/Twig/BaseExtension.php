<?php declare(strict_types = 1);

namespace Vairogs\Utils\Twig;

use Exception;
use ReflectionClass;
use Twig;
use Twig\Extension\AbstractExtension;
use Vairogs\Utils\Helper\Php;
use Vairogs\Utils\Helper\Text;
use Vairogs\Utils\Vairogs;
use function get_class_vars;
use function sprintf;
use function str_starts_with;
use function strtolower;

abstract class BaseExtension extends AbstractExtension
{
    use TwigTrait;

    final public const HELPER_NAMESPACE = 'Vairogs\Utils\Helper';

    protected static string $class;
    protected static string $key = '';
    protected static ?string $prefix = null;

    protected array $vars;

    public function __construct()
    {
        $this->vars = get_class_vars(class: static::class);
    }

    public function getFilters(): array
    {
        return $this->getMethods(filter: Attribute\TwigFilter::class, class: Twig\TwigFilter::class);
    }

    public function getFunctions(): array
    {
        return $this->getMethods(filter: Attribute\TwigFunction::class, class: Twig\TwigFunction::class);
    }

    private function getMethods(string $filter, string $class): array
    {
        return $this->makeArray(input: Helper::getFiltered($this->vars['class'], $filter), key: $this->getPrefix(), class: $class);
    }

    private function getPrefix(): string
    {
        $base = $this->vars['prefix'] ?? $this->vars['class'];

        try {
            $ns = (new ReflectionClass(objectOrClass: $this->vars['class']))->getNamespaceName();
        } catch (Exception) {
            $ns = '';
        }

        $short = Php::getShortName(class: $this->vars['class']);

        if (self::HELPER_NAMESPACE === $ns) {
            $base = sprintf('%s_%s', 'helper', $short);
        } elseif ('Extension' === $short) {
            $base = Text::getLastPart(string: $ns, delimiter: '\\');
        }

        if (str_starts_with(haystack: $ns, needle: Php::getShortName(class: Vairogs::class))) {
            $this->vars['key'] = Vairogs::VAIROGS;
        }

        if ('' !== $this->vars['key']) {
            $base = sprintf('%s_%s', $this->vars['key'], $base);
        }

        return strtolower(string: $base);
    }
}
