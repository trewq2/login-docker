<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <title>Regisztrációs űrlap</title>
</head>
<body>
  <h2>Regisztráció</h2>
  <form method="post" action="index.php">
    <label for="nev">Név:</label>
    <input type="text" id="nev" name="nev" required>
    <br><br>

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required>
    <br><br>

    <label for="jelszo">Jelszó:</label>
    <input type="password" id="jelszo" name="jelszo" required>
    <br><br>

    <label for="jelszo2">Jelszó ismét:</label>
    <input type="password" id="jelszo2" name="jelszo2" required>
    <br><br>

    <button type="submit">Regisztráció</button>
  </form>

  <hr>

  <?php
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $nev = trim($_POST["nev"]);
      $email = trim($_POST["email"]);
      $jelszo = $_POST["jelszo"];
      $jelszo2 = $_POST["jelszo2"];

      $hibak = [];

      // Ellenőrzések
      if (empty($nev) || empty($email) || empty($jelszo) || empty($jelszo2)) {
          $hibak[] = "Minden mezőt ki kell tölteni!";
      }

      if ($jelszo !== $jelszo2) {
          $hibak[] = "A két jelszó nem egyezik!";
      }

      if (strlen($jelszo) < 6) {
          $hibak[] = "A jelszónak legalább 6 karakter hosszúnak kell lennie!";
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $hibak[] = "Érvénytelen e-mail formátum!";
      }

      // Ha nincs hiba, mentés adatbázisba
      if (count($hibak) === 0) {
          $hashed = password_hash($jelszo, PASSWORD_DEFAULT);

          $stmt = $conn->prepare("INSERT INTO users (nev, email, password) VALUES (:nev, :email, :password)");
          $stmt->bindParam(':nev', $nev);
          $stmt->bindParam(':email', $email);
          $stmt->bindParam(':password', $hashed);

          try {
              $stmt->execute();
              echo "<h3>Sikeres regisztráció!</h3>";
          } catch (PDOException $e) {
              if ($e->errorInfo[1] == 1062) { // Duplicate entry
                  echo "<h3>Hiba: Ez az e-mail már regisztrálva van!</h3>";
              } else {
                  echo "<h3>Hiba: " . $e->getMessage() . "</h3>";
              }
          }
      } else {
          echo "<h3>Hibák:</h3><ul>";
          foreach ($hibak as $hiba) {
              echo "<li>$hiba</li>";
          }
          echo "</ul>";
      }
  }
  ?>
</body>
</html>
