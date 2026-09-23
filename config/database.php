<!-- this is the Data Tier
 Single shared PDO connection for the whole app 
  Models use this connection directly with parameterized queries -->

<?php

class Database
{
    private static $connection = null;

    public static function connection()
    {
        if (self::$connection === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            self::$connection = new PDO($dsn, DB_USER, DB_PASSWORD, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ));
        }

        return self::$connection;
    }
}
