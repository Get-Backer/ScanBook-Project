<?php
  $isbn = $_POST['isbn'];
  $author = $_POST['author_name'];
  $title = $_POST['title'];
  $page_count = $_POST['page_count'];
  $imageUrl = $_POST['url'];
  $readStat = $_POST['read_val'];
  $notes = $_POST['notes'];
  $check = $_POST['check'];

  $user = 'root';
  $pass = '';
  $db = 'scanbook';
  $conn = new mysqli('localhost', $user, $pass, $db);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $stat = 0;
  //database query to select columns
  if($check == 0){
    $sql ="INSERT INTO book_info (isbn, title, author, page_count, read_status, notes, img_url) VALUES ('$isbn', '$title', '$author', '$page_count', '$readStat', '$notes', '$imageUrl')";
  }else{
    $sql ="UPDATE book_info SET read_status='$readStat', notes='$notes' WHERE isbn='$isbn'";
    $stat = 1;
  }

  if($conn->query($sql) == true){
    if($stat == 0){
      echo '<p>Book has been addedd to the list!</p>';
    }else{
      echo '<p>Book information updated successfully!</p>';
    }
  }else{
    echo 'error!!!! '. $conn->error;
    echo ' '.$notes;
  }
  $conn->close();
?>
