<?php

namespace SilvainEu\Paginator\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @template T of object
 * @template-implements PageInterface<T>
 */
class Page extends PagePartial implements PageInterface
{
    private int $nbElements;

    /**
     * @var Collection<T>
     */
    private Collection $data;

    /**
     * @param int $currentPage Page courante
     * @param int $nbElements Nombre d'éléments
     * @param int $pageSize Taille de la page
     * @param Collection<T>|T[] $data Données
     */
    public function __construct(int $currentPage, int $nbElements, int $pageSize, Collection|array $data)
    {
        parent::__construct($currentPage, $pageSize);
        if ($nbElements < 0) {
            throw new \InvalidArgumentException("Number of elements must be greater than 0");
        }
        $this->nbElements = $nbElements;
        $this->setData($data);
    }

    public function getNbElements(): int
    {
        return $this->nbElements;
    }

    public function getNbPages(): int
    {
        return (int)ceil($this->getNbElements() / $this->getPageSize());
    }

    public function hasNextPage(): bool
    {
        return $this->getCurrentPage() < $this->getNbPages();
    }

    public function hasPreviousPage(): bool
    {
        return $this->getCurrentPage() > 1;
    }

    public function setNbElements(int $nbElements): void
    {
        $this->nbElements = $nbElements;
    }

    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        return $this->data->toArray();
    }

    /**
     * @param Collection<T>|T[] $data
     */
    public function setData(Collection|array $data): void
    {
        if (is_array($data)) {
            $data = new ArrayCollection($data);
        }
        $this->data = $data;
    }

}
