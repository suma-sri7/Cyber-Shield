<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['email'])) {
  header("Location: login.html");
  exit();
}

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT full_name, email FROM users WHERE email = ?");
if (!$stmt) {
  die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "❌ No account found.";
  exit();
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Account Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans min-h-screen flex items-center justify-center px-4">

  <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-lg text-center animate-fade-in">
    <h1 class="text-2xl font-bold text-blue-600 mb-4">👤 Your Account Details</h1>
    
    <div class="text-left space-y-4 text-lg">
      <div>
        <span class="font-semibold text-gray-700">Full Name:</span>
        <p class="text-gray-900"><?= htmlspecialchars($user['full_name']) ?></p>
      </div>
      <div>
        <span class="font-semibold text-gray-700">Email:</span>
        <p class="text-gray-900"><?= htmlspecialchars($user['email']) ?></p>
      </div>
    </div>

    <div class="mt-6">
      <a href="index.html" class="text-blue-600 hover:underline text-sm">← Back to Help Desk</a>
    </div>
  </div>

  <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
      animation: fade-in 0.6s ease-out;
    }
  </style>

</body>
</html>
