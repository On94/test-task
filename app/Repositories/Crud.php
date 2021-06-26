<?php

namespace App\Repositories;

/**
 * Interface Crud
 * @package App\Repositories
 */
interface Crud
{
    /**
     * @param array $data
     * @return bool
     */
    public function store(array $data): bool;

    /**
     * @param int $userId
     * @param int|null $id
     * @return array
     */
    public function retrieve(int $userId, ?int $id): array;

    /**
     * @param int $userId
     * @param int $bookId
     * @return bool
     */
    public function delete(int $userId, int $bookId): bool;

    /**
     * @param int $userId
     * @param int $bookId
     * @param array $data
     * @return bool
     */
    public function update(int $userId, int $bookId, array $data): bool;


}