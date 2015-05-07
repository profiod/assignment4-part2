<?php 
  ini_set('display_errors',1);  
  error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <link rel="stylesheet" href="video_inventory.css">
</head>
<body>
  <?php
    if($_POST["action"] == "printAll") {

      $mysqli = new mysqli("oniddb.cws.oregonstate.edu","profiod-db","YdvEBCHZy4pJfAwO","profiod-db"); 
      if (!$mysqli || $mysqli->connect_errno) 
        echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error ;

        if ($_POST["cat"] == "All Movies") {

        $results = $mysqli->query("SELECT id, name, category, length, rented FROM video_store ORDER BY id");

        if (!$results) 
          echo "Error with query: " . $mysqli->error;
        

        echo "<table>
          <caption>Current Inventory</caption>
          <thead>
            <tr>
              <th>Checkout/Return</th>
              <th>Name</th>
              <th>Category</th>
              <th>Length</th>
              <th>Status</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>"; 

        while ($row = $results->fetch_assoc()) {
          if($row['rented'] == 0)
            $status = "Checked In";
          else
            $status = "Checked Out"; 
          echo "<tr id='". $row["id"] . "'>
            <td><button name='Checkout' onclick='checkoutVid(this)'>X</button></td>
            <td>" . $row["name"] . "</td>
            <td>" . $row["category"] . "</td>
            <td>" . $row["length"] . "</td>
            <td>" . $status . "</td>
            <td><button name='Delete' onclick='deleteVid(this)'>X</button></td>
          </tr>"; 
        }

        echo "</tbody>
          </table>"; 

        $mysqli->close(); 
      }
      else {
        $cat = $_POST['cat'];

        $mysqli = new mysqli("oniddb.cws.oregonstate.edu","profiod-db","YdvEBCHZy4pJfAwO","profiod-db"); 
        if (!$mysqli || $mysqli->connect_errno) 
          echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error ;

        $stmt = $mysqli->prepare("SELECT id, name, category, length, rented FROM video_store WHERE category = ? ORDER BY id");

        $stmt->bind_param('s', $cat); 

        $stmt->execute(); 

        if (!$stmt) 
          echo "Error with query: " . $stmt->error;

        $stmt->bind_result($id, $name, $category, $length, $rented); 

        echo "<table>
          <caption>Current Inventory</caption>
          <thead>
            <tr>
              <th>Checkout/Return</th>
              <th>Name</th>
              <th>Category</th>
              <th>Length</th>
              <th>Status</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>"; 

        while ($stmt->fetch()) {
          if ($rented == 0) 
            $status = "Checked In"; 
          else 
            $status = "Checked Out"; 
          echo "<tr id='". $id . "'>
            <td><button name='Checkout' onclick='checkoutVid(this)'>X</button></td>
            <td>" . $name . "</td>
            <td>" . $category . "</td>
            <td>" . $length . "</td>
            <td>" . $status . "</td>
            <td><button name='Delete' onclick='deleteVid(this)'>X</button></td>
          </tr>"; 
        }

        echo "</tbody>
          </table>"; 

        $stmt->close(); 

        $mysqli->close(); 
      }
    }
    else if ($_POST["action"] == "deleteAll") {
      $mysqli = new mysqli("oniddb.cws.oregonstate.edu","profiod-db","YdvEBCHZy4pJfAwO","profiod-db"); 
      if (!$mysqli || $mysqli->connect_errno) 
        echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error ;

      $results = $mysqli->query("DELETE FROM video_store");

      if (!$results) 
        echo "Error with query: " . $mysqli->error;
      
      $mysqli->close(); 
      
    }
    else if ($_POST["action"] == "deleteId") {
      $id = $_POST["id"]; 

      $mysqli = new mysqli("oniddb.cws.oregonstate.edu","profiod-db","YdvEBCHZy4pJfAwO","profiod-db"); 
      if (!$mysqli || $mysqli->connect_errno) 
        echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error ;

      $stmt = $mysqli->prepare("DELETE FROM video_store WHERE id = ?");

      $stmt->bind_param('i',$id); 

      $stmt->execute(); 

      if (!$stmt) 
        echo "Error with query: " . $stmt->error;

      $stmt->close(); 

      $mysqli->close(); 
    }
    else if ($_POST["action"] == "checkoutVid") {
      $id = $_POST["id"]; 

      $mysqli = new mysqli("oniddb.cws.oregonstate.edu","profiod-db","YdvEBCHZy4pJfAwO","profiod-db"); 
      if (!$mysqli || $mysqli->connect_errno) 
        echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error ;

      $stmt = $mysqli->prepare("UPDATE video_store SET rented = 1 - rented WHERE id = ?");

      $stmt->bind_param('i',$id); 

      $stmt->execute(); 

      if (!$stmt) 
        echo "Error with query: " . $stmt->error;

      $stmt->close(); 

      $mysqli->close(); 
    }
    else if($_POST["action"] == "deleteAll") {
      $mysqli = new mysqli("oniddb.cws.oregonstate.edu","profiod-db","YdvEBCHZy4pJfAwO","profiod-db"); 
      if (!$mysqli || $mysqli->connect_errno) 
        echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error ;

      $results = $mysqli->query("DELETE FROM video_store");

      if (!$results) 
        echo "Error with query: " . $mysqli->error;

      $mysqli->close(); 
    }
    else if($_POST["action"] == "printCategories") {
      $mysqli = new mysqli("oniddb.cws.oregonstate.edu","profiod-db","YdvEBCHZy4pJfAwO","profiod-db"); 

      if (!$mysqli || $mysqli->connect_errno) 
        echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error ;

      $results = $mysqli->query("SELECT DISTINCT category FROM video_store ORDER BY id");

      if (!$results) 
        echo "Error with query: " . $mysqli->error;

      echo "<select id='Category'>
        <option value='All Movies'>All Movies</option>";

      while ($row = $results->fetch_assoc()) {
        if(!empty($row['category']))
          echo "<option value=" . $row['category'] . ">" . $row['category']  . "</option>"; 
      }

      echo "</select>";
      echo "<button name='Filter' onclick='filterCategory()'>Filter</button>";
    }
    elseif ($_POST["action"] == "addVideo") {
      $name = $_POST['name_input'];
      $cat = $_POST['category_input'];
      $len = $_POST['length_input'];

      $mysqli = new mysqli("oniddb.cws.oregonstate.edu","profiod-db","YdvEBCHZy4pJfAwO","profiod-db"); 
      if (!$mysqli || $mysqli->connect_errno) 
        echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error ;

      $stmt = $mysqli->prepare("INSERT INTO video_store (name, category, length) VALUES (?, ?, ?)");

      $stmt->bind_param('ssi', $name, $cat, $len); 

      $stmt->execute(); 

      if (!$stmt) 
        echo "Error with query: " . $stmt->error;

      $stmt->close(); 

      $mysqli->close(); 
    }
  ?>
</body>
</html>