<?php


namespace App\Repositories;


use Doctrine\ORM\EntityManager;

/**
 * Class BaseRepository
 * @package App\Repositories
 */
abstract class BaseRepository
{
    /**
     * @var EntityManager
     */
    private static EntityManager $entityManager;


    /**
     * @param EntityManager $entityManager
     */
    public static function setEntityManager(EntityManager $entityManager)
    {
        self::$entityManager = $entityManager;
    }

    /**
     * @return EntityManager
     */
    protected static function getEntityManager(): EntityManager
    {
        return self::$entityManager;
    }

}