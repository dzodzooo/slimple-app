<?php
declare(strict_types=1);
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Identifiable;
use App\Entity\Category;

class User extends Identifiable
{
    private string $name;
    private string $email;
    private string $password;
    private Collection $transactions;
    private Collection $categories;
    private Collection $codes;
    private bool $verified;

    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function setPassword(string $password)
    {
        $this->password = $password;
    }
    public function getPassword(): string
    {
        return $this->password;
    }

    public function addTransaction(Transaction $transaction)
    {
        $this->transactions->add($transaction);
    }
    public function addCategory(Category $category)
    {
        $this->categories->add($category);
    }
    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->codes = new ArrayCollection();
    }

    public function addCode(VerificationCode $code)
    {
        $this->codes->add($code);
    }
    public function setVerified(bool $verified)
    {
        $this->verified = $verified;
    }
    public function getVerified()
    {
        return $this->verified;
    }
}