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
use function fclose;
use function fopen;
use function getcwd;
use function sprintf;
use function unlink;

class SitemapCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'vairogs:sitemap';
    private array $options;
    private ?Provider $provider;

    /**
     * @param ValidatorInterface $validator
     * @param Provider|null $provider
     * @param array $options
     */
    public function __construct(private ValidatorInterface $validator, ?Provider $provider = null, array $options = [])
    {
        if (null === $provider || (false === $options['enabled'])) {
            throw new NotFoundHttpException('To use vairogs/sitemap, you must enable it and provide a Provider');
        }
        $this->options = $options;
        $this->provider = $provider;
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $host = $this->options['host'] ?? null;
        $this->setDescription('Regenerate sitemap.xml')
            ->addArgument('host', $host ? InputArgument::OPTIONAL : InputArgument::REQUIRED, 'host to use in sitemap', $this->options['host'])
            ->addOption('filename', null, InputOption::VALUE_OPTIONAL, 'sitemap filename if not sitemap.xml', 'sitemap.xml');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $sitemap = $this->provider->populate($input->getArgument('host'));
        $constraintViolationList = $this->validator->validate($sitemap);
        if (0 !== $constraintViolationList->count()) {
            foreach ($constraintViolationList as $error) {
                /** @var ConstraintViolation $error */
                $output->writeln($error->getMessage());
            }
        } else {
            $output->writeln('<fg=blue>Generating sitemap</>');
            $filename = getcwd() . '/public/' . $input->getOption('filename');

            @unlink($filename);
            $handle = fopen($filename, 'w+b');
            try {
                (new Director($handle))->build(new FileBuilder($sitemap));
                $output->writeln(sprintf('<info>Sitemap generated as "%s"</info>', $filename));
            } catch (Exception $exception) {
                @unlink($filename);
                $output->writeln('<error>' . $exception->getMessage() . '</error>');
            }
            fclose($handle);
        }
    }
}
