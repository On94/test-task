<?php


namespace App\Repositories;


use App\Entity\User;
use DateTime;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends BaseRepository
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function register(array $data): bool
    {
        try {
            $user = $this->user;
            $user->password = sha1($data['password']);
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->updated_at = new DateTime("now");
            $user->created_at = new DateTime("now");
            self::getEntityManager()->persist($user);
            self::getEntityManager()->flush();
        } catch (\Exception $exception) {
            return false;
        }
        return true;
    }

    /**
     * @param string $token
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function saveToken(string $token, string $email, string $password): bool
    {
        $password = sha1($password);
        $queryBuilder = self::getEntityManager()->createQueryBuilder();
        $query = $queryBuilder->update(User::class, 'u')
            ->set('u.token', ':token')
            ->where('u.email=:email')
            ->andWhere('u.password=:password')
            ->setParameter('token', $token)
            ->setParameter('email', $email)
            ->setParameter('password', $password)
            ->getQuery();
        return $query->execute();

    }

    /**
     * @param string $token
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function checkToken(string $token): array
    {
        $queryBuilder = self::getEntityManager()->createQueryBuilder();
        $query = $queryBuilder->select('u')
            ->from(User::class, 'u')
            ->where('u.token = :token')
            ->setParameter('token', $token)
            ->getQuery();
        $json = json_encode($query->getOneOrNullResult());
        return json_decode($json, true) ?? [];
    }
}