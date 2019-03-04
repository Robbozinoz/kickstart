<?php
$db = new PDO("mysql:host=localhost;dbname=kickstartapp", "jr_kickstart", "kickstart");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
    $queryStr = "SELECT * FROM users";
    $query = $db->prepare($queryStr);
    $query->execute();
    while ($row = $query->fetch()) {
        echo $row['id'] . ' - ' . $row['name'] . ' - '  . $row['email'] . ' - ' . $row['password'];
        echo '<br>';
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
$query->closeCursor();
$db = null;
?>
