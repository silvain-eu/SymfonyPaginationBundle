<?php

namespace SilvainEu\Paginator\Factory;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use SilvainEu\Paginator\Model\Page;
use SilvainEu\Paginator\Model\PagePartial;
use SilvainEu\Paginator\Model\PagePartialInterface;
use Symfony\Component\HttpFoundation\Request;

class PaginatorFactory implements PaginatorFactoryInterface
{

    /**
     * @inheritDoc
     */
    public function createFromRequest(Request $request, int $pageSize = self::DEFAULT_PAGE_SIZE): PagePartial
    {
        $page = $request->get(self::QUERY_PARAM_PAGE, 1);
        $page = intval($page);
        if ($page <= 0) {
            $page = 1;
        }
        return new PagePartial($page, $pageSize);
    }

    /**
     * @inheritDoc
     */
    public function executeFromDoctrineQuery(
        PagePartialInterface $pagePartial,
        QueryBuilder         $queryBuilder,
        ?callable            $mapDatas = null): Page
    {
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $previousSelect = $queryBuilder->getDQLPart('select');
        $queryBuilder->select($queryBuilder->expr()->count($rootAlias));
        $queryBuilder->setMaxResults(1);
        try {
            $count = intval($queryBuilder->getQuery()->getSingleScalarResult());
        } catch (NoResultException|NonUniqueResultException) {
            $count = 0;
        }

        $first = true;
        foreach ($previousSelect as $select) {
            $queryBuilder->add("select", $select, !$first);
            $first = false;
        }
        $queryBuilder->setFirstResult(($pagePartial->getCurrentPage() - 1) * $pagePartial->getPageSize());
        $queryBuilder->setMaxResults($pagePartial->getPageSize());
        $datas = $queryBuilder->getQuery()->getResult();
        if ($mapDatas !== null) {
            $datas = array_map($mapDatas, $datas);
        }

        return new Page($pagePartial->getCurrentPage(), $count, $pagePartial->getPageSize(), $datas);
    }
}
