<?php

namespace SilvainEu\Paginator\Model;

class PagePartial implements PagePartialInterface
{

    private int $currentPage;
    private int $pageSize;

    public function __construct(int $currentPage, int $pageSize)
    {
        if ($currentPage < 1) {
            throw new \InvalidArgumentException("Current page must be greater than 0");
        }
        if ($pageSize < 0) {
            throw new \InvalidArgumentException("Number of elements must be greater than 0");
        }
        $this->currentPage = $currentPage;
        $this->pageSize = $pageSize;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function setCurrentPage(int $currentPage): void
    {
        $this->currentPage = $currentPage;
    }

    public function setPageSize(int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }
}
