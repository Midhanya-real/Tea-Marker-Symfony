<?php

namespace App\Services\PaymentService\PaymentBuilder;

use App\Entity\Order;
use App\Entity\User;

class PaymentBuilder implements PaymentBuilderInterface
{
    private array $payment;

    /**
     * @param string $price
     * @param string $currency
     * @return $this
     */
    public function setAmount(string $price, string $currency): static
    {
        $amount = [
            'value' => $price,
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

    public function setMetaData(User $user, Order $order): static
    {
        $this->payment['metadata'] = [
            'order_id' => $order->getId(),
            'user_id' => $user->getId()
        ];

        return $this;
    }

    public function setTestMode(bool $mode): static
    {
        $this->payment['test'] = $mode;

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