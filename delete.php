<?php
/*
# ------------------------------------------------------
# eBayLister - An eBay Listing Creator
# Copyright © 2021 David Rodgers
# Released under the terms of the MIT License
# ------------------------------------------------------
*/

require "dbconnect.php";

// Delete the record
$pdo_statement=$pdo_conn->prepare("delete from listings where id=" . $_GET['id']);
$pdo_statement->execute();
header('location:search.php');

?>