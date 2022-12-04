<?php declare(strict_types = 1);

namespace Vairogs\Utils\Doctrine\Traits;

trait Entity
{
    use CreatedModified;
    use Id;
    use Serializable;
    use Status;
}
