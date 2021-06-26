<?php


namespace App\Entity;


/**
 * @ORM\Entity
 * @ORM\Table(name="books")
 */
class Book
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    public $id;


    /**
     * @ORM\Column(type="integer")
     */
    public $user_id;


    /**
     * @ORM\Column(type="string")
     */
    public $first_name;

    /**
     * @ORM\Column(type="string")
     */
    public $last_name;


    /**
     * @ORM\Column(type="string")
     */
    public $phone_number;

    /**
     * @ORM\Column(type="string")
     */
    public $country_code;

    /**
     * @ORM\Column(type="string")
     */
    public $timezone;

    /**
     * @ORM\Column(type="datetime")
     */
    public $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    public $updated_at;
}