<?php  
  
  include("_connect.php");
  $email = $_SERVER['PHP_AUTH_USER'] ?? null;
  $password = $_SERVER['PHP_AUTH_PW'] ?? null;
  
  $conn = dbo();
  $sql = "SELECT * FROM admins WHERE email = :email";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();
  $admin = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!password_verify($password, $admin["password"])) {
    $admin = false;
  }

  if (!isset($_SERVER['PHP_AUTH_USER']) || !$admin) {
    header('WWW-Authenticate: Basic realm="The Registration"');
    header('HTTP/1.0 401 Unauthorized');
    echo "You must be authenticated to use this page";
    exit;
  }

  $users = $conn->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en" class="home">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">

    <title>The Registery</title>
  </head>

  <body>
    <?php include_once('notification.php') ?>
    
    <div class="container">
      <header class="jumbotron my-5">
        <h1 class="display-4">Users</h1>
      </header>

      <section class="mb-5">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($users as $user): ?>
              <tr>
                <td><?= $user['first_name'] ?></td>
                <td><?= $user['last_name'] ?></td>
                <td><?= $user['email'] ?></td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </section
    </div>
  </body>
</html>