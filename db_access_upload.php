<?php
require('/usr/local/www/term_project_creds.php');

try {

  // create db connection for uploading
  $conn = new PDO('mysql:host=localhost;dbname=TERM_PROJECT', $db_user, $db_pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // create db connection to reset auto increment to 1. this is mainly due to
  // uploading and deleting everything for testing.
  $autoReset =  new PDO('mysql:host=localhost;dbname=TERM_PROJECT', $db_user, $db_pass);
  $autoReset->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



  //======== Ajax return for upload $_FILES =======================================
  // if there is a file to upload, start db access sequence
  if (isset($_FILES['uploaded_file'])) {
      // reset auto increment
      $stmt = $autoReset->prepare('ALTER TABLE `Joshes` AUTO_INCREMENT=1');
      $stmt->execute();

      // grab display name from post
      $display_name = htmlspecialchars($_POST['display_name']);
      // create sql statement for uploading the display name. the storage name is unknown until we
      // can access the id of the last insert
      $stmt = $conn->prepare("INSERT INTO `TERM_PROJECT`.`Joshes` (`id`, `name`, `url`) VALUES (NULL, ?, ?);");
      $conn->beginTransaction();
      // execute insertion of display name. if fails dont continue
      if(!$stmt->execute(array( $display_name, 'unknown'))){
        echo json_encode(['result' => 'failed', 'file' => $display_name]);
        exit;
      }

      // grab id from the display name we just inserted
      $newId = $conn->lastInsertId();
      // create the storage name using the id and the file name
      $storage_name = $newId . '-' . htmlspecialchars($_FILES['uploaded_file']['name']);
      // update table with new storage name where id==id
      $stmt = $conn->prepare("UPDATE `Joshes` SET `url`='pix/{$storage_name}' WHERE `id`='{$newId}'");

      // if success, try to move the image to proper location on server
      if( $stmt->execute() ){
        // if move is successful, send success reply
        if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], "pix/" . $storage_name)){
            echo json_encode(['result' => 'success', 'file' => $display_name]);
        } else {
            echo json_encode(['result' => 'failed', 'file' => $display_name]);
        }
      // else failed to upadte db with storage name
      } else {
        echo json_encode(['result' => 'failed', 'file' => $display_name]);
      }
      // finished executing uplaod. close pdo
      $conn->commit();

      exit;
  }
  //=======================================================================


} catch (PDOException $e) {
  echo 'ERROR ', $e->getMessage();
}
?>
