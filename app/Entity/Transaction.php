<?php
declare(strict_types=1);
namespace App\Entity;

use App\Entity\Identifiable;
class Transaction extends Identifiable
{
    private float $amount;
    private string $description;
    private \DateTime $date;

    private User $user;
    private ?Category $category;
    private Receipt $receipt;

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }
    public function setUser(User $user): void
    {
        $user->addTransaction($this);
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
    public function setCategory(?Category $category): void
    {
        if ($category === null)
            return;
        $category->addTransaction($this);
        $this->category = $category;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }
    public function setReceipt(Receipt $receipt): void
    {
        $this->receipt = $receipt;
    }

    public function getReceipt(): Receipt
    {
        return $this->receipt;
    }
}