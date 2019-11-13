<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Activité 2</title>
</head>
<body>
	<?php
		$host = 'localhost';
		$db   = 'my_activities';
		$user = 'root';
		$pass = 'root';
		$charset = 'utf8mb4';
		$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
		$options = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
		];
		try {
			$pdo = new PDO($dsn, $user, $pass, $options);
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}
		$status = 'Active account';
		$lettre = '';		
		if (isset($_POST['status'])) {
			$status = $_POST['status'] ;
		}
		if (isset($_POST['lettre'])) {
			$lettre = $_POST['lettre'];
			
		}
	?>
	<form action="all_users.php" method="POST">
		Start with letter : <input type="text" name="lettre" id="lettre">
		and status is : <select name="status" id="status">
							<?php 
							echo $status;
								echo '<option value="Active account"';
								if ($status == 'Active account') {
									echo ' selected';
								}
								echo '>Active account</option>';
								echo '<option value="Waiting for account deletion"';
								if ($status == 'Waiting for account deletion') {
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
		$stmt = $pdo->query("SELECT U.id,U.username,U.email,S.name FROM users U
							JOIN status S ON S.id = U.status_id 
							WHERE U.username LIKE '$lettre%' AND S.name = '$status' ORDER BY username");
		while ($row = $stmt->fetch())
		{
			echo '<tr>';
 		   		echo '<td>'.$row['id'].'</td>';
 		   		echo '<td>'.$row['username'].'</td>';
 		   		echo '<td>'.$row['email'].'</td>';
 		   		echo '<td>'.$row['name'].'</td>';
 		   	echo '</tr>';
		}
	?>
	</table>
</body>
</html>