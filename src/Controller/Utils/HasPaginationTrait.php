<?php

namespace App\Controller\Utils;

use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

trait HasPaginationTrait
{
    public function paginate($queryBuilder, $page, $limit): Pagerfanta
    {
        $adapter = new QueryAdapter($queryBuilder);

        return Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $page,
            $limit,
        );
    }
}
