<?php
		session_start();

		function validateDate($date, $format = 'Y-m-d H:i:s')
		{
		    $d = DateTime::createFromFormat($format, $date);
		    return $d && $d->format($format) == $date;
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
										?>
											<h1><?php echo $studentnr; ?></h1>
											<em><?php echo $typeMens." "; ?></em><em><?php echo $geslacht; ?></em>
											<p><?php echo $emailadres; ?></p>
											<p><?php echo $bio ?></p>
											<p><?php echo $datum; ?></p>
										<?php
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
