<?php
/*
# ------------------------------------------------------
# eBayLister - An eBay Listing Creator
# Copyright © 2021 David Rodgers
# Released under the terms of the MIT License
# ------------------------------------------------------
*/

// Debug
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

// Default Time Zone
date_default_timezone_set("America/New_York");

// SQL Connection
$dbhost         = "localhost";          // Database server IP
$dbuser         = "root";               // Database user name
$dbpass         = "root";               // Database user password
$dbname         = "apps_ebaylister";    // Database for this app
$dsn            = "mysql:host=$dbhost;dbname=$dbname";
$options        = array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION );
$pdo_conn       = new PDO($dsn, $dbuser, $dbpass, $options);

// Connect to the database
function connect($dbuser, $dbpass) {
    
    global $pdo_conn;
    
    $pdo_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo_conn;
}

// Test the database connection
function testConnect()
{
    global $dbuser, $dbpass;

 	if ( connect($dbuser, $dbpass) ) {
        return true;
 	} else {
 		return false;
 	}
}

// Get the last ID in the listings table
function getLastID() {

    global $pdo_conn;

    try {
        $statement = "SELECT id FROM listings ORDER BY id DESC LIMIT 1";
        $pdo_statement = $pdo_conn->prepare($statement);
        $pdo_statement->execute();
        $last_id = $pdo_statement->fetch();
        return $last_id[0];
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}

// Get the next unused ID in the listings table
function getNextID() {
    
    global $pdo_conn;
    
    try {
        $statement = "SELECT id FROM listings ORDER BY id DESC LIMIT 1";
        $pdo_statement = $pdo_conn->prepare($statement);
        $pdo_statement->execute();
        $last_id = $pdo_statement->fetch();
        return $last_id[0] + 1;
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}

// Get the Auto Increment index in the listings table
function getAutoID() {

    global $pdo_conn;

    try {
        $statement = "SELECT `AUTO_INCREMENT`FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'apps_ebaylister' AND TABLE_NAME = 'listings'";
        $pdo_statement = $pdo_conn->prepare($statement);
        $pdo_statement->execute();
        $auto_id = $pdo_statement->fetch();
        return $auto_id[0];
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}

// Check if ID exists in listings
function idExists($id) {
    
    global $pdo_conn;

    try {
        $stmt = $pdo_conn->prepare('SELECT * FROM listings WHERE ID=?');
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();

        if($count == 0)
        {
            //return $count;
            return 0;
        } else {
            return 1;
        }

    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}

?>