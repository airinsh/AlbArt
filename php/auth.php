<?php
// auth.php
session_start();

// Lidhja me DB
$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) {
    die("Gabim lidhjeje me DB: " . $conn->connect_error);
}

// ========================
// Kontrollo session ose cookie
// ========================
if (!isset($_SESSION['user_id'])) {

    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];

        $stmt = $conn->prepare("SELECT * FROM Users WHERE remember_token=?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['last_activity'] = time();

            // Vendos rolin në session
            if ($conn->query("SELECT 1 FROM Artisti WHERE User_ID=".$user['id'])->num_rows > 0)
                $_SESSION['role'] = 'artist';
            elseif ($conn->query("SELECT 1 FROM Klient WHERE User_ID=".$user['id'])->num_rows > 0)
                $_SESSION['role'] = 'klient';
            elseif ($conn->query("SELECT 1 FROM Admin WHERE User_ID=".$user['id'])->num_rows > 0)
                $_SESSION['role'] = 'admin';
        } else {
            // Cookie jo e vlefshme -> fshij dhe ridrejto në login
            setcookie("remember_token", "", time()-3600, "/");
            header("Location: login.php");
            exit;
        }
    } else {
        // As session as cookie -> ridrejto në login
        header("Location: login.php");
        exit;
    }
}

// ========================
// Timeout 15 minuta
// ========================
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 900)) {
    session_destroy();
    header("Location: login.php?timeout=1");
    exit;
}

$_SESSION['last_activity'] = time();

// ========================
// Funksione ndihmëse
// ========================
function getArtistID($conn) {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'artist') return null;
    $stmt = $conn->prepare("SELECT Artist_ID FROM Artisti WHERE User_ID=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return $res['Artist_ID'] ?? null;
}

function getKlientID($conn) {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'klient') return null;
    $stmt = $conn->prepare("SELECT Klient_ID FROM Klient WHERE User_ID=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return $res['Klient_ID'] ?? null;
}

function getAdminID($conn) {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') return null;
    $stmt = $conn->prepare("SELECT Admin_ID FROM Admin WHERE User_ID=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return $res['Admin_ID'] ?? null;
}
