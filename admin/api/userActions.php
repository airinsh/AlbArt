<?php
header('Content-Type: application/json');

// Database connection
$host = "localhost";
$user = "root";
$pass = ""; // vendos password-in e databazes
$db   = "albart";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(["error" => $conn->connect_error]));
}

$action = $_POST['action'] ?? '';

if ($action == 'get') {
    // Marrim listën e userve me role
    $sql = "SELECT u.id, u.name, u.surname, u.email, 
                   CASE 
                       WHEN k.Klient_ID IS NOT NULL THEN 'klient' 
                       WHEN a.Artist_ID IS NOT NULL THEN 'artist' 
                       ELSE 'unknown' 
                   END AS role
            FROM Users u
            LEFT JOIN Klient k ON u.id = k.User_ID
            LEFT JOIN Artisti a ON u.id = a.User_ID
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
    $role = $_POST['role']; // client ose artist

    // Shto userin
    $sql = "INSERT INTO Users (name, surname, email, password) VALUES ('$name', '$surname', '$email', '$password')";
    if ($conn->query($sql)) {
        $user_id = $conn->insert_id;

        // Vendos rolin
        if ($role == 'klient') {
            $conn->query("INSERT INTO Klient (User_ID) VALUES ($user_id)");
        } elseif ($role == 'artist') {
            $conn->query("INSERT INTO Artisti (User_ID) VALUES ($user_id)");
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

    // Nëse po ndryshojmë role
    if ($field == 'role') {
        // Fshi userin nga të dyja tabelat e rolit
        $conn->query("DELETE FROM Klient WHERE User_ID = $id");
        $conn->query("DELETE FROM Artisti WHERE User_ID = $id");

        // Vendos rolin e ri
        if ($value == 'klient') {
            $conn->query("INSERT INTO Klient (User_ID) VALUES ($id)");
        } elseif ($value == 'artist') {
            $conn->query("INSERT INTO Artisti (User_ID) VALUES ($id)");
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
            WHEN k.Klient_ID IS NOT NULL THEN 'client'
        END AS role
        FROM Users u
        LEFT JOIN Artisti a ON u.id = a.User_ID
        LEFT JOIN Klient k ON u.id = k.User_ID
        WHERE u.id = $id
    ";

    $res = $conn->query($sql)->fetch_assoc();
    echo json_encode($res);
}

elseif ($action == "updateUser") {

    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $_POST['role'];

    $conn->query("UPDATE Users SET name='$name', surname='$surname', email='$email' WHERE id=$id");

    // Role
    $conn->query("DELETE FROM Artisti WHERE User_ID=$id");
    $conn->query("DELETE FROM Klient WHERE User_ID=$id");

    if ($role === "artist")
        $conn->query("INSERT INTO Artisti (User_ID) VALUES ($id)");
    else
        $conn->query("INSERT INTO Klient (User_ID) VALUES ($id)");

    // FOTO (vetëm upload)
    if (!empty($_FILES['photo']['name'])) {
        $path = "uploads/profile_" . $id . "_" . time() . ".jpg";
        move_uploaded_file($_FILES['photo']['tmp_name'], "../".$path);

        $conn->query("UPDATE Users SET profile_photo='$path' WHERE id=$id");
    }

    echo json_encode(["success" => true]);
}

else {
    echo json_encode(["error" => "Invalid action"]);
}

$conn->close();
