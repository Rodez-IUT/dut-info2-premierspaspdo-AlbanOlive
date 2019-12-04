<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
	<title>Activité 2</title>
	<?php
		spl_autoload_extensions(".php");
		spl_autoload_register();

		use yasmf\HttpHelper;
	?>
</head>
<body>
	<form action="index.php" method="GET">
		Start with letter : <input type="text" name="lettre" id="lettre">
		and status is : <select name="status" id="status">
							<?php
								echo '<option value="waitValid"';
								if ($status_id == 1) {
									echo ' selected';
								}
								echo '>Waiting for account validation</option>';
								echo '<option value="active"';
								if ($status_id == 2) {
									echo ' selected';
								}
								echo '>Active account</option>';
								
								echo '<option value="waitDel"';
								if ($status_id == 3) {
									echo ' selected';
								}
								echo '>Waiting for account deletion</option>';
							?>
						</select>
		<input type="submit" value="Ok">
	</form>
	<table>
		<thead>
			<td>Id</td>
			<td>Username</td>
			<td>Email</td>
			<td>Status</td>
		</thead>
	<?php		
		while ($row = $stmt->fetch())
		{
			echo '<tr>';
 		   		echo '<td>'.$row['id'].'</td>';
 		   		echo '<td>'.$row['username'].'</td>';
 		   		echo '<td>'.$row['email'].'</td>';
				echo '<td>'.$row['name'].'</td>';
				if ($status_id != 3) {
					echo '<td><a href="index.php?user_id='.$row['id'].'&status=waitDel&action=askDeletion">Ask deletion</a></td>';
				}	
 		   	echo '</tr>';
		}
	?>
	</table>
</body>
</html>