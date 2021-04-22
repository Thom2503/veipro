<?php
		session_start();

		// waar is de log file?
		$log_file = "./errors.log";

		//de datum van nu zodat het bij gehouden kan worden in het log
		$date = date('m/d/Y h:i:s a', time());

		require 'db.php';

		//eem functie om de date te checken
		//van een stackoverflow post gevonden, precies wat ik nodig had.
		//https://stackoverflow.com/questions/13194322/php-regex-to-check-date-is-in-yyyy-mm-dd-format
		function validateDate($date, $format = 'Y-m-d H:i:s')
		{
		    $d = DateTime::createFromFormat($format, $date);
		    return $d && $d->format($format) == $date;
		}

		function uuidv4()
		{
			$data = openssl_random_pseudo_bytes(16);

			$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
			$data[8] = chr(ord($data[8]) & 0x3f | 0x80);

			return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
		}

		if (isset($_SESSION['token']) && $_SESSION['token'] == $_POST['csrf_token'])
		{
			if (isset($_POST['submit']))
			{
				$studentnr = $_POST['naam'];
				$bio = htmlentities($_POST['bio'], ENT_QUOTES);
				$emailadres = $_POST['email'];
				$typeMens = $_POST['type_mens'];
				$geslacht = $_POST['geslacht'];
				$datum = $_POST['date'];

				$email_pattern = "/[0-9]{0,5}@glr\.nl/";
				$date_pattern = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";

				$types_schoolganger = ['Docent', 'Student'];
				$geslachten = ['M', 'F'];

				//create the uuidv4
				$id = uuidv4();

				if (!empty($studentnr) &&
					!empty($bio) &&
					!empty($emailadres) &&
					!empty($typeMens) &&
					!empty($geslacht) &&
					!empty($datum))
				{
					if (filter_var($studentnr, FILTER_VALIDATE_INT))
					{
						if (preg_match($email_pattern, $emailadres) == 1)
						{
							if (in_array($typeMens, $types_schoolganger))
							{
								if (in_array($geslacht, $geslachten))
								{
									if (validateDate($datum, 'Y-m-d'))
									{
										$sql_id = "SELECT * FROM hoofdstuk3 WHERE ID = '$id'";
										$res_id = mysqli_query($mysql, $sql_id);

										if (mysqli_num_rows($res_id) > 0) {
											header('location: index.php?fout=UUID word al gebruikt, sorry voor het ongemak.');
											exit;
										} else
										{
											$stmt = mysqli_prepare($mysql, 'INSERT INTO `hoofdstuk3`
											(`ID`, `Studentnr`, `Bio`, `Email`, `Type`, `Geslacht`, `Datum`)
											VALUES (?, ?, ?, ?, ?, ?, ?)');

											mysqli_stmt_bind_param($stmt, 'sisssss', $id, $studentnr, $bio, $emailadres, $typeMens, $geslacht, $datum);

											mysqli_stmt_execute($stmt);

											$result = mysqli_stmt_get_result($stmt);

											//try catch systeem
											try
											{
												if (!$result)
												{
													//was om de oude data te laten zien
													?>
														<h1><?php //echo $studentnr; ?></h1>
														<em><?php //echo $typeMens." "; ?></em><em><?php //echo $geslacht; ?></em>
														<p><?php //echo $emailadres; ?></p>
														<p><?php //echo $bio ?></p>
														<p><?php //echo $datum; ?></p>
													<?php
													$_SESSION['studentnr'] = $studentnr;
													$_SESSION['typeMens'] = $typeMens;
													$_SESSION['geslacht'] = $geslacht;
													$_SESSION['emailadres'] = $emailadres;
													$_SESSION['bio'] = $bio;
													$_SESSION['datum'] = $datum;

													$_SESSION['start'] = time(); // Taking now logged in time.
							            $_SESSION['expire'] = $_SESSION['start'] + (1 * 10);

													header("location: data.php");
												} else
												{
													// error message om er in te zetten
													$error_message = $date . " Fout met het verbinden met de database\n";
													// de daadwerkelijke error in het log file zetten
													error_log($error_message, 3, $log_file);
													throw new Exception('Sorry voor het ongemak er kan momenteel geen verbinding met de database gemaakt worden.');
												}

											} catch (Exception $e)
											{
												echo $e->getMessage();
											}

											//oude systeem
											//if ($result)
											//{
												//// mocht het resultaat toch niet goed gaan
												//header('location: index.php?fout=Er is iets fout gegaan met de resultaat van de DB!');
												//exit;
											//} else
											//{
												//mysqli_stmt_close($stmt);

											//}
										}
									} else
									{
										header('location: index.php?fout=Datum klopt niet!');
										exit;
									}//als de datum niet klopt volgens de year, month and date format
								} else
								{
									header('location: index.php?fout=Geslacht klopt niet!');
									exit;
								}//geslacht check
							} else
							{
								header('location: index.php?fout=Type klopt niet!');
								exit;
							}//type schoolganger klopt niet
						} else
						{
							header('location: index.php?fout=email adres klopt niet!');
							exit;
						}//email adres regex
					} else
					{
						header('location: index.php?fout=studentnummer is geen int!');
						exit;
					}//checked of studentnr een integer is
				} else
				{
					header('location: index.php?fout=je hebt een of meer van de velden niet ingevoerd!');
					exit;
				}//kijkt of de velden wel zijn ingevoerd
			}
		} else
		{
			header('location: index.php?fout=csrf token klopt niet!');
			exit;
		}
	?>
