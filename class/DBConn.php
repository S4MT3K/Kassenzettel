<?php

abstract class DBConn
{
    private static string $servername = "localhost";
    private static string $username = "root";
    private static string $pass = "";
    private static string $dbname = "kassenzettel";

    protected static function getConn() : PDO //DIESE FUNKTION DIENT ALS DIREKTER METHODENAUFRUF OHNE INSTANZIERUNG DER KLASSE DBCONN SELBST
    {
        $servername = self::$servername;
        $username = self::$username;
        $pass = self::$pass;
        $dbname = self::$dbname;
        return new PDO("mysql:host=$servername;dbname=$dbname", $username, $pass);
    }

    public function create() : void
    {
    }

    public static function read(int $id) : static
    {
        return new static;
    }

    public function update() : void
    {
    }

    public function delete() : void
    {
    }
}
