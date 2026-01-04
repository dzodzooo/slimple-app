<?php
declare(strict_types=1);
namespace App\Entity;

use App\Entity\Identifiable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Category extends Identifiable
{
    private string $name;
    private User $user;
    private Collection $transactions;
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
        $user->addCategory($this);
        $this->user = $user;
    }
    public function getUser(): User
    {
        return $this->user;
    }
    public function addTransaction(Transaction $transaction)
    {
        $transaction->setCategory($this);
        $this->transactions->add($transaction);
    }
    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }
}