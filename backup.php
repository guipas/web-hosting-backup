<html>
<head>
    <meta charset="UTF-8"> 
   <title></title>
</head>
<body>

<?php


   error_reporting(E_ALL);

   //wordpress ?

   if (file_exists("wp-config.php")) {
      echo "Using wordpress database connexion config... <br/>";
      include_once("wp-config.php");
      
      if (defined("DB_NAME")) {
        $db_server         = DB_HOST;
        $db_name           = DB_NAME;
        $db_username       = DB_USER;
        $db_password       = DB_PASSWORD;
      }
   }
    else if (file_exists("config/settings.inc.php")) {//prestashop ?
      echo "Using prestashop database connexion config... <br/>";

      include_once("config/settings.inc.php");
      if(defined("_DB_NAME_")) {
        $db_server         = _DB_SERVER_;
        $db_name           = _DB_NAME_;
        $db_username       = _DB_USER_;
        $db_password       = _DB_PASSWD_;
      }
   }
   else {
      die("No database connexion detected ! ");
      
      $db_server         = "localhost"; // ex. mysql5-26.perso
      $db_name           = "";
      $db_username       = "";
      $db_password       = "";
   }


   $db_charset = "utf8";
   $cmd_mysql = "mysqldump";
   
   $date = date("Ymd");
   $heure = date("His");
   
   $archive_GZIP = "backup_" . $date . "_". $heure . "_" . $db_charset . ".sql.gz";

   echo "Generating database dump : <b>$archive_GZIP</b> <br/>";

  
   $commande = $cmd_mysql." --host=$db_server --user=$db_username --password=$db_password -C -Q -e --default-character-set=$db_charset  $db_name    | gzip -c > $archive_GZIP ";
   //echo 'Commande : ' . $commande . '<br/>';

   $CR_exec = system($commande);

   if (file_exists($archive_GZIP))
      {
      $size = filesize($archive_GZIP);
      echo "Database dump file : &nbsp; $archive_GZIP &nbsp; - size :".$size." KB <br/>";
      }
   else echo "Error : dump not created <br/>";

   /* web hosting files backup */

   echo "Backing up files... <br/>";

   $tar_file = 'backup_file_' . $date . '_' . $heure . '.tar';
   $commande_tar = 'tar -cf ./' . $tar_file . ' .';
   //echo 'Commande : ' . $commande_tar . '<br/>';

   $CR_exec2 = system($commande_tar);

   if (file_exists($tar_file))
      {
      $Taille_Sauve = filesize($tar_file);
      echo "Files saved in : $tar_file &nbsp; - size :".$Taille_Sauve." Ko <br/>";
      }
   else echo "Error : backup not created <br/>";

   echo "End of script <br/>";
   echo "Merci, bon-week-end, au revoir. <br/>";
?>
</body>
</html>

