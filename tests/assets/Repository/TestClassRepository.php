<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Repository;

use Doctrine\ORM\EntityRepository;
use Vairogs\Tests\Entity\TestClass;

/**
 * @method TestClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestClass[]    findAll()
 * @method TestClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestClassRepository extends EntityRepository
{
}
