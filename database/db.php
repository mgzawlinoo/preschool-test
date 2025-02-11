<?php 
// $host $dbname $username $password

$host = 'localhost';
$dbname = 'preschool';
$username = 'root';
$password = 'password';

// data source name
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
    // $pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    // echo "Connected successfully";
} catch (PDOException $e) {
    echo $e->getMessage();
}
