<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Vairogs\Component\Sitemap\Builder\Director;
use Vairogs\Component\Sitemap\Builder\FileBuilder;
use Vairogs\Component\Sitemap\Provider;
use Vairogs\Component\Utils\DependencyInjection\Dependency;
use function fclose;
use function fopen;
use function getcwd;
use function is_file;
use function sprintf;
use function unlink;

class SitemapCommand extends Command
{
    private const HOST = 'host';

    protected static $defaultName = 'vairogs:sitemap';
    private array $options;
    private Provider $provider;

    public function __construct(private ValidatorInterface $validator, ?Provider $provider = null, array $options = [])
    {
        if (null === $provider || (false === $options[Dependency::ENABLED])) {
            throw new NotFoundHttpException('To use vairogs/sitemap, you must enable it and provide a Provider');
        }

        $this->options = $options;
        $this->provider = $provider;
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $host = $this->options[self::HOST] ?? null;
        $this->setDescription('Regenerate sitemap.xml')
            ->addArgument(self::HOST, $host ? InputArgument::OPTIONAL : InputArgument::REQUIRED, 'host to use in sitemap', $this->options[self::HOST])
            ->addOption('filename', null, InputOption::VALUE_OPTIONAL, 'sitemap filename if not sitemap.xml', 'sitemap.xml');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $sitemap = $this->provider->populate($input->getArgument(self::HOST));
        $constraintViolationList = $this->validator->validate($sitemap);

        if (0 !== $constraintViolationList->count()) {
            /** @var ConstraintViolation $error */
            foreach ($constraintViolationList as $error) {
                $output->writeln($error->getMessage());
            }
        } else {
            $output->writeln('<fg=blue>Generating sitemap</>');
            $filename = getcwd() . '/public/' . $input->getOption('filename');

            if (is_file($filename)) {
                unlink($filename);
            }

            $handle = fopen($filename, 'w+b');

            try {
                (new Director($handle))->build(new FileBuilder($sitemap));
                $output->writeln(sprintf('<info>Sitemap generated as "%s"</info>', $filename));
            } catch (Exception $exception) {
                if (is_file($filename)) {
                    unlink($filename);
                }

                $output->writeln('<error>' . $exception->getMessage() . '</error>');
            }

            fclose($handle);
        }
    }
}
