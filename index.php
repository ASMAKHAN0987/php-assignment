<?php
//connect to database
$insert = false;
$update = false;
$delete = false;
$servername = "localhost";
$username = "root";
$password = "";
$database       = "notes";
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
  // echo"failed to connection";
  die("sorry we couldn't connect to database" . mysqli_connect_error());
}
if (isset($_GET['delete'])) {
  //delete file
  $sno = $_GET['delete'];
  $sql = "DELETE FROM NOTES WHERE sno = $sno";
  $result = mysqli_query($conn, $sql);
  $delete = true;
}
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
  if (isset($_POST['snoEdit'])) {
    //update query
    $sno = $_POST['snoEdit'];
    $title = $_POST['titleEdit'];
    $descrpt = $_POST['descriptionEdit'];
    $SQL = "UPDATE `notes` SET `title` = '$title', `description` = '$descrpt' WHERE `notes`.`sno` = '$sno'";
    $result = mysqli_query($conn, $SQL);
    if ($result) {
      $update = true;
    }
  } else {
    //insert query
    $title = $_POST['title'];
    $descrpt = $_POST['description'];
    $SQL = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title','$descrpt')";
    $result = mysqli_query($conn, $SQL);
    if ($result) {
      $insert = 'true';
    } else {
      echo "cuz of this query could not inserted! " . mysqli_error($conn);
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css
    ">
  <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <title>iNotes notes taking made easy</title>
  <style>
    #con {
      background-color: blue;
      color: white;
      padding: 20px;
      text-align: center;
    }
    #cont2{
      background-color: orange;
      padding: 20px 0;
      /* margin-bottom: 50px; */
    }
    #cont3{
      background-color:beige;
      padding:20px;
    }
  </style>
</head>

<body>
  <!-- Edit Modal -->
  <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Edit Note</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/curd/index.php" method="post">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <h2>Add a note</h2>
            <div class="mb-3">
              <label for="title" class="form-label">Note Title</label>
              <input type="text" class="form-control" id="titleEdit" name="titleEdit">
            </div>
            <div class="form-floating mb-3">
              <textarea class="form-control" placeholder="Leave a comment here" id="descriptionEdit" name="descriptionEdit"></textarea>
              <label for="desc">Notes description</label>
            </div>

            <div>
              <button type="submit" class="btn btn-primary">update note</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <!-- <button type="button" class="btn btn-primary" >Save changes</button> -->
        </div>
      </div>
    </div>
  </div>
  <div id="con">
    <h2>todo list app</h2>
  </div>
  <!-- for insertion -->
  <?php
  if ($insert) {
    echo "<div class='alert alert-success   alert-dismissible fade show' role='alert'>
         <strong>success</strong> Your notes has been submitted successfully
         <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
         </div>";
  }
  ?>
  <!-- updation -->
  <?php
  if ($update) {
    echo "<div class='alert alert-success   alert-dismissible fade show' role='alert'>
         <strong>success</strong> Your notes has been updated successfully
         <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
         </div>";
  }
  ?>
  <!--  deletion  -->
  <?php
  if ($delete) {
    echo "<div class='alert alert-success   alert-dismissible fade show' role='alert'>
         <strong>success</strong> Your notes has been deleted successfully
         <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
         </div>";
  }
  ?>
  <div id="cont2">
  <div class="container my-5">
    <form action="/curd/index.php" method="post">
      <h2>Add a note</h2>
      <div class="mb-3">
        <label for="title" class="form-label">Note Title</label>
        <input type="text" class="form-control" id="title" name="title">
      </div>
      <div class="form-floating mb-3">
        <textarea class="form-control" placeholder="Leave a comment here" id="description" name="description"></textarea>
        <label for="description">Notes description</label>
      </div>

      <div>
        <button type="submit" class="btn btn-primary">Add note</button>
      </div>
    </form>
  </div>
  </div>
  <div id="cont3">
  <div class="container my-4">
    <?php
     //fetch the record
    $sql = "select * from notes";
    $result = mysqli_query($conn, $sql);
    ?>
    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">S.NO</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>  
        <?php
        $sql = "select * from notes";
        $result = mysqli_query($conn, $sql);
        $sno = 0;
        while ($row = mysqli_fetch_assoc($result)) {
          $sno = $sno + 1;
          echo "<tr>
                <th scope='row'>" . $sno . "</th>
                 <td>" . $row['title']  . "</td>
                 <td>" . $row['description']  . "</td>
                 <td><button class='edit btn btn-sm btn-primary' id=" . $row['sno'] . ">Edit</button>  <button class='delete btn btn-sm btn-primary' id=d" . $row['sno'] . ">Delete</button>
                </tr>";
        }

        ?>
      </tbody>
    </table>
  </div>
  </div>
  <hr>
  <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#myTable').DataTable();
    });
  </script>
  <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        tr = e.target.parentNode.parentNode;
        // console.log(tr);
        title = tr.getElementsByTagName('td')[0].innerText;
        description = tr.getElementsByTagName('td')[1].innerText;
        titleEdit.value = title;
        descriptionEdit.value = description;
        snoEdit.value = e.target.id;
        const myModal = new bootstrap.Modal('#editmodal', {
          keyboard: false
        })
        const modalToggle = document.getElementById('editmodal');
        myModal.show(modalToggle);
      })
    });
    //deleting
    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        // console.log("delete");
        sno = e.target.id.substr(1, );
        if (confirm("Are You Sure You want to delete this note?")) {
          // console.log("yesss");
          window.location = `/curd/index.php?delete=${sno}`;
        }
      })
    });
  </script>
</body>

</html>
