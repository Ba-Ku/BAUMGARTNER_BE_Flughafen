<?php


class Database
{
    private $database;

    public function __construct()
    {
        $this->database = new PDO(DATABASE_TYPE . ":host=" . DATABASE_HOST . ";dbname=" . DATABASE_NAME . ";charset=" . DATABASE_CHARSET, DATABASE_USER, DATABASE_PASSWORD);
    }

    /**
     * @return PDO
     */
    public function getDatabaseContent()
    {
        return $this->database;
    }


}