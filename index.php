<?php
	session_start();

	$fout = $_GET['fout'];
	if ($fout != null) {
		echo "<h2>".$fout."</h2>";
	}

	$token = bin2hex(openssl_random_pseudo_bytes(32));
	$_SESSION['token'] = $token;

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
				<head>
					<title>Opdracht 1</title>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				</head>
				<body>
					<h1>Formulier</h1>
					<form name="" method="post" action="form_check_db.php">
						<label for="">
						<input type="hidden" name="csrf_token" value="<?php echo $token; ?>"/><br></label>

						<label for="">
						Studentnr:<input type="text" name="naam" value=""/><br></label>

						<label for="">
						Bio:<textarea rows="2" cols="40" name="bio"></textarea><br></label>

						<label for="">
						Emailadres:<input type="text" name="email" value="" /><br></label>

						<label for="">
						Type:<select name="type_mens" id="">
							<optgroup label="">
									<option value="Docent">Docent</option>
									<option value="Student">Student</option>
							</optgroup>
						</select><br></label>

						<label for="">
						Geslacht: M<input type="radio" name="geslacht" value="M" />F<input type="radio" name="geslacht" value="F" /><br></label>

						<label for="">
						Datum:<input type="date" name="date" /><br></label>
						<input type="submit" name="submit" value="Ga verder" />
					</form>
				</body>
			</html>
