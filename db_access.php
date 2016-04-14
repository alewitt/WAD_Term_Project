<?php
require('/usr/local/www/hw2-creds.php');

try {

  $conn = new PDO('mysql:host=localhost;dbname=HW2', $db_user, $db_pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  function get_names($conn){
    $stmt = $conn->prepare('SELECT name FROM Joshes');
    $stmt->execute();
    $arr = [];
    while($row = $stmt->fetch()){
      $arr[] = $row['name'];
    }
    return $arr;
  }

  function get_urls($conn){
    $stmt = $conn->prepare('SELECT url FROM Joshes');
    $stmt->execute();
    $arr = [];
    while($row = $stmt->fetch()){
      $arr[] = $row['url'];
    }
    return $arr;
  }

  function get_pix_count($conn){
    $stmt = $conn->prepare('SELECT COUNT(*) FROM Joshes');
    $stmt->execute();
    return $stmt->fetchColumn();
  }

  $names = get_names($conn);
  $urls = get_urls($conn);
  $pix_count = get_pix_count($conn);

  function get_name($conn, $id, $pix_count, $names){
    if($id <= $pix_count-1){
      return $names[$id];
    } else {
      return 'No Match Found';
    }
  }

  function get_url($conn, $id, $pix_count, $urls){
    if($id <= $pix_count-1){
      return $urls[$id];
    } else {
      return 'No Match Found';
    }
  }


  if(isset($_GET['joshMood'])){
    $image = get_url($conn, $_GET['joshMood'], $pix_count, $urls);

    $reply = [
      'image' => $image,
    ];

    echo json_encode($reply);
  }

} catch (PDOException $e) {
  echo 'ERROR ', $e->getMessage();
}
