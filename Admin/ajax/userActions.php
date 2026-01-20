<?php
require_once '../../includes/auth.php';
header('Content-Type: application/json');

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "albart";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(["error" => $conn->connect_error]));
}

$action = $_POST['action'] ?? '';

if ($action == 'get') {
    // Marrim listen e userave me role
    $sql = "SELECT u.id, u.name, u.surname, u.email, 
                   CASE 
                       WHEN k.Klient_ID IS NOT NULL THEN 'klient' 
                       WHEN a.Artist_ID IS NOT NULL THEN 'artist'
                       WHEN ad.Admin_ID IS NOT NULL THEN 'admin'
                       ELSE 'unknown' 
                   END AS role
            FROM Users u
            LEFT JOIN Klient k ON u.id = k.User_ID
            LEFT JOIN Artisti a ON u.id = a.User_ID
            LEFT JOIN Admin ad ON u.id = ad.User_ID
            ORDER BY u.id ASC";

    $result = $conn->query($sql);
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
}

elseif ($action == 'add') {
    $name = $conn->real_escape_string($_POST['name']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Shto userin
    $sql = "INSERT INTO Users (name, surname, email, password) VALUES ('$name', '$surname', '$email', '$password')";
    if ($conn->query($sql)) {
        $user_id = $conn->insert_id;

        // Vendos rolin
        if ($role == 'klient') {
            $conn->query("INSERT INTO Klient (User_ID) VALUES ($user_id)");
        } elseif ($role == 'artist') {
            $conn->query("INSERT INTO Artisti (User_ID) VALUES ($user_id)");
        }else{
            $conn->query("INSERT INTO Admin (User_ID) VALUES ($user_id)");
        }

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}

elseif ($action == 'update') {
    $id = intval($_POST['id']);
    $field = $_POST['field'];
    $value = $conn->real_escape_string($_POST['value']);

    // Nese po ndryshojme rolin
    if ($field == 'role') {
        // Fshi userin nga te dyja tabelat e rolit
        $conn->query("DELETE FROM Klient WHERE User_ID = $id");
        $conn->query("DELETE FROM Artisti WHERE User_ID = $id");
        $conn->query("DELETE FROM Admin WHERE User_ID = $id");

        // Vendos rolin e ri
        if ($value == 'klient') {
            $conn->query("INSERT INTO Klient (User_ID) VALUES ($id)");
        } elseif ($value == 'artist') {
            $conn->query("INSERT INTO Artisti (User_ID) VALUES ($id)");
        }else{
            $conn->query("INSERT INTO Admin (User_ID) VALUES ($id)");
        }

        echo json_encode(["success" => true]);
    } else {
        // Fushat e tjera direkt në Users
        $allowed_fields = ['name', 'surname', 'email', 'password'];
        if (in_array($field, $allowed_fields)) {
            if ($field == 'password') {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }
            $sql = "UPDATE Users SET $field='$value' WHERE id=$id";
            $conn->query($sql);
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => "Field not allowed"]);
        }
    }
}

elseif ($action == 'delete') {
    $id = intval($_POST['id']);

    // Fshi nga role tabelat
    $conn->query("DELETE FROM Klient WHERE User_ID = $id");
    $conn->query("DELETE FROM Artisti WHERE User_ID = $id");
    $conn->query("DELETE FROM Admin WHERE User_ID = $id");

    // Fshi userin
    $conn->query("DELETE FROM Users WHERE id=$id");

    echo json_encode(["success" => true]);
}

elseif ($action == "getSingleUser") {
    $id = intval($_POST['id']);

    $sql = "
        SELECT u.id, u.name, u.surname, u.email,
        CASE 
            WHEN a.Artist_ID IS NOT NULL THEN 'artist'
            WHEN k.Klient_ID IS NOT NULL THEN 'klient'
            WHEN ad.Admin_ID IS NOT NULL THEN 'admin'
        END AS role
        FROM Users u
        LEFT JOIN Artisti a ON u.id = a.User_ID
        LEFT JOIN Klient k ON u.id = k.User_ID
        LEFT JOIN Admin ad ON U.id = ad.User_ID
        WHERE u.id = $id
    ";

    $res = $conn->query($sql)->fetch_assoc();
    echo json_encode($res);
}

elseif ($action == "modify") {

    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $_POST['role'];

    $conn->query("UPDATE Users SET name='$name', surname='$surname', email='$email' WHERE id=$id");

    // Roli
    $conn->query("DELETE FROM Artisti WHERE User_ID=$id");
    $conn->query("DELETE FROM Klient WHERE User_ID=$id");

    if ($role === "artist"){
        $conn->query("INSERT INTO Artisti (User_ID) VALUES ($id)");

        // FOTO PROFILI (vetem upload)
        $stmt = $conn->prepare("SELECT Artist_ID FROM Artisti WHERE User_ID=?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $artist_id = $res['Artist_ID'] ?? null;

        if (!empty($_FILES['photo']['name']) && $artist_id !== null) {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $path = "uploads/profile_" . $artist_id . "_" . time() . "." . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], "../../".$path);
            $conn->query("UPDATE Artisti SET Fotografi='$path' WHERE Artist_ID=$artist_id");
        }

    } else{
        $conn->query("INSERT INTO Klient (User_ID) VALUES ($id)");
    }

    echo json_encode(["success" => true]);
}

else {
    echo json_encode(["error" => "Invalid action"]);
}

$conn->close();
