<?php

namespace App\Services\PaymentService\PaymentBuilder;

interface PaymentBuilderInterface
{
    public function setAmount(string $price, string $currency): static;

    public function setConfirmation(string $url): static;

    public function setCapture(bool $capture): static;

    public function setDescription(string $description): static;

    public function getPayment(): array;
}