<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<h2 style='text-align:center;margin-top:20px;'>Please log in to view your history.</h2>";
    exit;
}

// Connect to MySQL
$mysqli = new mysqli("localhost", "root", "", "cyber_shield");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$user_id = $_SESSION['user_id'];

// Run the query
$query = "SELECT message, suggestions, timestamp FROM queries WHERE user_id = $user_id ORDER BY timestamp DESC";
$result = $mysqli->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>History - Cyber Shield</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">
  <div class="max-w-3xl mx-auto bg-white shadow-md rounded-xl p-6">
    <h2 class="text-2xl font-bold text-blue-600 mb-4 text-center">Your Submitted Problems</h2>

    <?php if ($result && $result->num_rows > 0): ?>
      <ul class="space-y-4">
        <?php while ($row = $result->fetch_assoc()): ?>
          <li class="border border-gray-300 rounded p-4">
            <p class="text-gray-800 font-medium mb-1">📝 <strong>Your Query:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
            <p class="text-sm text-gray-700"><strong>💡 Suggestions:</strong></p>
            <ul class="list-disc list-inside ml-4 text-gray-600">
              <?php
                $suggestions = json_decode($row['suggestions'], true);
                if (is_array($suggestions)) {
                  foreach ($suggestions as $sugg) {
                    echo "<li>" . htmlspecialchars($sugg) . "</li>";
                  }
                } else {
                  echo "<li>No suggestions recorded</li>";
                }
              ?>
            </ul>
            <p class="text-xs text-gray-500 mt-2">Submitted on: <?php echo $row['timestamp']; ?></p>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p class="text-center text-gray-600">No history found.</p>
    <?php endif; ?>

    <div class="text-center mt-6">
      <a href="index.html" class="text-blue-600 hover:underline">← Back to Home</a>
    </div>
  </div>
</body>
</html>
