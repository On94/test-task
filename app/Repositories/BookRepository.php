<?php

namespace App\Repositories;

use App\Entity\Book;

/**
 * Class BookRepository
 * @package App\Repositories
 */
class BookRepository extends BaseRepository implements Crud
{
    /**
     * @var Book
     */
    private Book $book;

    /**
     * BookRepository constructor.
     */
    public function __construct()
    {
        $this->book = new Book();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function store(array $data): bool
    {
        try {
            $book = $this->book;
            $dateTime = new \DateTime("now");
            $book->user_id = $data['user_id'];
            $book->first_name = $data['first_name'];
            $book->last_name = $data['last_name'] ?? null;
            $book->timezone = $data['timezone'];
            $book->country_code = $data['country_code'];
            $book->phone_number = $data['phone_number'];
            $book->updated_at = $dateTime;
            $book->created_at = $dateTime;
            self::getEntityManager()->persist($book);
            self::getEntityManager()->flush();
            return true;
        } catch (\Exception $exception) {
            return false;
        }

    }

    /**
     * @param int $userId
     * @param int|null $id
     * @return array
     */
    public function retrieve(int $userId, ?int $id): array
    {
        $queryBuilder = self::getEntityManager()->createQueryBuilder();
        $query = $queryBuilder->select('b')
            ->from(Book::class, 'b')
            ->where('b.user_id = :uId')
            ->setParameter('uId', $userId)
            ->setFirstResult(0)
            ->setMaxResults(100);
        if (isset($id)) {
            $query->andWhere('b.id = :Id')
                ->setParameter('Id', $id);
        }
        return $query->getQuery()->execute();
    }

    /**
     * @param int $userId
     * @param int $bookId
     * @return bool
     */
    public function delete(int $userId, int $bookId):bool
    {
        $queryBuilder = self::getEntityManager()->createQueryBuilder();
        $query = $queryBuilder->delete(Book::class, 'b')
            ->where('b.id = :bId')
            ->andWhere('b.user_id = :uId')
            ->setParameter('bId', $bookId)
            ->setParameter('uId', $userId)
            ->getQuery();
        return $query->execute();
    }

    /**
     * @param int $userId
     * @param int $bookId
     * @param array $data
     * @return bool
     */
    public function update(int $userId, int $bookId, array $data):bool
    {
        $date = date('d-m-y h:i:s');
        $queryBuilder = self::getEntityManager()->createQueryBuilder();
        $query = $queryBuilder->update(Book::class, 'b')
            ->set('b.first_name', ':first_name')
            ->set('b.last_name', ':last_name')
            ->set('b.country_code', ':country_code')
            ->set('b.timezone', ':timezone')
            ->set('b.phone_number', ':phone_number')
            ->set('b.updated_at', ':updated_at')
            ->where('b.id=:id')
            ->andWhere('b.user_id=:user_id')
            ->setParameter('id', $bookId)
            ->setParameter('user_id', $userId)
            ->setParameter('first_name', $data['first_name'])
            ->setParameter('last_name', $data['last_name'])
            ->setParameter('country_code', $data['country_code'])
            ->setParameter('timezone', $data['timezone'])
            ->setParameter('phone_number', $data['phone_number'])
            ->setParameter('updated_at', $date)
            ->getQuery();
        return $query->execute();
    }
}