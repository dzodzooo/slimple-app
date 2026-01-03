<?php
declare(strict_types=1);
namespace App\Entity;

use App\Entity\Transaction;

class Receipt
{
    use \App\Traits\Identifiable;
    private string $filename;
    private Transaction $transaction;
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }
    public function getFilename(): string
    {
        return $this->filename;
    }
    public function setTransaction(Transaction $transaction): void
    {
        $this->transaction = $transaction;
    }
    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }
}