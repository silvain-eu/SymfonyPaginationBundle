<?php

namespace SilvainEu\Paginator\Model;

interface PagePartialInterface
{
    public function getCurrentPage(): int;

    public function getPageSize(): int;
}
