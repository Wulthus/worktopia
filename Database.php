<?php

class Database {
    public $connection;
    
    //-------------------------------------------------------------------------------------------CONSTRUCTOR
    /**
     * Construction for database
     * 
     * @param array $config;
     */
     public function __construct($config) {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $this-> connection = new PDO($dsn, $config["username"], $config["password"], $options);
            echo "connected!";
        } catch (PDOException $exeption){
            throw new Exception("Database connection failed {$exeption->getMessage()}");
        }
     }
    //----------------------------------------------------------------------------------------------QUERY METODS
    /**
     * Database Query method
     * 
     * @param string $query
     * 
     * @return PDOStatement
     * @throws PDOException
     */
    public function query($query) {

        try {
            $statement = $this->connection->prepare($query);
            $statement->execute();
            return $statement;
        } catch(PDOException $exception) {
            throw new Exception("SQL query failed to execute ", $exception->getMessage());
        }
    }
    

}