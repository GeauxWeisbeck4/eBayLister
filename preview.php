<?php
/*
# ------------------------------------------------------
# eBayLister - An eBay Listing Creator
# Copyright © 2021 David Rodgers
# Released under the terms of the MIT License
# ------------------------------------------------------

Thanks to Vincy over at PHPPot.com for PDO code snippets
https://phppot.com/php/php-search-and-pagination-using-pdo/

*/

require "dbconnect.php";

// Load Config
$pdo_statement = $pdo_conn->prepare("SELECT * FROM config where id=1");
$pdo_statement->execute();
$config = $pdo_statement->fetchAll();

// Load Preview
if (!empty($_GET["id"]))
{
    $_POST['submit'] = "Preview";
    $_POST['saveListing'] = NULL;
    $saveAsNew = NULL;
    $localPickup = NULL;
    $headImageHeight = NULL;
    $previewID = $_GET["id"];
    $pdo_statement = $pdo_conn->prepare("SELECT * FROM listings where id=$previewID");
    $pdo_statement->execute();
    $preview = $pdo_statement->fetchAll();
    $_POST['listingID'] = stripslashes($preview[0]["id"]);
    $_POST['lastModified'] = stripslashes($preview[0]["lastModified"]);
    $_POST['itemTitle'] = stripslashes($preview[0]["itemTitle"]);
    $_POST['titleColor'] = stripslashes($preview[0]["titleColor"]);
    $_POST['headImage'] = stripslashes($preview[0]["headImage"]);
    $_POST['headImageHeight'] = stripslashes($preview[0]["headImageHeight"]);
    $_POST['headerColor'] = stripslashes($preview[0]["headerColor"]);
    $_POST['itemHeading'] = stripslashes($preview[0]["itemHeading"]);
    $_POST['itemDescription'] = stripslashes($preview[0]["itemDescription"]);
    $_POST['addInfo'] = stripslashes($preview[0]["addInfo"]);
    $_POST['itemLocation'] = stripslashes($preview[0]["itemLocation"]);
    $_POST['listInfo1'] = stripslashes($preview[0]["listInfo1"]);
    $_POST['listInfo2'] = stripslashes($preview[0]["listInfo2"]);
    $_POST['listInfo3'] = stripslashes($preview[0]["listInfo3"]);
    $_POST['localPickup'] = stripslashes($preview[0]["localPickup"]);
}

// Set Current ID
if ($_POST['saveListing'] == "on")
    if (!empty($_POST['listingID'])) {
        $current_ID = $_POST['listingID'];
}

// Save As New
if ($_POST['submit'] == "Save New") {
    $saveAsNew = 1;
    $current_ID = getAutoID();
}
else {
    $saveAsNew = 0;
}

// Set Checkboxes
if ($_POST["localPickup"] == "on") {
    $_POST["localPickup"] = "1";
}

// Fix Image Height
if ($_POST["headImageHeight"] == 0) {
    $_POST["headImageHeight"] = NULL;
}

