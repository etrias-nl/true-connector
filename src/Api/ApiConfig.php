<?php

declare(strict_types=1);

namespace Etrias\TrueConnector\Api;

class ApiConfig
{
    private ?int $customerId = null;

    public function __construct(?int $customerId = null)
    {
        $this->customerId = $customerId;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }
}
