<!DOCTYPE html>
<html>

<?php
		require_once("conn.php");

		session_start();

		?>


	<head>
		<style type="text/css">

		form{

			margin: 3% auto;
				height: 650px;
				width: 1000px;
				border:5px solid;
				border-color: skyblue;
				font-family: century gothic;
				color:#2d6673;
				background: white;
				padding: 10px;
		}
		.header{

			width: auto;
			padding: 20px 0px 10px 5px;
			color: black;
			font-family: sans-serif;
			border: 3px solid;
			border-color: skyblue;
			border-top: 0px;
			border-right: 0px;
			border-left: 0px;
		}
		label{

			font-size: 40px;
		}
		table, tr, th{

			border-collapse: collapse;
		}
		.table{
			width: auto;
			height: 420px;
			margin: 10px 0px;
			overflow-x: hidden;
			overflow-y: scroll;
		}
		.table-row{
			padding: 5px;
		}
		.col-header{

			padding: 10px;
			border: 3px solid;
			border-top: 0px;
			border-bottom: 0px;
			background: #84b4d4;
			font-family: sans-serif;
			color: white;
		}
		.fullname{

			width: 300px;
		}
		.phone-number{

			width: 150px;
		}
		.email{

			width: 300px;
		}
		th{
			color: black;
			font-family: sans-serif;
			border: 1px solid skyblue;
			border-left: 0px;
			border-right: 0px;
		}

		.btn{

			padding: 5px 15px;
			border: 3px solid;
			border-color: skyblue;
			border-radius: 5px;
			margin-left: 5px;
			font-family: sans-serif;
		}
		.col-data{

			padding: 20px 15px;

		}
		.fname{

			text-align: left;
		}
		.number{
			text-align: right;
		}
		.mail{
			text-align: left;
		}
		.buttons{

			width: 200px;
		}
		input[type="text"]{

			padding: 5px 5px;
			height: 30px;
			border: 2px solid #b4b1b1;
			border-top: 0px;
			border-left: 0px;
			border-right: 0px;
			margin-top: 5px;
		}
		input[type="submit"]{

			padding: 10px 30px;
			float: right;
			margin-top: 15px;

		}
		.footer{
			padding: 5px;
			border:3px solid skyblue;
			border-bottom: 0px;
			border-left: 0px;
			border-right: 0px;
		}
		.add{
			padding: 10px 20px;
			background: #17425f;
			color: white;
			font-weight: bold;
		}
		.left-pnl{
			float: left;
			height: 460px;
			border:3px solid skyblue;
			padding: 20px;
			margin: 1% auto;
		}
		.left-pnl p{

			font-family: sans-serif;
			font-size: 20px;

		}
		.right-pnl{

			float: right;
			border: 3px solid skyblue;
			height: 500px;
			width: 770px;
			margin: 1% auto;
		}
		.btn-save{

			margin-top: 1px;
		}
		.btn-pnl{
			float: right;
			padding:10px;
		}
		.txt-search{
			margin-left: 250px;
		}



		</style>

		<title> Phone Directory </title>

	</head>


	<body>

		<form action="index.php" method="get">

		<?php

			if(isset($_GET['delUser'])){

				$userID = $_GET['delUser'];

				$sql = "CALL deleteData(:userID)";
				$cmd = $conn->prepare($sql);
				$cmd->bindParam("userID",$userID);
				$cmd->execute();

			} //this block of codes enables the user to delete a data from the database.


			if (isset($_GET['btnSave'])) {
			

				$firstName = $_GET['txtFname'];
				$middleName = $_GET['txtMname'];
				$lastName = $_GET['txtLname'];
				$phoneNumber = $_GET['txtPhoneNumber'];
				$email = $_GET['txtEmail'];


				$userID = $_SESSION['userID'];
				$checker = $_SESSION['checker'];

				if (empty($checker)) {

					$sql = "CALL saveData(:firstName, :middleName, :lastName, :phoneNumber, :emailAddress)";

					try {					
						$cmd = $conn->prepare($sql);
						$cmd->bindParam("firstName", $firstName);
						$cmd->bindParam("middleName", $middleName);
						$cmd->bindParam("lastName", $lastName);
						$cmd->bindParam("phoneNumber", $phoneNumber);
						$cmd->bindParam("emailAddress", $email);
						$cmd->execute();	

					} catch (Exception $e) {

						echo  $e->getMessage();

					}

					$saveChecker = 1;

				} // this block of codes enables the user to add a new data to the database.

				else{
					$sql = "CALL updateDATA(:userID, :firstName, :middleName, :lastName, :phoneNumber, :emailAddress)";

					try {

						$cmd = $conn->prepare($sql);
						$cmd->bindParam("userID",$userID);
						$cmd->bindParam("firstName", $firstName);
						$cmd->bindParam("middleName", $middleName);
						$cmd->bindParam("lastName", $lastName);
						$cmd->bindParam("phoneNumber", $phoneNumber);
						$cmd->bindParam("emailAddress", $email);
						$cmd->execute();	

					} catch (Exception $e) {

						echo  $e->getMessage();

					}

					$_SESSION['checker'] ='';
					$saveChecker = '';

				}// this block of codes enables the user to update an existing data from the database.			
			}
		?>

				
			<div class="mainForm">
					
				<div class="header">
						
					<label> Phone Directory<br> Management System </label>

						<input type="text" placeholder="Type here..." class="txt-search" name="txtSearch">
						<input type="submit" name="btnSearch" value="Search">

				</div>

				<div class="left-pnl">

					<p> Data Entry </p> <br>

					<?php

						$fname = "";
						$mname = "";
						$lname = "";
						$pnumber = "";
						$email = "";

						if (isset($_GET['userID'])) {

							$_SESSION['checker'] = $_GET['checker'];
							$_SESSION['userID'] = $_GET['userID'];


							$sql = "SELECT * FROM users WHERE userID=:userID";

							$cmd = $conn->prepare($sql);
							$cmd->bindParam('userID',$_GET['userID']);
							$cmd->execute();

							foreach ($cmd as $row) {

							$fname = $row['firstName'];
							$mname = $row['middleName'];
							$lname = $row['lastName'];
							$pnumber = $row['phoneNumber'];
							$email = $row['emailAddress'];

							}

						} // this block of codes is needed for updating a data from the database.
					?>
					
					<input type="text" name="txtFname" placeholder="Firstname" value="<?php echo $fname?>" ><br>
					<input type="text" name="txtMname" placeholder="Middlename" value="<?php echo $mname?>"><br>
					<input type="text" name="txtLname" placeholder="Lastname" value="<?php echo $lname?>" ><br>
					<input type="text" name="txtPhoneNumber" placeholder="PhoneNumber" value="<?php echo $pnumber?>" ><br>
					<input type="text" name="txtEmail" placeholder="Email" value="<?php echo $email?>"><br>
					<input type="submit" name="btnSave" value="Save" class="btn add">
				</div>

				<div class="right-pnl">

					<div class="table">
							
						<table>
							<tr class="table-row">
								<th class="col-header fullname"> Fullname </th>
								<th class="col-header phone-number"> Phone number</th>
								<th class="col-header email"> Email </th>
								<th class="col-header buttons"> Actions </th>
							</tr>

						<?php

							if(isset($_GET['loadData']) | isset($_GET['userID']) |  isset($_GET['delUser']) | isset($saveChecker)){

								$sql = "CALL getData";
								$cmd = $conn->prepare($sql);
								$cmd->execute();

								foreach ($cmd as $row) {

									echo '
										<tr class="table-row">
										<th class="col-data fname">'.$row["fullname"].'</th>
										<th class="col-data number">'.$row["phoneNumber"].'</th>
										<th class="col-data mail">'.$row["emailAddress"].'</th>
										<th class="col-data btns"> <a class="btn edit" href="?userID='.$row["userID"].'&checker=1">Edit</a><a class="btn del" href="?delUser='.$row["userID"].'">Delete</a></th>
										</tr>
									';
										
								}
							}

							if (isset($_GET['btnSearch'])){

								$saveChecker ='';

								$sql = 'SELECT * FROM vw_users WHERE CONCAT(fullname,phoneNumber,emailAddress) LIKE "%'.$_GET['txtSearch'].'%"';
								$cmd = $conn->prepare($sql);
								$cmd->execute();

									
								foreach ($cmd as $row) {

									echo '
										<tr class="table-row">
										<th class="col-data fname">'.$row["fullname"].'</th>
										<th class="col-data number">'.$row["phoneNumber"].'</th>
										<th class="col-data mail">'.$row["emailAddress"].'</th>
										<th class="col-data btns"> <a class="btn edit" href="?userID='.$row["userID"].'&checker=1">Edit</a><a class="btn del" href="?delUser='.$row["userID"].'">Delete</a></th>
										</tr>
									';
								}

							} // this block of codes enables the user to use the search engine and fetch the data from the database.
						?>	
						</table>


					</div>

					<div class="footer">
						<div class="btn-pnl">
							<a class="btn add" name="btnLoad" href="?loadData=1">Load Data</a>
						</div>		
					</div>
						
				</div>

			</form>

	</body>
</html>
