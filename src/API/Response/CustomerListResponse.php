<?php

declare(strict_types=1);

namespace App\API\Response;

use JMS\Serializer\Annotation as Serializer;
use Knp\Component\Pager\Pagination\PaginationInterface;

class CustomerListResponse extends AbstractResponse
{
    /**
     * @Serializer\Type("array<App\Entity\Customer>")
     */
    private iterable $customers;

    private int $currentPage;

    private int $itemsPerPage;

    private int $totalItems;

    public function __construct(
        iterable $customers,
        int $currentPage,
        int $itemsPerPage,
        int $totalItems
    ) {
        $this->customers = $customers;
        $this->currentPage = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
        $this->totalItems = $totalItems;
    }

    public static function createFromPagination(PaginationInterface $pagination): self
    {
        return new self(
            $pagination->getItems(),
            $pagination->getCurrentPageNumber(),
            $pagination->getItemNumberPerPage(),
            $pagination->getTotalItemCount()
        );
    }
}
