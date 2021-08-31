<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Monolog\Handler\AbstractProcessingHandler;
use Vairogs\Component\Utils\Doctrine\Model\Factory\LogArrayFactory;

class MonologDBHandler extends AbstractProcessingHandler
{
    public function __construct(protected EntityManagerInterface $em, protected ManagerRegistry $managerRegistry, protected string $logClass)
    {
        parent::__construct();
    }

    public function handleBatch(array $records): void
    {
        $this->em->beginTransaction();

        foreach ($records as $record) {
            $this->handle($record);
        }

        $this->em->commit();
    }

    protected function write(array $record): void
    {
        $entry = LogArrayFactory::create($record, $this->logClass);

        if (!$this->em->isOpen()) {
            $this->em = $this->managerRegistry->resetManager();
        }

        $this->em->persist($entry);
        $this->em->flush();
    }
}
