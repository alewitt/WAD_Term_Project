<?php
require('/usr/local/www/term_project_creds.php');

try {

  $dbh = new PDO('mysql:host=localhost;dbname=TERM_PROJECT', $db_user, $db_pass);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $autoReset =  new PDO('mysql:host=localhost;dbname=TERM_PROJECT', $db_user, $db_pass);
  $autoReset->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if (isset($_FILES['uploaded_file'])) {

      $stmt = $autoReset->prepare('ALTER TABLE `Joshes` AUTO_INCREMENT=1');
      $stmt->execute();

      $display_name = $_POST['display_name'];
      $stmt = $dbh->prepare("INSERT INTO `TERM_PROJECT`.`Joshes` (`id`, `name`, `url`) VALUES (NULL, ?, ?);");
      $dbh->beginTransaction();
      $stmt->execute(array( $display_name, 'unknown'));



      $newId = $dbh->lastInsertId();
      $storage_name = $newId . '-' . $_FILES['uploaded_file']['name'];
      $stmt = $dbh->prepare("UPDATE `Joshes` SET `url`='pix/{$storage_name}' WHERE `id`='{$newId}'");

      if( $stmt->execute() ){

        if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], "pix/" . $storage_name)){
            echo json_encode(['result' => 'success', 'file' => $display_name]);
        } else {
            echo json_encode(['result' => 'failed', 'file' => $display_name]);
        }

      } else {
        echo json_encode(['result' => 'failed', 'file' => $display_name]);
      }
      $dbh->commit();

      exit;
  }

} catch (PDOException $e) {
  echo 'ERROR ', $e->getMessage();
}
?>
