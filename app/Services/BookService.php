<?php

namespace App\Services;

use App\Repositories\BookRepository;

/**
 * Class BookService
 * @package App\Services
 */
class BookService
{
    /**
     * @var BookRepository
     */
    private BookRepository $repository;

    /**
     * BookService constructor.
     */
    public function __construct()
    {
        $this->repository = new BookRepository();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function store(array $data): bool
    {
        return $this->repository->store($data);
    }

    /**
     * @param int $userId
     * @param int $bookId
     * @return bool
     */
    public function delete(int $userId, int $bookId): bool
    {
        return $this->repository->delete($userId, $bookId);
    }

    /**
     * @param int $user_id
     * @param int $book_id
     * @param array $data
     * @return bool
     */
    public function update(int $user_id, int $book_id, array $data): bool
    {
        return $this->repository->update($user_id, $book_id, $data);
    }

    /**
     * @param int $userId
     * @param int|null $id
     * @return array
     */
    public function retrieve(int $userId, ?int $id): array
    {
        return $id ? $this->repository->retrieve($userId, $id) : [];
    }
}