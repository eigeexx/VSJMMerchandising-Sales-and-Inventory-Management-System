<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Edit Supplier Information</title>
		<link href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round" rel="stylesheet">
		<link rel="stylesheet" href="supplier.css" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		
		<?php
			include_once '../../env/conn.php';
		?>

	</head>
	<body>
		<?php
			
			if(count($_POST)>0){
			
				$supplier_ID = $_POST['supplier_ID'];
				$item_ID = $_POST['item_ID'];
				$supplierItem_CostPrice = $_POST['transactionItems_CostPrice'];
				$transactionItems_TotalPrice = $_POST['transactionItems_Quantity']*$_POST['transactionItems_CostPrice'];
				
				mysqli_query($conn, "UPDATE supplier_transactions set transaction_ID='".$_SESSION['transaction_ID']."', supplier_ID='".$_POST['supplier_ID']."', transaction_Date='".$_POST['transaction_Date']."', transaction_Status='".$_POST['transaction_Status']."', transaction_TotalPrice='".$_POST['transaction_TotalPrice']."' WHERE transaction_ID = '". $_SESSION['transaction_ID']."'") or die( mysqli_error($conn));

				$message = "Supplier transaction record edited successfully. ";
				
				mysqli_query($conn, "UPDATE transaction_items set transaction_ID='".$_SESSION['transaction_ID']."', item_ID='".$_POST['item_ID']."',transactionItems_Quantity='".$_POST['transactionItems_Quantity'] ."',transactionItems_CostPrice='".$_POST['transactionItems_CostPrice']."',
				transactionItems_TotalPrice='".$transactionItems_TotalPrice."' 
				WHERE transaction_ID ='".$_SESSION['transaction_ID']."'") or die( mysqli_error($conn));
				
				$message = "Transaction item record edited successfully.";

				$nonempty=0;

				$sql = "SELECT * from supplier_item where supplier_ID =".$_POST['supplier_ID']." and item_ID =".$_POST['item_ID'];
					$result = $conn-> query($sql) or die($conn->error);
					if ($result-> num_rows >0) {
						$nonempty=1;
					}
				
				if($nonempty==0){
					$insert = mysqli_query($conn,"INSERT INTO supplier_item". "(supplier_ID, item_ID, supplierItem_CostPrice)"."VALUES('$supplier_ID', '$item_ID', '$supplierItem_CostPrice')");
					mysqli_query($conn, $insert);
				}
				if($nonempty==1){
					$update = mysqli_query($conn,"UPDATE supplier_item set supplierItem_CostPrice='".$supplierItem_CostPrice."' WHERE supplier_ID ='".$supplier_ID."' and item_ID ='".$item_ID."'");
					mysqli_query($conn, $update);
				}

				unset($_SESSION['transaction_ID']);
				mysqli_close($conn);

				header("Location: ./suppliertable.php?supplier_ID=".$supplier_ID);
				exit();
			}

			$result = mysqli_query($conn, "SELECT * from supplier_transactions INNER JOIN transaction_items on transaction_items.transaction_ID = supplier_transactions.transaction_ID where supplier_transactions.transaction_ID = '". $_GET['transaction_ID'] . "'") or die( mysqli_error($conn));
			$orig=mysqli_fetch_array($result);

		?>
		<div id ="transactionform">
			<form action = "./edittransaction.php" method="post">
			<h3>Fill the Form</h3>
			<?php  
				$_SESSION['transaction_ID'] = $_GET['transaction_ID'];
			?>

				<p>
					Supplier:
					<?php
						$query = "SELECT * from supplier";
							$result = mysqli_query($conn,$query);
							if(mysqli_num_rows($result) > 0){
								echo "<select name='supplier_ID'>";
									while($row = mysqli_fetch_assoc($result)){
										echo "<option value='".$row['supplier_ID']."'";

										if($row['supplier_ID']===$orig['supplier_ID']){
											echo " selected";
										}

										echo">".$row['supplier_Name']."</option>";										

									}
									echo "</select><br>";
							}
					?>
				</p>

				<p>
					Item:
					<?php
						$query = "SELECT * from item";
							$result = mysqli_query($conn,$query);
							if(mysqli_num_rows($result) > 0){
								echo "<select name='item_ID'>";
									while($row = mysqli_fetch_assoc($result)){
										echo "<option value='".$row['item_ID']."'";

										if($row['item_ID']==$orig['item_ID']){ echo " selected";}

										echo">".$row['item_ID']."</option>";
									}
									echo "</select><br>";
								}
					?>
				</p>
				<?php
					echo "<p>Item Quantity: <input type=\"text\" name=\"transactionItems_Quantity\" value='".$orig['transactionItems_Quantity']."'></p>
					<p>Item Cost Price: Php <input type=\"text\" name=\"transactionItems_CostPrice\" value='".$orig['transactionItems_CostPrice']."'></p>";

					echo "<p>Transaction Date: <input type=\"datetime-local\" name=\"transaction_Date\" value='".$orig['transaction_Date']."' /></p>";
					echo"<p>Transaction Status: <select name=\"transaction_Status\" id=\"transaction_Status\">
						<option value=\"1\""; 
						if($orig['transaction_Status']=="1"){
							echo " selected";
						}
					echo ">Successful</option>
						<option value=\"0\"";
						if($orig['transaction_Status']=="0"){
							echo " selected";
						}
					echo ">Failed</option>
					</select></p>";
					echo"<p>Transaction Total Price: Php <input type=\"text\" name=\"transaction_TotalPrice\" value='".$orig['transaction_TotalPrice']."'></p>";
				?>

				<input type="hidden" name="transactionItems_TotalPrice" id="transactionItems_TotalPrice" <?php echo"value='".$orig['transactionItems_TotalPrice']."'"; ?>Size="6" readonly>


		<div class="text-center">
		<!-- Button HTML (to Trigger Modal) -->
			<button type="button" class="trigger-btn" data-toggle="modal" data-target="#myModal">Update</button>
			<?php echo"<button onclick=\"location.href='suppliertable.php?supplier_ID=".$orig['supplier_ID']."'\">Back</button>"; ?>
		</div>

		<!-- Modal HTML -->
		<div id="myModal" class="modal fade">
		<div class="modal-dialog modal-confirm">
			<div class="modal-content">
				<div class="modal-header">
					<div class="icon-box">
						<i class="material-icons">&#xE5CD;</i>
					</div>				
					<h4 class="modal-title">Are you sure?</h4>	
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					<p>Do you really want to update these records? </p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
					<input type="submit" class="btn btn-danger" name="submit" value= "Update" type="submit">
				</div>
				</form>	
			</div>
		</div>
		</div>     
			</div>

	</body>

</html>