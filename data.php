<?php
  session_start();

  //de rest van deze code staat op lijn 40
  echo "Welkom<br>";
  echo "Je wordt zo naar de index gestuurd in 15 secondes<br>";

    if(isset($_SESSION["studentnr"]))
    {
        $now = time();

        if ($now > $_SESSION['expire'])
        {
          session_unset();
          session_destroy();
          header("location: index.php?fout=Je bent te lang inactief geweest");
        }
    }
    else
    {
        header("Location:index.php?fout=Je moet eerst inloggen voordat je data kan zien");
    }


    $data = array(
      $_SESSION['studentnr'],
      $_SESSION['typeMens'],
      $_SESSION['geslacht'],
      $_SESSION['emailadres'],
      $_SESSION['bio'],
      $_SESSION['datum']
    );

    foreach ($data as $value)
    {
      echo $value."<br>";
    }
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    echo $ip;



 ?>
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <meta http-equiv="refresh" content="15" >
     <title></title>
   </head>
   <body>

   </body>
 </html>
