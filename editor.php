<?php
/*
# ------------------------------------------------------
# eBayLister - An eBay Listing Creator
# Copyright © 2021 David Rodgers
# Released under the terms of the MIT License
# ------------------------------------------------------
*/

require "dbconnect.php";
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
					<a href="index.php"><i class="headerMenuIcon fa fa-home fa-lg" title="Home"></i></a>
					<a href="search.php"><i class="headerMenuIcon fa fa-search fa-lg" title="Search"></i></a>
					<a href="config.php"><i style="color:black;" class="headerMenuIcon fa fa-cog fa-lg" title="Configuration"></i></a>
					<?php
						if (testConnect() == FALSE) {
							echo "<i style='color:red;' class='headerMenuIcon fa fa-database fa-lg' title='Database Connection Error!'></i>";
						}
					?>
				</div>
				<div class="col-md-6"></div>
			</div>
		</div>
        <div class="row">
            <div class="col-md-12">
                <br />
                <form action="processform.php" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="summernote form-control" name="editor" id="editor" rows="8" cols="80"></textarea>
                            </div>
                        </div>
                    </div>
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