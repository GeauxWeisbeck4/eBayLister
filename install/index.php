<?php
/*
# ------------------------------------------------------
# eBayLister - An eBay Listing Creator
# Copyright © 2021 David Rodgers
# Released under the terms of the MIT License
# ------------------------------------------------------
*/

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');
require "../dbconnect.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>eBayLister</title>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- FontAwesome 4.4.0 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- Custom -->
    <link rel="stylesheet" href="../css/custom.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <br />
                <img src="../images/eBayListerLogo.png">
            </div>
        </div>
        <div class="row text-center">
            <div class="col-md-12">
                <img src="../images/snoopy.gif" title="The Red Baron">
                <br />
                <h2>Welcome to eBayLister</h2>
                <?php
                if (testConnect() == 1) {
                    ?>
                    <div class="form-group">
                        <a class="btn btn-success" onClick="return confirm('This will overwrite the existing database. Continue?')" href="install.php">Install SQL</a>
                    <?php } else { ?>
                        <div style="font-size: 22px;">
                            <br />
                            You need to edit <span class="warning">dbconnect.php</span> and check your user and database connection credentials.
                        </div>       
                    <?php } ?>
            </div>
        </div>
    </div>

    <!-- jQuery 3.6.0 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Bootstrap 3.3.7 -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
    </script>

</body>

</html>