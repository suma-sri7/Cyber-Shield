<?php
$email = $_GET['email'] ?? '';
$mysqli = new mysqli("localhost", "root", "", "cyber_shield");

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = $_POST['otp'];

    $stmt = $mysqli->prepare("SELECT otp_code FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($otp);
    $stmt->fetch();
    $stmt->close();

    if ($entered_otp == $otp) {
        $update = $mysqli->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
        $update->bind_param("s", $email);
        $update->execute();
        $message = "<p class='text-green-600 font-semibold'>✅ Email verified. You can now <a href='login.html' class='underline text-blue-600'>login</a>.</p>";
    } else {
        $message = "<p class='text-red-600 font-semibold'>❌ Incorrect OTP. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Verify OTP - Cyber Shield</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex items-center justify-center px-4">
  <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md border border-gray-200">
    <h1 class="text-2xl font-bold text-blue-600 mb-6 text-center">Cyber Shield: Verify Your Email</h1>

    <?php if ($message): ?>
      <div class="mb-4 text-center"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
      <label for="otp" class="block mb-2 text-gray-700 font-medium">Enter the OTP sent to your email:</label>
      <input type="text" id="otp" name="otp"
             class="w-full px-4 py-3 border border-gray-300 rounded-xl mb-6 focus:outline-none focus:ring-2 focus:ring-blue-500"
             placeholder="6-digit OTP" required>

      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-xl font-semibold transition">
        Verify OTP
      </button>
    </form>

    <p class="text-sm text-center text-gray-500 mt-6">Didn't get the OTP? <a href="register.html" class="text-blue-600 underline">Try registering again</a>.</p>
  </div>
</body>
</html>
