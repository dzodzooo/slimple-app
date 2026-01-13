<?php
declare(strict_types=1);
namespace App\Entity;

use DateTime;

class VerificationCode
{
    private int $id;
    private string $code;
    private User $user;
    public function getId()
    {
        return $this->id;
    }
    public function getCode()
    {
        return $this->code;
    }
    public function setCode(string $code)
    {
        $this->code = $code;
    }
    public function setUser(User $user)
    {
        $this->user = $user;
        $user->addCode($this);
    }
    public function getUser()
    {
        return $this->user;
    }
}