<?php

namespace SilvainEu\Paginator;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use function dirname;

class SilvainEuPaginatorBundle extends Bundle
{
    protected function getContainerExtensionClass(): string
    {
        return SilvainEuPaginatorExtension::class;
    }
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
