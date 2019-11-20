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
		$statusID = 2;
		$lettre = '%';
		$action = "";
		$userID = "";		
		if (isset($_GET['action'])) {
			$action = $_GET['action'];
		}
		if (isset($_GET['user_id'])) {
			$userID = $_GET['user_id'];
		}
		if (isset($_GET['status'])) {
			switch ($_GET['status']) {
				case 'waitValid':
					$statusID =  1;
					break;
				case 'active':
					$statusID = 2;
					break;
				case 'waitDel':
					$statusID =  3;
					break;
			}			
		}
		if (isset($_GET['lettre'])) {
			$lettre = $_GET['lettre'].'%';			
		}
	?>
	<form action="all_users.php" method="GET">
		Start with letter : <input type="text" name="lettre" id="lettre">
		and status is : <select name="status" id="status">
							<?php
								echo '<option value="waitValid"';
								if ($statusID == 1) {
									echo ' selected';
								}
								echo '>Waiting for account validation</option>';
								echo '<option value="active"';
								if ($statusID == 2) {
									echo ' selected';
								}
								echo '>Active account</option>';
								
								echo '<option value="waitDel"';
								if ($statusID == 3) {
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
		$stmt = $pdo->prepare("SELECT U.id,U.username,U.email,S.name FROM users U
							   JOIN status S ON S.id = U.status_id 
							   WHERE U.username LIKE ? AND U.status_id = ? ORDER BY username");
		$stmt->execute([$lettre,$statusID]);
		while ($row = $stmt->fetch())
		{
			echo '<tr>';
 		   		echo '<td>'.$row['id'].'</td>';
 		   		echo '<td>'.$row['username'].'</td>';
 		   		echo '<td>'.$row['email'].'</td>';
				echo '<td>'.$row['name'].'</td>';
				if ($statusID == 3) {
					echo '<td><a href="all_users.php?user_id='.$row['id'].'&status=waitDel&action=askDeletion">Ask deletion</a></td>';
				}	
 		   	echo '</tr>';
		}
	?>
	</table>
</body>
</html>