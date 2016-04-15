<?php
require('/usr/local/www/term_project_creds.php');

try {
  // create DB connection
  $conn = new PDO('mysql:host=localhost;dbname=TERM_PROJECT', $db_user, $db_pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // init variables for referencial use
  $names = [];
  $urls = [];
  $pix_count = 0;

  //======== Helper functions =======================================
  // get total number of elements in the db.
  // unnecessary function, no longer using.
  function get_pix_count($conn){
    $stmt = $conn->prepare('SELECT COUNT(*) FROM Joshes');
    $stmt->execute();
    return $stmt->fetchColumn();
  }

  // fetch and store data in the initialized variables
  function get_all_data($conn, &$names, &$urls, &$pix_count){
    $stmt = $conn->prepare('SELECT * FROM Joshes');
    $stmt->execute();
    while($row = $stmt->fetch()){
      $names[] = $row['name'];
      $urls[] = $row['url'];
      $pix_count = count($names);
    }
  }
  //======================================================================= 


  //======== Ajax return for $_GET =======================================
  // when allMoods is set, either the page is loading for first time or
  // we just finished uploading new images. either way, we need to return
  // all data in the db.
  if(isset($_GET['allMoods'])){
    get_all_data($conn, $names, $urls, $pix_count);

    $reply = [
      'names' => $names,
      'urls' => $urls,
      'pix_count' => $pix_count
    ];

    echo json_encode($reply);
  }
  //=======================================================================


} catch (PDOException $e) {
  echo 'ERROR ', $e->getMessage();
}
