<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Vairogs\Extra\Constants\Status;
use Vairogs\Sitemap\Builder\Director;
use Vairogs\Sitemap\Builder\FileBuilder;
use Vairogs\Sitemap\Provider;
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

    public function __construct(private readonly ValidatorInterface $validator, ?Provider $provider = null, array $options = [])
    {
        if (null === $provider || (false === $options[Status::ENABLED])) {
            throw new NotFoundHttpException(message: 'To use vairogs/sitemap, you must enable it and provide a Provider');
        }

        $this->options = $options;
        $this->provider = $provider;
        parent::__construct(name: self::$defaultName);
    }

    protected function configure(): void
    {
        $host = $this->options[self::HOST] ?? null;
        $this->setDescription(description: 'Regenerate sitemap.xml')
            ->addArgument(name: self::HOST, mode: $host ? InputArgument::OPTIONAL : InputArgument::REQUIRED, description: 'host to use in sitemap', default: $this->options[self::HOST])
            ->addOption(name: 'filename', mode: InputOption::VALUE_OPTIONAL, description: 'sitemap filename if not sitemap.xml', default: 'sitemap.xml');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $sitemap = $this->provider->populate(host: $input->getArgument(name: self::HOST));
        $constraintViolationList = $this->validator->validate(value: $sitemap);

        if (0 !== $constraintViolationList->count()) {
            /** @var ConstraintViolation $error */
            foreach ($constraintViolationList as $error) {
                $output->writeln(messages: $error->getMessage());
            }
        } else {
            $output->writeln(messages: '<fg=blue>Generating sitemap</>');
            $filename = getcwd() . '/public/' . $input->getOption(name: 'filename');

            if (is_file(filename: $filename)) {
                unlink(filename: $filename);
            }

            $handle = fopen(filename: $filename, mode: 'w+b');

            try {
                (new Director(buffer: $handle))->build(builder: new FileBuilder(sitemap: $sitemap));
                $output->writeln(messages: sprintf('<info>Sitemap generated as "%s"</info>', $filename));
            } catch (Exception $exception) {
                if (is_file(filename: $filename)) {
                    unlink(filename: $filename);
                }

                $output->writeln(messages: '<error>' . $exception->getMessage() . '</error>');
            }

            fclose(stream: $handle);
        }
    }
}
