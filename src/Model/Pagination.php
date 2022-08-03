<?php

declare(strict_types=1);

namespace Etrias\TrueConnector\Model;

class Pagination
{
    private int $count = 0;
    private int $currentPage = 1;
    private int $pageCount = 1;
    private bool $hasNextPage = false;
    private bool $hasPrevPage = false;
    private ?int $limit = null;
    private ?string $sort = null;
}
