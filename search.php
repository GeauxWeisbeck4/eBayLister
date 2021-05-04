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

define("ROW_PER_PAGE",10);
require "dbconnect.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include 'meta.php' ?>

	<!-- Custom Styles -->
	<style>

		/* Elements */
		.title {
			font-size: 24px;
		}
		
		/* Tables */
		.tbl-qa {
			width: 100%;
			font-size: 0.9em;
			background-color: #f5f5f5;
		}

		.tbl-qa th.table-header {
			padding: 5px;
			text-align: left;
			padding: 10px;
		}

		.tbl-qa .table-row td {
			padding: 10px;
			background-color: #FDFDFD;
			vertical-align: top;
		}

		/* Pagination Buttons */
		.button_link {
			color: #FFF;
			text-decoration: none;
			background-color: #428a8e;
			padding: 10px;
		}
		.btn-page{
			margin-right:10px;
			padding:5px 10px; 
			border: #CCC 1px solid; 
			background:#FFF; 
			border-radius:4px;
			cursor:pointer;
		}	
		.btn-page:hover{background:#F0F0F0;}
		.btn-page.current{background:#F0F0F0;}
	</style>
</head>

<body>
<?php
	$search_keyword = '';
	if(!empty($_POST['search']['keyword'])) {
		$search_keyword = $_POST['search']['keyword'];
	}
	
	$sql = 'SELECT * FROM listings WHERE itemTitle LIKE :keyword OR itemDescription LIKE :keyword ORDER BY id DESC ';
	
	/* Pagination Code starts */
	$per_page_html = '';
	$page = 1;
	$start=0;

	if(!empty($_POST["page"])) {
		$page = $_POST["page"];
		$start=($page-1) * ROW_PER_PAGE;
	}

	$limit=" limit " . $start . "," . ROW_PER_PAGE;
	$pagination_statement = $pdo_conn->prepare($sql);
	$pagination_statement->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
	$pagination_statement->execute();

	$row_count = $pagination_statement->rowCount();
	if(!empty($row_count)){
		$per_page_html .= "<div style='text-align:center;margin:20px 0px;'>";
		$page_count=ceil($row_count/ROW_PER_PAGE);
		if($page_count>1) {
			for($i=1;$i<=$page_count;$i++){
				if($i==$page){
					$per_page_html .= '<input type="submit" name="page" value="' . $i . '" class="btn-page current" />';
				} else {
					$per_page_html .= '<input type="submit" name="page" value="' . $i . '" class="btn-page" />';
				}
			}
		}
		$per_page_html .= "</div>";
	}
	
	$query = $sql.$limit;
	$pdo_statement = $pdo_conn->prepare($query);
	$pdo_statement->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
	$pdo_statement->execute();
	$result = $pdo_statement->fetchAll();
?>
	<div class="container">

		<div class="row">
			<div class="col-md-12" style="margin-top: 25px;">
				<div class="col-md-3">
					<a href="index.php"><img src="images/eBayListerLogo.png"></a>
				</div>
				<div class="col-md-3" style="margin-top: 40px;">
					<a href="index.php"><i class="headerMenuIcon fa fa-home fa-lg" title="Home"></i></a>
					<a href="editor.php"><i style="color:black;" class="headerMenuIcon fa fa-file-code-o fa-lg" title="Editor"></i></a>
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
				<span class="title">Find Listing</span>
			</div>
		</div>

		<form name='frmSearch' action='' method='post'>
			<div class="row">			
				<div class="col-md-3">
					<input class="form-control" type='text' name='search[keyword]'
						value="<?php echo $search_keyword; ?>" id='keyword' maxlength='25'>
				</div>
				<div class="col-md-3">
					<input type="submit" class="btn btn-success" name="submit" value="Search">
					<br />
					<br />
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<table class="tbl-qa">
						<thead>
							<tr>
								<th class="table-header" style="width: 5%">id</th>
								<th class="table-header" style="width: 15%">Date</th>
								<th class="table-header" style="width: 15%">Modified</th>
								<th class="table-header" style="width: 55%">Title</th>
								<th class="table-header" style="width: 10%">Action</th>
							</tr>
						</thead>
						<tbody id="table-body">
							<?php
                            if(!empty($result)) { 
                                foreach($result as $row) {
                            ?>
							<tr class="table-row">
								<td><?php echo $row["id"]; ?></td>
								<td><?php echo $row["date"]; ?></td>
								<td><?php echo $row["lastModified"]; ?></td>
								<td><?php echo stripslashes($row["itemTitle"]); ?></td>
								<td>
								<form id="previewForm" action="preview.php" method="post">  
									<a class="ajax-action-links" href='preview.php?id=<?php echo $row['id']; ?>'><i class="fa fa-eye fa-2x" title="Preview"></i></a>
									&nbsp;
									<a class="ajax-action-links" href='index.php?id=<?php echo $row['id']; ?>'><i class="fa fa-edit fa-2x" title="Edit"></i></a>
									&nbsp;
									<a onClick="return confirm('Are you sure you want to delete this?')"
									class="ajax-action-links" href='delete.php?id=<?php echo $row['id']; ?>'><i class="fa fa-trash-o fa-2x" title="Delete"></i></a>
								</form>
								</td>
							</tr>
							<?php
                                }
                            }
                        ?>
						</tbody>
					</table>
					<?php echo $per_page_html; ?>
		</form>
	
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
	
</body>

</html>