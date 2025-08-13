<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    echo $_SESSION['email'];  // Return email instead of just "logged_in"
} else {
    echo "not_logged_in";
}
?>
