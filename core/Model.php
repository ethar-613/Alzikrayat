<?php
/**
 * Base Model class.
 * Every entity model (User, Photo, Comment) extends this so they all share
 * the same database connection without opening a new one each time.
 */

abstract class Model
{
    protected $database;

    public function __construct()
    {
        $this->database = Database::connection();
    }
}
