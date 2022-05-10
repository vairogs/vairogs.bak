<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Command;

use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
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
use Vairogs\Sitemap\Model\Sitemap;
use Vairogs\Sitemap\Provider;
use function fclose;
use function fopen;
use function getcwd;
use function is_file;
use function is_resource;
use function sprintf;
use function unlink;

#[AsCommand(name: 'vairogs:sitemap', description: 'Regenerate sitemap.xml')]
class SitemapCommand extends Command
{
    private const HOST = 'host';
    private mixed $handle;

    public function __construct(private readonly ValidatorInterface $validator, private readonly ?Provider $provider = null, private readonly array $options = [])
    {
        if (null === $provider || (false === $options[Status::ENABLED])) {
            throw new NotFoundHttpException(message: 'To use vairogs/sitemap, you must enable it and pass a Provider');
        }

        parent::__construct();
    }

    public function __destruct()
    {
        if (is_resource(value: $this->handle)) {
            fclose(stream: $this->handle);
        }
    }

    protected function configure(): void
    {
        $this
            ->addArgument(name: self::HOST, mode: ($this->options[self::HOST] ?? null) ? InputArgument::OPTIONAL : InputArgument::REQUIRED, description: 'host to use in sitemap', default: $this->options[self::HOST])
            ->addOption(name: 'filename', mode: InputOption::VALUE_OPTIONAL, description: 'sitemap filename if not sitemap.xml', default: 'sitemap.xml');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sitemap = $this->provider->populate(host: $input->getArgument(name: self::HOST));

        if (!$this->validate(sitemap: $sitemap, output: $output)) {
            return 1;
        }

        $output->writeln(messages: '<fg=blue>Generating sitemap</>');
        $filename = getcwd() . '/public/' . $input->getOption(name: 'filename');

        $this->unlink(filename: $filename);

        $this->handle = fopen(filename: $filename, mode: 'w+b');

        try {
            (new Director(buffer: $this->handle))->build(builder: new FileBuilder(sitemap: $sitemap));
            $output->writeln(messages: sprintf('<info>Sitemap generated as "%s"</info>', $filename));
        } catch (Exception $exception) {
            $this->unlink(filename: $filename);

            $output->writeln(messages: '<error>' . $exception->getMessage() . '</error>');

            return 2;
        }

        return 0;
    }

    private function unlink(string $filename): void
    {
        if (is_file(filename: $filename)) {
            unlink(filename: $filename);
        }
    }

    private function validate(Sitemap $sitemap, OutputInterface $output): bool
    {
        if (0 !== ($violations = $this->validator->validate(value: $sitemap))->count()) {
            foreach ($violations as $error) {
                /* @var ConstraintViolation $error */
                $output->writeln(messages: $error->getMessage());
            }

            return false;
        }

        return true;
    }
}
