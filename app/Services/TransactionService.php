<?php
declare(strict_types=1);
namespace App\Services;

use App\Contracts\CategoryRepositoryInterface;
use App\Contracts\SessionInterface;
use App\Contracts\TransactionRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Exception\InvalidFileUploadException;
use App\Repository\TransactionRepository;
use DateMalformedStringException;
use DateTime;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use ReflectionClass;

define('TEMP_DIR_PATH', __DIR__ . '/../temp');
class TransactionService
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly SessionInterface $session
    ) {
    }
    public function addTransactionsFromFile(string $filename)
    {

    }
    public function getAllTransactionsAndCategories()
    {
        $transactions = $this->transactionRepository->getAll();
        $categories = $this->categoryRepository->getAll();
        return [$transactions, $categories];
    }
    public function __call($method, $args)
    {
        $reflectionClass = new ReflectionClass(TransactionRepository::class);
        if ($reflectionClass->hasMethod($method)) {
            $callable = $reflectionClass->getMethod($method);
            $callable->invoke($this->transactionRepository, $args[0]);
        }
    }

    public function uploadFileFromRequest(ServerRequestInterface $request)
    {
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['file'];

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile(TEMP_DIR_PATH, $uploadedFile);
            $this->persistTransactionsFromUploadedFile($filename);
            unlink(TEMP_DIR_PATH . DIRECTORY_SEPARATOR . $filename);
            return true;
        }
        return false;
    }

    function persistTransactionsFromUploadedFile($filename)
    {
        $contents = file_get_contents(TEMP_DIR_PATH . DIRECTORY_SEPARATOR . $filename);

        if ($contents === false)
            throw new InvalidFileUploadException("Couldn't read file.");

        $rows = explode("\n", $contents);
        try {
            foreach ($rows as $row) {
                [$date, $description, $amount, $categoryId] = explode("\t", $row);
                $this->transactionRepository->create(['date' => $date, 'description' => $description, 'amount' => $amount, 'category' => $categoryId]);
            }
        } catch (DateMalformedStringException $exception) {
            unlink(TEMP_DIR_PATH . DIRECTORY_SEPARATOR . $filename);
        }

    }

    function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}