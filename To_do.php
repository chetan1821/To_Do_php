<?php
$con = mysqli_connect("localhost", "root", "", "to_do_data");
ob_start();

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$update_mode = false;
$task_to_update = "";
$id_to_update = "";

// If "Edit" is clicked
if (isset($_GET['edit'])) {
    $id_to_update = $_GET['edit'];
    $get_task = "SELECT * FROM to_do_table WHERE id = $id_to_update";
    $res = mysqli_query($con, $get_task);
    if ($row = mysqli_fetch_assoc($res)) {
        $task_to_update = $row['task'];
        $update_mode = true;
    }
}

// If "Update" is submitted
if (isset($_POST['update'])) {
    $updated_task = $_POST['task'];
    $updated_id = $_POST['id'];
    $upd = "UPDATE to_do_table SET task='$updated_task' WHERE id=$updated_id";
    if (mysqli_query($con, $upd)) {
        header("location:To_do.php");
    } else {
        echo "Error updating task: " . mysqli_error($con);
    }
}

// If "Add Task" is submitted
if (isset($_POST['submit'])) {
    $task = $_POST['task'];
    $ins = "INSERT INTO to_do_table VALUES ('','$task')";
    mysqli_query($con, $ins);
}

// If "Delete" is clicked
if (isset($_GET['del'])) {
    $delete = "DELETE FROM to_do_table WHERE id=" . $_GET['del'];
    if (mysqli_query($con, $delete)) {
        header("location:To_do.php");
    } else {
        echo "Error deleting: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>To Do List</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f4f8;
      margin: 0;
      padding: 0;
      display: flex;
      height: 100vh;
      justify-content: center;
      align-items: center;
    }

    .container {
      background: #ffffff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 90%;
      max-width: 500px;
      text-align: center;
    }

    h1 {
      color: #2c3e50;
      margin-bottom: 20px;
    }

    input[type="text"] {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 15px;
      border: 2px solid #dcdfe3;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s;
    }

    input[type="text"]:focus {
      border-color: #3498db;
      outline: none;
    }

    input[type="submit"], .btn {
      background-color: #3498db;
      color: white;
      border: none;
      padding: 10px 16px;
      font-size: 14px;
      border-radius: 6px;
      cursor: pointer;
      margin-right: 5px;
      text-decoration: none;
    }

    .btn:hover {
      background-color: #2980b9;
    }

    .btn-danger {
      background-color: #e74c3c;
    }

    .btn-danger:hover {
      background-color: #c0392b;
    }

    table {
      width: 100%;
      margin-top: 25px;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #f9fafb;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>📝 To Do List</h1>

    <!-- Form: Add or Update -->
    <form action="#" method="post">
      <input type="hidden" name="id" value="<?php echo $id_to_update; ?>">
      <input type="text" name="task" placeholder="Enter your task here" value="<?php echo $task_to_update; ?>" required />
      <?php if ($update_mode): ?>
        <input type="submit" name="update" value="Update Task" />
      <?php else: ?>
        <input type="submit" name="submit" value="Add Task" />
      <?php endif; ?>
    </form>

    <!-- Task List -->
    <table>
      <tr>
        <th>Task</th>
        <th>Action</th>
      </tr>
      <?php 
      $sel = "SELECT * FROM to_do_table";
      $exe = mysqli_query($con, $sel);
      while ($row = mysqli_fetch_array($exe)) {
      ?>
      <tr>
        <td><?php echo $row['task']; ?></td>
        <td>
          <a class="btn" href="To_do.php?edit=<?php echo $row['id']; ?>">Edit</a>
          <a class="btn btn-danger" href="To_do.php?del=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this task?')">Delete</a>
        </td>
      </tr>
      <?php } ?>
    </table>
  </div>
</body>
</html>
