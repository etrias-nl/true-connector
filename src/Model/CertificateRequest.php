<?php

declare(strict_types=1);

namespace Etrias\TrueConnector\Model;

class CertificateRequest implements CustomerAwareRequest
{
    use CustomerAwareTrait;

    public const ISSUER_LETS_ENCRYPT = 'letsencrypt';
    public const VALIDATION_METHOD_DNS = 'dns';
    public const PRODUCT_ID = '8150653d-6bae-45fc-97b9-1173f81a0e9b';

    private string $issuer = self::ISSUER_LETS_ENCRYPT;
    private ?string $commonName = null;
    private ?array $hostnames = null;
    private ?string $productId = self::PRODUCT_ID;
    private string $validationMethod = self::VALIDATION_METHOD_DNS;

    public function getCommonName(): ?string
    {
        return $this->commonName;
    }

    public function setCommonName(?string $commonName): self
    {
        $this->commonName = $commonName;

        return $this;
    }

    public function getHostnames(): ?array
    {
        return $this->hostnames;
    }

    public function setHostnames(?array $hostnames): self
    {
        $this->hostnames = $hostnames;

        return $this;
    }

    public function getValidationMethod(): string
    {
        return $this->validationMethod;
    }

    public function setValidationMethod(string $validationMethod): self
    {
        $this->validationMethod = $validationMethod;

        return $this;
    }

    public function getIssuer(): string
    {
        return $this->issuer;
    }

    public function setIssuer(string $issuer): self
    {
        $this->issuer = $issuer;

        return $this;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function setProductId(?string $productId): self
    {
        $this->productId = $productId;

        return $this;
    }
}
