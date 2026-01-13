<?php
declare(strict_types=1);

namespace App\Services;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Contracts\AuthInterface;
use App\Contracts\UserRepositoryInterface;
use App\Contracts\VerificationCodeRepositoryInterface;
use App\DataObject\UserDTO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
class AuthService implements AuthInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly VerificationCodeRepositoryInterface $verificationCodeRepository,
        private readonly PHPMailer $mailer
    ) {
    }
    public function register(array $userData)
    {
        return $this->userRepository->create($userData);
    }

    public function userExists(array $userData): UserDTO|null
    {
        return $this->userRepository->getByEmail($userData['email']);
    }

    public function login(array $userData)
    {
        return $this->userRepository->login($userData);
    }

    public function sendVerificationCode($user)
    {
        $verificationCode = $this->verificationCodeRepository->generate();
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host = "host.docker.internal";                     //Set the SMTP server to send through
            $mail->SMTPAuth = false;                                   //Enable SMTP authentication
            $mail->Port = 1025;

            //Recipients
            $mail->setFrom('noreply@slimple.com', 'admin');
            $mail->addAddress($user->email, $user->name);     //Add a recipient

            //Content
            $mail->Subject = 'Verify your email';
            $mail->Body = "Your code is {$verificationCode->getCode()}";

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function tryVerify(string $code): bool
    {
        return $this->verificationCodeRepository->tryVerify($code);
    }
}