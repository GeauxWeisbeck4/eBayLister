<?php
/*
eBayLister - An eBay Listing Creator

eBayLister is a tool for creating quick and easy eBay listings. It's a WYSIWYG template editor that generates styled HTML code you paste into the eBay HTML listing editor. Listings can be stored in the database for later use or modification. This app contains PDO CRUD database functions and additional functionality for storing configuration parameters, changing styles, etc.

This app was designed to run locally, so it's not security friendly and has no sanitization of user inputs, hash/salt of passwords, or other safeguards in place. I offer it as a useful tool to speed up eBay listing creation and as an example project for anyone looking to learn more about how PHP/MySQL can be used together to create a simple database application.

eBayLister employs PHP, MariaDB, Javascript, Bootstrap, jQuery, and Summernote.

# Version History
v2021.04.24 - Initial Release

# Copyright

Copyright 2021 David Rodgers
https://github.com/caressofsteel/eBayLister

This project is distributed under the MIT License. Please see the included COPYRIGHT and LICENSE for more information.

*/

require "dbconnect.php";

// ID funcs
$last_id = getLastID();
$next_id = getNextID();
$auto_id = getAutoID();
$check_id = idExists(1);

// Get/Clear Field Data
if (!empty($_GET["id"])) {
    try {
        $statement = "SELECT * FROM listings where id=" . $_GET["id"];
        $pdo_statement = $pdo_conn->prepare($statement);
        $pdo_statement->execute();
        $result = $pdo_statement->fetch();
        $get_id = $_GET["id"];
        $saveAsNew = 0;
        $pickUp = $result["localPickup"];
        $created = $result["date"];  
        $lastModified = $result["lastModified"];  
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
} else {
    try {
        $statement = "SELECT * FROM listings where id=0";
        $pdo_statement = $pdo_conn->prepare($statement);
        $pdo_statement->execute();
        $result = $pdo_statement->fetch();
        $get_id = 0;      
        $pickUp = 0;
        $created = $result["date"];
        $lastModified = $result["lastModified"];  
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }        
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'meta.php' ?>
</head>

<body>    
    <div class="container">
        
        <div class="row">
            <div class="col-md-12" style="margin-top: 25px;">
                <div class="col-md-3">
                    <a href="index.php"><img src='images/eBayListerLogo.png'></a>
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


                <div style="margin-top: 45px;">
                    <form id="listform" action="preview.php" method="post">                

                        <div class="col-md-2">
                        <?php
                            // If we're editing an existing record
                            // get the passed id and set it in POST
                            if (!empty($_GET['id'])) { ?>
                                <div class="form-group">
                                    <input name="submit" class="btn btn-block btn-info" type="submit" value="Save New">
                                </div>
                            <?php } ?>
                        </div>                    
                        <div class="col-md-2">
                            <div class="form-group">
                                <a class="reset btn btn-block btn-danger" type="reset" value="Reset" href="index.php" role="button">Reset</a>
                            </div>                          
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input name="submit" class="btn btn-block btn-success" type="submit" value="Preview">
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <br />

                <!-- Title Row -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="itemTitle">Title <span class="itemID">(ID: 
                            <?php 
                                // If we're editing an existing record
                                // get the passed id and set it in POST
                                if (!empty($_GET['id'])) {
                                    echo $get_id;
                                } else {
                                    echo $auto_id;                              
                                }                            
                            ?>)</span>
                            </label>
                            <?php
                                // Add the listingID
                                if (!empty($_GET['id'])) { ?>
                                    <input id="listingID" name="listingID" value="<?php echo $_GET["id"]; ?>" type="hidden">
                            <?php } else { ?>                                    
                                    <input id="listingID" name="listingID" value="<?php echo $auto_id; ?>" type="hidden">
                            <?php } ?>
                            <input maxlength="80" class="form-control" type="text" name="itemTitle" id="itemTitle" value="<?php echo htmlspecialchars(stripslashes($result['itemTitle'])); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="titleColor">Title Color</label>
                                <input class="form-control" type="text" maxlength="6" size="6" name="titleColor"
                                    id="titleColor" value="<?php if (!empty($result['titleColor'])) { echo $result['titleColor'];} else { echo "DimGrey"; }?>">
                            </div>
                        </div>
                    </div>                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="headerColor">Header Color</label>
                                <input class="form-control" type="text" maxlength="6" size="6" name="headerColor"
                                    id="headerColor" value="<?php if (!empty($result['headerColor'])) { echo $result['headerColor'];} else { echo "DarkSeaGreen"; }?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div style="margin-top: 30px;" class="form-check">
                            <input name="saveListing" value="0" type="hidden">                            
                            <input class="form-check-input" type="checkbox" name="saveListing" id="saveListing">
                            <label class="form-check-label" for="flexCheckDefault">
                                <?php 
                                     if (!empty($_GET["id"])) {
                                        echo "Update Listing?";
                                     } else {
                                        echo "Save Listing?";
                                     }
                                ?>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Image Row -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="headImage">Header Image URL</label>
                            <input class="form-control" type="text" name="headImage" id="headImage"
                                placeholder="http://www.fillmurray.com/g/355/400.jpg" value="<?php echo $result['headImage']; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="headImageHeight">Image Height</label>
                            <input class="form-control" type="text" maxlength="4" size="6" name="headImageHeight"
                                id="headImageHeight" value="<?php echo $result['headImageHeight']; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="itemLocation">Local Pickup Location</label>
                            <input value="<?php echo stripslashes($result['itemLocation']); ?>" class="form-control" type="text" name="itemLocation" id="itemLocation">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div style="margin-top: 30px;" class="form-check">                            
                                <input name="localPickup" value="0" type="hidden">
                                <input class="form-check-input" <?php echo ($result["localPickup"]==1 ? 'checked' : '');?> type="checkbox" name="localPickup" id="localPickup">                                
                                <label class="form-check-label" for="flexCheckDefault">Offer Local Pickup?</label>
                            </div>
                        </div>
                    </div>
                </div>
               
               <!-- Description Row -->
                <div class="row">
                    <div class="col-md-12">
                        
                        <!-- Heading -->
                        <div class="form-group">
                            <label for="itemHeading">Heading</label>
                            <textarea class="summernote form-control" name="itemHeading" id="itemHeading" rows="1"
                                cols="80"><?php echo stripslashes($result['itemHeading']); ?></textarea>
                        </div>

                        <!-- Item Description -->
                        <div class="form-group">
                            <label for="itemDescription">Description</label>
                            <textarea class="summernote form-control" name="itemDescription" id="itemDescription" rows="8" cols="80"><?php echo stripslashes($result['itemDescription']); ?></textarea>
                        </div>

                        <!-- Highlight Bullets -->
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="listInfo1">Highlight Bullet</label>
                                        <input value="<?php echo stripslashes($result['listInfo1']); ?>" class="form-control" type="text" name="listInfo1" id="listInfo1">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="listInfo2">Highlight Bullet</label>
                                        <input value="<?php echo stripslashes($result['listInfo2']); ?>" class="form-control" type="text" name="listInfo2" id="listInfo2">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="listInfo3">Highlight Bullet</label>
                                        <input value="<?php echo stripslashes($result['listInfo3']); ?>" class="form-control" type="text" name="listInfo3" id="listInfo3">
                                    </div>
                                </div>
                            </div>                        
                        </div>

                        <!-- Additional Info -->
                        <div class="form-group">
                            <label for="addInfo">Additional Info</label>
                            <textarea class="summernote form-control" name="addInfo" id="addInfo" rows="5" cols="80"><?php echo stripslashes($result['addInfo']); ?></textarea>
                        </div>

                    </div>                   
                </div>

                <?php 
                if (!empty($_GET["id"])) { ?>
                <div class="row text-center">
                    <div class="col-md-12 timeCreated" style="margin-bottom: 25px;">
                        <span  class="timeCreated">
                            <?php echo "Listing Created: <span class='timeStamp'>" . $created . " </span>&nbsp; .:. &nbsp Last Updated: <span class='timeStamp'>" . $lastModified . "</span>"; ?>
                        </span>                        
                    </div>
                </div>
                <?php } else { ?>
                    <div class="row text-center">
                    <div class="col-md-12 timeCreated gitLink" style="margin-bottom: 25px;">
                        <span  class="timeCreated">
                            <?php echo "©2021 <a target='_blank' href='https://github.com/caressofsteel/eBayLister/'>Powered by eBayLister</a> .:. Licensed under the <a target='_blank' href='https://github.com/caressofsteel/eBayLister/blob/main/LICENSE.md'>MIT License</a>"?>
                        </span>                        
                    </div>
                </div>
                <?php } ?>                    
            </form>
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

    <!-- SummerNote 0.8.18 -->
    <script src="summernote/summernote.min.js"></script>

    <!-- Color Picker -->
    <script src="js/colorpicker.js"></script>

    <script>
        $(document).ready(function () {

            $('.summernote').summernote({           
                    lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                    fontSizes: ["8", "9", "10", "11", "12", "13", "14", "15", "16", "18", "20", "22", "24", "36", "48"],
                    toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['height', ['height']],
                    ['color', ['color']],
                    /* START MOD - Add buttons to toolbar - summernote.js (line 7724) */    
                    ['insert', ['picture']],
                    ['para', ['justifyCenter']],
                    ['para', ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull', 'indent', 'outdent', 'ul', 'ol']],
                    /* END MOD - Add buttons to toolbar - summernote.js (line 7724) */    
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                    ],
                });
            });

            $('#headerColor, #titleColor').ColorPicker({
                onSubmit: function (hsb, hex, rgb, el) {
                    $(el).val('#' + hex);
                    $(el).ColorPickerHide();
                },
                onBeforeShow: function () {
                    $(this).ColorPickerSetColor(this.value);
                }
            }).bind('keyup', function () {

                $(this).ColorPickerSetColor(this.value);
        });
    </script>

</body>

</html>