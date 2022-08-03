<?php

declare(strict_types=1);

namespace Etrias\TrueConnector\Model;

interface CustomerAwareRequest extends RequestInterface
{
    public function getCustomerId(): ?int;

    public function setCustomerId(?int $customerId): self;
}
