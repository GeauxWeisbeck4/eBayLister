<?php
/*
# ------------------------------------------------------
# eBayLister - An eBay Listing Creator
# Copyright © 2021 David Rodgers
# Released under the terms of the MIT License
# ------------------------------------------------------
*/

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
                <div class="container">
                <br />
                <?php
                    try {

                        $dbname = "`".str_replace("`","``",$dbname)."`";
                        $pdo_conn->query("DROP DATABASE IF EXISTS $dbname");
                        $pdo_conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
                        $pdo_conn->query("use $dbname");

                        $sql = file_get_contents("install.sql");
                        $pdo_conn->exec($sql);

                        echo "<div style='font-size: 24px;' class='success'>Database " . $dbname . " populated successfully.</div>";

                    } catch(PDOException $error) {
                        echo $sql . "<div style='font-size: 24px; color: red;'><br>" . $error->getMessage() . "</div>";
                    }
                ?>
                <br /><br />
            </div>
        </div>
        <div class="row text-center">
            <div class="col-md-12">
                <div class="form-group">
                    <a class="btn btn-success btn-lg" value="" href="../index.php" role="button">Start!<a>                
                </div>
            </div>
        </div>
    </div>
                <div class="col-md-3"></div>
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