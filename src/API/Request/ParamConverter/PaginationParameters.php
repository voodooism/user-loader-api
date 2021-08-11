<?php
declare(strict_types=1);

namespace App\API\Request\ParamConverter;

use Symfony\Component\Validator\Constraints as Assert;

class PaginationParameters
{
    /**
     * @Assert\Positive()
     */
    private int $page;

    /**
     * @Assert\Positive()
     */
    private int $limit;

    public function __construct(int $page, int $limit)
    {
        $this->page  = $page;
        $this->limit = $limit;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
