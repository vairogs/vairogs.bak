<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Twig;

use ReflectionClass;
use ReflectionException;
use Twig;
use Twig\Extension\AbstractExtension;
use Vairogs\Component\Utils\Helper\Php;
use Vairogs\Component\Utils\Helper\Text;
use Vairogs\Component\Utils\Twig\Annotation;
use Vairogs\Component\Utils\Vairogs;
use function get_class_vars;
use function sprintf;
use function str_starts_with;
use function strtolower;

abstract class BaseExtension extends AbstractExtension
{
    use TwigTrait;

    protected static string $class;
    protected static string $key = '';
    protected static string $suffix = '';

    protected array $vars;

    public function __construct()
    {
        $this->vars = get_class_vars(static::class);
    }

    /**
     * @throws ReflectionException
     */
    public function getFilters(): array
    {
        return $this->getMethods(Annotation\TwigFilter::class, Twig\TwigFilter::class);
    }

    /**
     * @throws ReflectionException
     */
    private function getMethods(string $filter, string $class): array
    {
        return $this->makeArray(Helper::getFiltered($this->vars['class'], $filter), $this->getSuffix(), $class);
    }

    /**
     * @throws ReflectionException
     */
    private function getSuffix(): string
    {
        $suffix = '' !== $this->vars['suffix'] ? $this->vars['suffix'] : $this->vars['class'];
        $ns = (new ReflectionClass($this->vars['class']))->getNamespaceName();
        $short = Php::getShortName($this->vars['class']);

        if (Vairogs::HELPER_NAMESPACE === $ns) {
            $suffix = sprintf('%s_%s', 'helper', $short);
        } elseif ('Extension' === $short) {
            $suffix = Text::getLastPart($ns, '\\');
        }

        if (str_starts_with($ns, Php::getShortName(Vairogs::class))) {
            $this->vars['key'] = Vairogs::VAIROGS;
        }

        if ('' !== $this->vars['key']) {
            $suffix = sprintf('%s_%s', $this->vars['key'], $suffix);
        }

        return strtolower($suffix);
    }

    /**
     * @throws ReflectionException
     */
    public function getFunctions(): array
    {
        return $this->getMethods(Annotation\TwigFunction::class, Twig\TwigFunction::class);
    }
}
