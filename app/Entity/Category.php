<?php
declare(strict_types=1);
namespace App\Entity;

class Category
{
    use \App\Traits\Identifiable;
    private string $name;
    private User $user;
    private array $transactions;
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
    public function getUser(): User
    {
        return $this->user;
    }
    public function addTransaction(Transaction $transaction)
    {
        array_push($this->transactions, $transaction);
    }
    public function __construct()
    {
        $this->transactions = [];
    }
}