<?php

declare(strict_types=1);

namespace Etrias\TrueConnector\Model;

trait CustomerAwareTrait
{
    private ?int $customerId = null;

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomerId(?int $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }
}
