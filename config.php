<?php
/*
# ------------------------------------------------------
# eBayLister - An eBay Listing Creator
# Copyright © 2021 David Rodgers
# Released under the terms of the MIT License
# ------------------------------------------------------
*/

require "dbconnect.php";

// Save Data
if(!empty($_POST["save_record"])) {
    $pdo_statement = $pdo_conn->prepare("UPDATE config SET pricing='" . addslashes($_POST['pricing']) . "', offers='" .  addslashes($_POST['offers'])  . "', shipping='" .  addslashes($_POST['shipping']) . "', pricingTitle='" .  addslashes($_POST['pricingTitle']) . "', offerTitle='" .  addslashes($_POST['offerTitle']) . "', shippingTitle='" .  addslashes($_POST['shippingTitle']) . "' where id=1");
	$result = $pdo_statement->execute();
	if($result) {
		//header('location:config.php');
	}
}

// Load Data
$pdo_statement = $pdo_conn->prepare("SELECT * FROM config where id=1");
$pdo_statement->execute();
$result = $pdo_statement->fetchAll();

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
                    <a href="index.php"><img src="images/eBayListerLogo.png"></a>
				</div>
				<div class="col-md-3" style="margin-top: 40px;">
					<a href="index.php"><i class="headerMenuIcon fa fa-home fa-lg" title="Home"></i></a>
					<a href="editor.php"><i style="color:black;" class="headerMenuIcon fa fa-file-code-o fa-lg" title="Editor"></i></a>
					<a href="search.php"><i class="headerMenuIcon fa fa-search fa-lg" title="Search"></i></a>
					<?php
						if (testConnect() == FALSE) {
							echo "<i style='color:red;' class='headerMenuIcon fa fa-database fa-lg' title='Database Connection Error!'></i>";
						}
					?>
				</div>
				<div class="col-md-6"></div>
			</div>
		</div>

        <form name="frmConfig" action="" method="POST">                
        <br />
        
        <div class="row">

            <!-- Pricing -->
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class = "panel-heading text-center panel-heading-forest">Pricing</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">                                
                                        <input style="width: 208px;" maxlength="27" class="form-control" type="text" name="pricingTitle" id="pricingTitle" placeholder="Pricing" value="<?php echo $result[0]['pricingTitle']; ?>">                    
                                        <br />
                                        <textarea class="form-control" name="pricing" id="pricing"><?php echo $result[0]['pricing']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            <!-- Special Offers -->
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class = "panel-heading text-center panel-heading-ocean">Special Offers</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input style="width: 208px;" maxlength="27" class="form-control" type="text" name="offerTitle" id="offerTitle" placeholder="Offers" value="<?php echo $result[0]['offerTitle']; ?>">
                                        <br />
                                        <textarea class="form-control" name="offers" id="offers" rows="8"
                                    cols="80"><?php echo $result[0]['offers']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            <!-- Shipping -->
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class = "panel-heading text-center panel-heading-sun">Shipping</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input style="width: 208px;" maxlength="27" class="form-control" type="text" name="shippingTitle" id="shippingTitle" placeholder="Shipping" value="<?php echo $result[0]['shippingTitle']; ?>">
                                        <br />
                                        <textarea class="form-control" name="shipping" id="shipping" rows="5"
                                    cols="80"><?php echo $result[0]['shipping']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

        </div>

        <div class="row text-center">
            <div class="col-md-5"></div>
            <div class="col-md-2">
                <div class="form-group">
                    <input class="btn btn-block btn-success" id="save_record" name="save_record" type="submit" value="Save">
                    </form>
                </div>
            </div>
            <div class="col-md-5"></div>
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

    <script>
        $(document).ready(function () {
            $('#offers').summernote();
            $('#pricing').summernote();
            $('#shipping').summernote();
        });
    </script>

</body>

</html>