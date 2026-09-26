<?php

/** 
 *   this is the Data Tier
 *  this file provide a Single shared PDO connection for the whole app rather than do new connect each time
 *  all models using this connection directly with parameterized queries
 *  this class provide the single connection for the app (singleton pattern)
 *  it provide pdo connection and to db connection management 
*/


class Database
{

    /* - this a static property for the class كذا هي عشان تتيح انه نحتفظ بنفس هذا المتغير في كل التطبيق.
    - it have PDO or null as value. and its started with null before any connection.*/
    private static ?PDO $connection=null;

    /*
      - this the function that create the PDO object and return it. can use this function everywhere without need for a new object

           ^^^ Returns PDO active db connection object
           ^^^ Throws PDOExecption when the connection can NOT be established

        - it check first if there any active connection or not if null then create a connection ,
            and if there a connection then it will return the current pdo connection.
        - then it built the Data Source Name , If no connection. PDO uses this to know the database type ,server, database name and the charset.
        - then building the connection with PDO() and save it in the $connection property
        - about exeptions:
            ERRMODE_EXCEPTION this will give exception error for every SQL error
            FETCH_ASSOC this make the select queries always give the result in Assosiative array with the column name rather than numbers
            ATTR_EMULATE_PREPARES this stops the simulate of phantom qeries from php and causes the server MYSQL to perform the actual preparation if the simulation
            ATTR_PERSISTENT this to avoid a persistent connection
        - at the end of function it return the connection object that ready to use
    */
    public static function connection():PDO{
        if (self::$connection === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            self::$connection = new PDO($dsn, DB_USER, DB_PASSWORD, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES =>false, //this important to protect from SQL injection
                PDO::ATTR_PERSISTENT =>false
            ));
        }

        return self::$connection;
    }
}
