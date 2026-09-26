<?php
/**
 * Base Model class.
 * Every entity model (User, Photo, Comment, Tag) extends this so they all share
 * the same database connection without opening a new one each time.
 */

// its a abstract class that provide single database connection for all the subclasses that extends from it
abstract class Model
{
    protected $database;

    // this make the connection with the database
    // call the function that Return the PDO connection object and store it in database property. 
    // then the model ready to work with the database useing SQL Queries 
    public function __construct()
    {
        $this->database = Database::connection();
    }
}
