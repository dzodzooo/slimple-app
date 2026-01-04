<?php
declare(strict_types=1);
namespace App\Entity;

use App\Entity\Transaction;
use App\Entity\Identifiable;

class Receipt extends Identifiable
{
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
        $transaction->setReceipt($this);
        $this->transaction = $transaction;
    }
    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }
}