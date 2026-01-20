<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "albart";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Gabim lidhjeje me DB: " . $conn->connect_error]);
    exit;
}

// Merr artistet dhe te dhenat e tyre (foto + emer)
$sql = "
    SELECT 
        a.Artist_ID, 
        u.name, 
        u.surname,
        a.Fotografi
    FROM Artisti a
    JOIN Users u ON a.User_ID = u.id
";
$result = $conn->query($sql);

$artists = [];
$imgFolder = "../uploads/"; // folderi ku jane fotot e artisteve

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Shto rrugen e plote per foton qe ruhet ne DB
        $row['Foto_Artistit'] = $imgFolder . $row['Fotografi'];
        $artists[] = $row;
    }
}

echo json_encode($artists, JSON_UNESCAPED_UNICODE);

$conn->close();
?>
