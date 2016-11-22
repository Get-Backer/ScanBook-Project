<?php
  //function to validate weighted checksum
  function checkWeightedSum($isbn){
    $odd = 0;
    $even = 0;
    for($i = 0; $i<strlen($isbn)-1; $i++){
      if($i%2 == 0){
        $odd += $isbn[$i];
      }else{
        $even += $isbn[$i]*3;
      }
    }
    $sum = $odd + $even;
    $mod = $sum%10;
    $check = ($mod == 0) ? 0 : 10-$mod;
    if($check == substr($isbn, 12,12)){
      return 1;
    }else{
      return 0;
    }
  }

  $user = 'root';
  $pass = '';
  $db = 'scanbook';
  $isbn = '';
  $title = 'Not Available';
  $imageUrl = 'no_img.jpg';
  $authorName = 'Not Available';
  $pageCount = 'Not Available';
  $readStat = '0';
  $notes = '';
  //validate ISBN and parse json data
  if((empty($_POST['search']) != 1) && (strlen($_POST['search']) == 13) && (substr($_POST['search'], 0,1) == 9)){
    $isbn = $_POST['search'];
    if(checkWeightedSum($isbn) == 1){

      //create database connection
      $conn = new mysqli('localhost', $user, $pass, $db);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      //database query to select columns
      $sql ="SELECT title, author, page_count, read_status, notes, img_url FROM book_info WHERE isbn=".$isbn;
      $result = $conn->query($sql);
      $check = 0;

      if($result->num_rows != 0){
        while($row = $result->fetch_assoc()) {
          $title = $row['title'];
          $imageUrl = $row['img_url'];
          $authorName = $row['author'];
          $pageCount = $row['page_count'];
          $readStat = $row['read_status'];
          $notes = $row['notes'];
        }
        $check = 1;
      }else{
        $data = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn");
        $arr = json_decode($data, true);
        $title = $arr['items'][0]['volumeInfo']['title'];
        $imageUrl = $arr["items"][0]["volumeInfo"]["imageLinks"]["thumbnail"];
        $authorName = '';
        $list = $arr['items'][0]['volumeInfo']['authors'];
        for($i = 0; $i<count($list); $i++){
          if($i == (count($list)-1)){
            $authorName .= $list[$i];
          }else{
            $authorName .= $list[$i].', ';
          }
        }

        if(strpos(json_encode($arr), 'pageCount') != false){
          $pageCount = $arr['items'][0]['volumeInfo']['pageCount'];
        }
        $readStat = '0';
      }
      $conn->close();

      $radio = '<td><input type="radio" name="read_val" value="1"/>Read <input type="radio" name="read_val" value="0" checked="checked"/>Yet to Read</td>';
      if($readStat == '1'){
        $radio = '<td><input type="radio" name="read_val" value="1" checked="checked"/>I\'ve Read it <input type="radio" name="read_val" value="0"/>I\'m Yet to Read</td>';
      }

      $addUp = '<td><button type="submit" id="add_db">ADD BOOK</button>
                <button type="submit" id="update_db" disabled>UPDATE</button>
                <input type="hidden" name="check" value="'.$check.'" /></td>';
      if($check == 1){
        $addUp = '<td><button type="submit" id="add_db" disabled>ADD BOOK</button>
                  <button type="submit" id="update_db">UPDATE</button>
                  <input type="hidden" name="check" value="'.$check.'" /></td>';
      }

      //generating table with book data
      echo '<form id="tab_gen" method="post">
            <table width = "50%">
            <tr>
              <th colspan = 2>'.$title.' by '.$authorName.'</th>
            </tr>

            <tr>
              <td rowspan = 10><img src="'.$imageUrl.'" alt="Book Cover"/>'.'<br/>
              <input type="hidden" name="url" value="'.$imageUrl.'" /></td>
              <td><label>ISBN: </label>'.$isbn.'
              <input type="hidden" name="isbn" value="'.$isbn.'" /></td>
            </tr>
            <tr>
              <td><label>Title: </label>'.$title.'
              <input type="hidden" name="title" value="'.$title.'" /></td>
            </tr>
            <tr>
              <td><label>Author(s): </label>'.$authorName.'
              <input type="hidden" name="author_name" value="'.$authorName.'" /></td>
            </tr>
            <tr>
              <td><label>Page Count: </label>'.$pageCount.'
              <input type="hidden" name="page_count" value="'.$pageCount.'" /></td>
            </tr>
            <tr>
              '.$radio.'
            </tr>
            <tr>
              <td><label>Notes: </label><br/><textarea name="notes" rows="5" cols="60">'.$notes.'</textarea>
              </td>
            </tr>
            <tr>
              '.$addUp.'
            </tr>
            </table>
            </form>';
    }else{
      echo '<p>Oppss the ISBN you entered is not valid!</p>';
    }
  }else{
    if(empty($_POST['search']) == 1){
      echo '<p>Seems like you forgot to enter the ISBN!</p>';
    }elseif(substr($_POST['search'], 0,1) != 9){
      echo '<p>Your ISBN must start with 9!</p>';
    }elseif(strlen($_POST['search']) != 13){
      if(strlen($_POST['search']) > 13){
        echo '<p>Opps seems like you entered more than 13 digits!</p>';
      }else{
        echo '<p>Opps seems like you entered only'.strlen($_POST['search']).' digits!</p>';
      }
    }
  }
?>
