<?php

namespace SilvainEu\Paginator\Model;

/**
 * @template T of object
 */
interface PageInterface extends PagePartialInterface
{
    public function getNbPages(): int;

    public function getNbElements(): int;

    public function hasNextPage(): bool;

    public function hasPreviousPage(): bool;

    /**
     * @return T[]
     */
    public function getData(): array;
}
