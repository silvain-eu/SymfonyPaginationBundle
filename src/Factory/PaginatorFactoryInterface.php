<?php

namespace SilvainEu\Paginator\Factory;

use Closure;
use Doctrine\ORM\QueryBuilder;
use SilvainEu\Paginator\Model\Page;
use SilvainEu\Paginator\Model\PagePartial;
use SilvainEu\Paginator\Model\PagePartialInterface;
use Symfony\Component\HttpFoundation\Request;

interface PaginatorFactoryInterface
{
    public const DEFAULT_PAGE_SIZE = 10;
    public const QUERY_PARAM_PAGE = 'page';

    /**
     * Create a PagePartial object from a request.
     * @param Request $request The request.
     * @param int $pageSize The page size.
     * @return PagePartial $pagePartial The page partial object.
     */
    public function createFromRequest(Request $request, int $pageSize = self::DEFAULT_PAGE_SIZE): PagePartial;

    /**
     * Apply the pagination to the query builder and return a Page object.
     * @template T of object
     * @param PagePartialInterface $pagePartial The page partial object.
     * @param QueryBuilder $queryBuilder The query builder.
     * @param (Closure(mixed): T)|null $mapDatas A function to map the datas.
     * @return Page<T> $page The page object.
     */
    public function executeFromDoctrineQuery(
        PagePartialInterface $pagePartial,
        QueryBuilder         $queryBuilder,
        ?Closure             $mapDatas = null): Page;
}
