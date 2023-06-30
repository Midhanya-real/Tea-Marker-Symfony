<?php

namespace App\Services\PaymentService\PaymentBuilder;

class PaymentBuilder implements PaymentBuilderInterface
{
    private array $payment;

    /**
     * @param string $value
     * @param string $currency
     * @return $this
     */
    public function setAmount(string $value, string $currency): static
    {
        $amount = [
            'value' => $value,
            'currency' => $currency,
        ];

        $this->payment['amount'] = $amount;

        return $this;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setConfirmation(string $url): static
    {
        $confirmation = [
            'type' => 'redirect',
            'return_url' => $url,
        ];

        $this->payment['confirmation'] = $confirmation;

        return $this;
    }

    /**
     * @param bool $capture
     * @return $this
     */
    public function setCapture(bool $capture): static
    {
        $this->payment['capture'] = $capture;

        return $this;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->payment['description'] = $description;

        return $this;
    }

    /**
     * @return array
     */
    public function getPayment(): array
    {
        return $this->payment;
    }
}