<?php
declare(strict_types=1);
namespace App\Repository;

use \App\Contracts\SessionInterface;
use \App\Contracts\VerificationCodeRepositoryInterface;
use \App\Entity\User;
use \App\Entity\VerificationCode;
use Doctrine\ORM\EntityManager;
use Exception;

class VerificationCodeRepository implements VerificationCodeRepositoryInterface
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly SessionInterface $session
    ) {
    }
    public function generate(): VerificationCode
    {
        $user = $this->entityManager->getReference(User::class, $this->session->get('user')->id);
        if ($user === null)
            throw new Exception('User not found');
        $verificationCode = new VerificationCode();


        $random_hash = md5(openssl_random_pseudo_bytes(32));
        $verificationCode->setCode($random_hash);
        $verificationCode->setUser($user);
        $this->entityManager->persist($verificationCode);
        $this->entityManager->flush();
        return $verificationCode;
    }
    public function tryVerify(string $code): bool
    {
        $user = $this->entityManager->getReference(User::class, $this->session->get('user')->id);
        if ($user === null)
            throw new Exception('User not found');
        $verificationCode = $this->entityManager->getRepository(VerificationCode::class)->findOneBy(['user' => $user, 'code' => $code]);
        $user->setVerified($verificationCode instanceof VerificationCode);
        $this->entityManager->flush();
        return $user->getVerified();
    }
}