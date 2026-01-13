<?php
declare(strict_types=1);
namespace App\Contracts;

use App\Entity\VerificationCode;

interface VerificationCodeRepositoryInterface
{
    public function generate(): VerificationCode;
    public function tryVerify(string $code): bool;
}