// Save Record
if (isset($_POST['submit'])) {    
    
    // If we're saving an existing record..
    if ($_POST['saveListing'] == "on" || $saveAsNew == 1)
    {
        // Update Existing Record
        if (!empty($_POST['listingID']) && $current_ID != getAutoID() && $saveAsNew != 1) {
        
            $data = [
                "id" => $_POST['listingID'],
                "lastModified" => date('Y-m-d H:i:s'),
                "itemTitle" => addslashes($_POST['itemTitle']),
                "titleColor" => $_POST['titleColor'],
                "headImage" => $_POST['headImage'],
                "headImageHeight" => $_POST['headImageHeight'],
                "headerColor" => $_POST['headerColor'],
                "itemHeading" => addslashes($_POST['itemHeading']),
                "itemDescription" => addslashes($_POST['itemDescription']),
                "addInfo" => addslashes($_POST['addInfo']),
                "itemLocation" => addslashes($_POST['itemLocation']),
                "listInfo1" => addslashes($_POST['listInfo1']),
                "listInfo2" => addslashes($_POST['listInfo2']),
                "listInfo3" => addslashes($_POST['listInfo3']),
                "localPickup" => $_POST['localPickup']
            ];
            
            $sql = "UPDATE listings SET 
                lastModified=:lastModified,
                itemTitle=:itemTitle, 
                titleColor=:titleColor, 
                headImage=:headImage,
                headImageHeight=:headImageHeight,
                headerColor=:headerColor,
                itemHeading=:itemHeading,
                itemDescription=:itemDescription,
                addInfo=:addInfo,
                itemLocation=:itemLocation,
                listInfo1=:listInfo1,
                listInfo2=:listInfo2,
                listInfo3=:listInfo3,
                localPickup=:localPickup
                WHERE id=:id";
            $stmt= $pdo_conn->prepare($sql);
            $stmt->execute($data);

        } else {
            
            // Check default colors
            if (!isset($_POST['headerColor'])) {
                $_POST['headerColor'] = "DeepSeaGreen";
            }
            if (!isset($_POST['titleColor'])) {
                $_POST['titleColor'] = "DimGrey";
            }

            // Save New Record
            $stmt = $pdo_conn->prepare('INSERT INTO listings (lastModified, itemTitle, titleColor, headImage, headImageHeight, headerColor, itemHeading, itemDescription, addInfo, itemLocation, listInfo1, listInfo2, listInfo3, localPickup) VALUES (:lastModified, :itemTitle, :titleColor, :headImage, :headImageHeight, :headerColor, :itemHeading, :itemDescription, :addInfo, :itemLocation, :listInfo1, :listInfo2, :listInfo3, :localPickup)');

            $stmt->execute([
                "lastModified" => date('Y-m-d H:i:s'),
                "itemTitle" => addslashes($_POST['itemTitle']),
                "titleColor" => $_POST['titleColor'],
                "headImage" => $_POST['headImage'],
                "headImageHeight" => $_POST['headImageHeight'],
                "headerColor" => $_POST['headerColor'],
                "itemHeading" => addslashes($_POST['itemHeading']),
                "itemDescription" => addslashes($_POST['itemDescription']),
                "addInfo" => addslashes($_POST['addInfo']),
                "itemLocation" => addslashes($_POST['itemLocation']),
                "listInfo1" => addslashes($_POST['listInfo1']),
                "listInfo2" => addslashes($_POST['listInfo2']),
                "listInfo3" => addslashes($_POST['listInfo3']),
                "localPickup" => $_POST['localPickup']
            ]);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'meta.php' ?>

    <!-- Custom Styles -->
    <style>
        .itemDesc { margin-left: 10px; margin-right: 10px; } 
        .panel-default > .panel-heading-custom {
            background: <?php echo $_POST['headerColor']; ?>;
            color: #fff;
            font-size: 24px;
            font-weight: bold;
            font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- Header -->
        <div class="row">
            <div class="col-md-12" style="margin-top: 25px;">
                <div class="col-md-3">
					<img src="images/eBayListerLogo.png">
				</div>
				<div class="col-md-3" style="margin-top: 40px;">
					<a href="search.php"><i class="headerMenuIcon fa fa-search fa-lg" title="Search"></i></a>
					<a href="editor.php"><i style="color:black;" class="headerMenuIcon fa fa-file-code-o fa-lg" title="Editor"></i></a>
					<a href="config.php"><i style="color:black;" class="headerMenuIcon fa fa-cog fa-lg" title="Configuration"></i></a>
					<?php
						if (testConnect() == FALSE) {
							echo "<i style='color:red;' class='headerMenuIcon fa fa-database fa-lg' title='Database Connection Error!'></i>";
						}
					?>
				</div>                         
                <div class="col-md-6" style="margin-top: 40px;">
                <form action="processform.php" method="post">
                    <div class="col-md-6">
                        <div class="form-group">                              
                            <a class="btn btn-success btn-lg btn-block" data-clipboard-action="cut" data-clipboard-target="#listing">Copy HTML</a>
                        </div>                    
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <?php
                        if (!empty($current_ID))
                        { ?>
                            <a href="index.php?id=<?php echo $current_ID; ?>" class="btn btn-info btn-lg btn-block" value="Back" role="button">Back</a>
                        <?php } else { ?>
                            <a onclick="window.history.go(-1); return false;" class="btn btn-info btn-lg btn-block" value="Back" role="button">Back</a>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>        
        </div>

        <hr />

        <!-- Greetings -->
        <div class="row">
            <div class="col-md-12">
                <center>
                    <?php 
                        if(!empty($_POST['headImage'])) {                             
                            if(!empty($_POST['headImageHeight'])) {
                                $headImageHeight = $_POST['headImageHeight'];
                            }
                            echo '<img height="'. $_POST['headImageHeight'] . '" title="Greetings Programs" src="' . $_POST['headImage'] . '">'; }
                    ?>
                </center>
                <br />
            </div>
        </div>

        <!-- Heading -->
        <div class="row">
            <div class="col-md-12">
                <div title="heading" style="margin-left: 10%; margin-right: 10%; font-family: 'Trebuchet MS'; font-size: 16px; font-weight:bold;">
                    <center>
                        <span style="font-size: 24px; color: <?php echo $_POST['titleColor']; ?>"><?php echo $_POST["itemTitle"] ?></span>
                        <br /><br />
                        <?php echo $_POST["itemHeading"] ?>
                    </center>
                </div>
            </div>
        </div>

        <hr />

        <!-- Description -->
        <div class="row">
            <div class="col-md-12">
                <div class="itemDesc" title="Item Description" style="font-family: Arial; font-size: 22px;">
                    <?php echo $_POST["itemDescription"]; ?>
                </div>
            </div>
        </div>

        <hr />

        <?php
        if ($_POST["localPickup"] != 0 || !empty($_POST["listInfo1"]) || !empty($_POST["listInfo2"]) || !empty($_POST["listInfo3"]))
        {
        ?>
        
        <!-- Listing Info Bullets -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-custom">LISTING INFORMATION</div>
                    <div class="panel-body">
                        <?php
                            if ($_POST['localPickup'] == "1") {
                                if(!empty($_POST['itemLocation'])) {        
                                    echo '<li><span style="color: darkred; font-weight: bold;">This item is available for local pickup near ' . $_POST['itemLocation'] . '.<span></li>';                                    
                                } else {
                                    echo '<li><span style="color: darkred; font-weight: bold;">This item is available for local pickup.</span></li>';
                                }
                            }                    
                        
                            if (!empty($_POST['listInfo1'])) {
                                echo '<li><strong>' . $_POST['listInfo1'] . '</strong></li>';
                            }
                        
                            if (!empty($_POST['listInfo2'])) {
                                    echo '<li><strong>' . $_POST['listInfo2'] . '</strong></li>';
                            }

                            if (!empty($_POST['listInfo3'])) {
                                echo '<li><strong>' . $_POST['listInfo3'] . '</strong></li>';
                            }

                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- Information Panels -->
        <div class="row panel-container">
        <div class="col col-sm-4">
                <div class="panel panel-success">
                    <div class="panel-heading"><?php echo $config[0]['pricingTitle']; ?></div>
                    <div class="panel-body"><?php echo $config[0]['pricing']; ?>
                    </div>
                </div>
            </div>        
        <div class="col col-sm-4">
                <div class="panel panel-info">
                    <div class="panel-heading"><?php echo $config[0]['offerTitle']; ?></div>
                    <div class="panel-body"><?php echo $config[0]['offers']; ?>
                    </div>
                </div>
            </div>        
            <div class="col col-sm-4">
                <div class="panel panel-warning">
                    <div class="panel-heading"><?php echo $config[0]['shippingTitle']; ?></div>
                    <div class="panel-body"><?php echo $config[0]['shipping']; ?>
                    </div>
                </div>
            </div>
        </div>

<?php 
if ($_POST['addInfo'] != '')
{ 
    ?>
        <!-- Additional Information Header-->
        <div class="row">
            <div class="col-md-12">
                <div
                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; padding-left: 10px; height: 40px; line-height: 40px; background-color: <?php echo $_POST['headerColor']; ?>; color: white; font-size: 24px; font-weight: bold;">
                    ADDITIONAL INFORMATION
                </div>
            </div>
        </div>

        <!-- Additonal Product Info Text -->
        <div class="row">
            <div class="col-md-12">
                <br />
                <?php echo $_POST["addInfo"]; ?>
            </div>
        </div>

        <br />
        <br />
    <?php 
} 
?>
<!-- ############################################################## -->
<!-- Listing Code                                                -->
<!-- ############################################################## -->

<div class="row">
<div class="col-md-12">

<!-- Listing HTML -->
<textarea style="height: 300px;" class="form-control" id="listing" >

<!--
This listing HTML was generated with eBayLister!
https://github.com/caressofsteel/eBayLister 
-->

<!-- Bootstrap -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Custom Styles -->
<style>
.itemDesc { margin-left: 10px; margin-right: 10px; }    
.localPickup { color: #ff6600; font-weight: bold; }
.panel-default>.panel-heading-custom {
background: <?php echo $_POST['headerColor'];
?>;
color: #fff;
font-size: 24px;
font-weight: bold;
}
</style>

<!-- Greetings -->
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<center>
<?php 
if(!empty($_POST['headImage'])) { 
if(!empty($_POST['headImageHeight'])) {
$headImageHeight = $_POST['headImageHeight'];
}
echo '<img height="'. $_POST['headImageHeight'] . '" title="Greetings Programs" src="' . $_POST['headImage'] . '">'; }
?>
</center>
<br />
</div>
</div>

<!-- Heading -->
<div class="row">
<div class="col-md-12">
<div title="heading" style="margin-left: 15px; margin-right: 15px; font-family: 'Trebuchet MS'; font-size: 16px; font-style: italic; font-weight:bold;">
<center>
<span style="font-size: 24px; color: <?php echo $_POST['titleColor']; ?>"><?php echo $_POST["itemTitle"] ?></span>
<br /><br />
<?php echo $_POST["itemHeading"] ?>
</center>
</div>
</div>
</div>

<hr />

<!-- Description -->
<div class="row">
<div class="col-md-12">
<div class="itemDesc" title="Item Description" style="font-family: Arial; font-size: 22px;">
<?php echo $_POST["itemDescription"]; ?>
</div>
</div>
</div>

<hr />

<!-- Listing Info Bullets -->
<?php

if (!empty($_POST["localPickup"]) || !empty($_POST["listInfo1"]) || !empty($_POST["listInfo2"]))
{
?>
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading panel-heading-custom" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serifl; background: <?php echo $_POST['headerColor']; ?>; color: #fff; font-size: 24px; font-weight: bold;">LISTING INFORMATION</div>
<div class="panel-body">
<?php
if ($_POST['localPickup'] == "1") {
if(!empty($_POST['itemLocation'])) {        
echo '<li><span style="color: darkred; font-weight: bold;">This item is available for local pickup near ' . $_POST['itemLocation'] . '.</span></li>';
} else {
echo '<li><span style="color: darkred; font-weight: bold;">This item is available for local pickup.</span></li>';
}
}  
if(!empty($_POST['listInfo1'])) {
echo '<li><strong>' . $_POST['listInfo1'] . '</strong></li>';
} else {}
if(!empty($_POST['listInfo2'])) {
echo '<li><strong>' . $_POST['listInfo2'] . '</strong></li>';
} else {}
if(!empty($_POST['listInfo3'])) {
echo '<li><strong>' . $_POST['listInfo3'] . '</strong></li>';
} else {}
?>
</div>
</div>
</div>
</div>
<?php } ?>

<!-- Information Panels -->
<div class="row panel-container">
<div class="col col-sm-4">
<div class="panel panel-success">
<div class="panel-heading"><?php echo $config[0]['pricingTitle']; ?></div>
<div class="panel-body">
<?php echo $config[0]['pricing']; ?>
</div>
</div>
</div>
<div class="col col-sm-4">
<div class="panel panel-info">
<div class="panel-heading"><?php echo $config[0]['offerTitle']; ?></div>
<div class="panel-body">
<?php echo $config[0]['offers']; ?>
</div>
</div>
</div>
<div class="col col-sm-4">
<div class="panel panel-warning">
<div class="panel-heading"><?php echo $config[0]['shippingTitle']; ?></div>
<div class="panel-body">
<?php echo $config[0]['shipping']; ?>
</div>
</div>
</div>
</div>

<?php
if ($_POST['addInfo'] != '')
{ ?>
<!-- Additional Product Information Header-->
<div class="row">
<div class="col-md-12">
<div
style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; padding-left: 10px; height: 40px; line-height: 40px; background-color: <?php echo $_POST['headerColor']; ?>; color: white; font-size: 24px; font-weight: bold;">
ADDITIONAL INFORMATION
</div>
</div>
</div>

<!-- Additonal Product Info Text -->
<div class="row">
<div class="col-md-12">
<br />
<?php echo $_POST["addInfo"]; ?>
</div>
</div>

<br />
<br />
<?php 
}
?>
</div>

</textarea>

</div>
</div>
<br />
<br />

</div>

<!-- jQuery 3.6.0 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!-- Bootstrap 3.3.7 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
</script>

<!-- SummerNote 0.8.18 -->
<script src="summernote/summernote.min.js"></script>

<!-- Clipboard -->
<script src="js/clipboard.js"></script>

<script>
    $(document).ready(function () {
        var clipboard = new ClipboardJS('.btn')
    });
</script>

</body>

</html>