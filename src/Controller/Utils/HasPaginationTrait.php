<?php

namespace App\Controller\Utils;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

trait HasPaginationTrait
{
    public function paginate(QueryBuilder $queryBuilder, int $page, int $limit): Pagerfanta
    {
        $adapter = new QueryAdapter($queryBuilder);

        return Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $page,
            $limit,
        );
    }
}
