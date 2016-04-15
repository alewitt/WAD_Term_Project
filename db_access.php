<?php
require('/usr/local/www/term_project_creds.php');

try {

  $conn = new PDO('mysql:host=localhost;dbname=TERM_PROJECT', $db_user, $db_pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $names = [];
  $urls = [];
  $pix_count = 0;

  function get_pix_count($conn){
    $stmt = $conn->prepare('SELECT COUNT(*) FROM Joshes');
    $stmt->execute();
    return $stmt->fetchColumn();
  }

  function get_all_data($conn, &$names, &$urls, &$pix_count){
    $stmt = $conn->prepare('SELECT * FROM Joshes');
    $stmt->execute();
    while($row = $stmt->fetch()){
      $names[] = $row['name'];
      $urls[] = $row['url'];
      $pix_count = get_pix_count($conn);
    }
  }

  function get_name($conn, $id, &$names, &$urls, &$pix_count){
    get_all_data($conn, $names, $urls, $pix_count);
    if($id <= $pix_count){
      return $names[$id-1];
    } else {
      return 'No Match Found';
    }
  }

  function get_url($conn, $id, &$names, &$urls, &$pix_count){
    get_all_data($conn, $names, $urls, $pix_count);
    if($id <= $pix_count){
      return $urls[$id-1];
    } else {
      return 'No Match Found';
    }
  }


  if(isset($_GET['selectedImg'])){
    $image = get_url($conn, htmlspecialchars($_GET['selectedImg']), $pix_count, $urls);

    $reply = [
      'url' => $image,
    ];

    echo json_encode($reply);
  }

  if(isset($_GET['allMoods'])){
    get_all_data($conn, $names, $urls, $pix_count);

    $reply = [
      'names' => $names,
      'urls' => $urls,
      'pix_count' => $pix_count
    ];

    echo json_encode($reply);
  }

  /**
   * User: remi
   * Date: 17/01/15
   * Time: 11:41
   */
  if (isset($_FILES['uploaded_file'])) {
      get_all_data($conn, $names, $urls, $pix_count);

      $name = $pix_count + 1 . '-' . $_FILES['uploaded_file']['name'];
      if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], "pix/" . $name)){
          echo $name . " uploaded ...";
      } else {
          echo $name . " NOT uploaded ...";
      }

      exit;
  }

} catch (PDOException $e) {
  echo 'ERROR ', $e->getMessage();
}
