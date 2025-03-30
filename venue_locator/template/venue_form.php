<?php
require 'config.php'; // Database connection file

$nextVenueId = 1;
$result = $conn->query("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'venue_db' AND TABLE_NAME = 'venues'");
if ($result && $row = $result->fetch_assoc()) {
    $nextVenueId = $row['AUTO_INCREMENT'];
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $lat = floatval($_POST['lat']);
    $lng = floatval($_POST['lng']);
    $capacity = intval($_POST['capacity']);
    $category = trim($_POST['category']);
    $category2 = trim($_POST['category2']);
    $category3 = trim($_POST['category3']);
    $created_at = $_POST['created_at'] ?? date('Y-m-d H:i:s');

    // Validate and encode JSON tags
    $tags = json_decode($_POST['tags'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error: Invalid JSON format for tags.");
    }
    $tagsInput = trim($_POST['tags']);

// Check if input is valid JSON
if (json_decode($tagsInput, true) === null && json_last_error() !== JSON_ERROR_NONE) {
    die("Error: Tags must be in valid JSON format (e.g., [\"event\", \"wedding\", \"party\"]).");
}

// Store JSON in the database
$tags = $tagsInput;
 // Convert to proper JSON string

    // Ensure uploads directory exists
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Handle file upload
    $image = 'uploads/default_court.jpg';
    if (!empty($_FILES['image']['name'])) {
        $targetFile = $uploadDir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $targetFile;
        } else {
            die("Error: Failed to upload image.");
        }
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO venues (name, description, price, lat, lng, capacity, tags, category, category2, category3, image, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddiisissss", $name, $description, $price, $lat, $lng, $capacity, $tags, $category, $category2, $category3, $image, $created_at);

    if ($stmt->execute()) {
        echo "<script>alert('Venue added successfully!'); window.location.href='venue_list.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body { background-color: #1e293b; color: #cbd5e1; font-family: 'Courier New', Courier, monospace; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-4xl">
        <h2 class="text-2xl mb-4">Create Venue</h2>
        <form method="POST" enctype="multipart/form-data">
        <div class="mb-4">
                    <label for="venue_id" class="block text-sm mb-2">Venue ID</label>
                    <input type="text" id="venue_id" name="venue_id" value="<?php echo $nextVenueId; ?>" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-300" readonly>
                </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="name" class="block text-sm mb-2">Name</label>
                    <input type="text" id="name" name="name" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-300" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm mb-2">Description</label>
                    <textarea id="description" name="description" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-300" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-sm mb-2">Price</label>
                    <input type="number" step="0.01" id="price" name="price" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-300" required>
                </div>
                <div class="mb-4">
                    <label for="lat" class="block text-sm mb-2">Latitude</label>
                    <input type="number" step="0.000001" id="lat" name="lat" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-300" required>
                </div>
                <div class="mb-4">
                    <label for="lng" class="block text-sm mb-2">Longitude</label>
                    <input type="number" step="0.000001" id="lng" name="lng" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-300" required>
                </div>
                <div class="mb-4">
                    <label for="capacity" class="block text-sm mb-2">Capacity</label>
                    <input type="number" id="capacity" name="capacity" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-300" required>
                </div>
                <div class="mb-4">
                    <label for="tags" class="block text-sm mb-2">Tags (JSON format)</label>
                    <input type="text" id="tags" name="tags" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-300" required>
                </div>
                <div class="mb-4">
                    <label for="category" class="block text-sm mb-2">Category</label>
                    <input type="text" id="category" name="category" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-300" required>
                </div>
                <div class="mb-4">
                    <label for="category2" class="block text-sm mb-2">Category 2</label>
                    <select id="category2" name="category2" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-300" required>
                        <option value="low price">Low Price</option>
                        <option value="mid price">Mid Price</option>
                        <option value="high price">High Price</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="category3" class="block text-sm mb-2">Category 3</label>
                    <select id="category3" name="category3" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-300" required>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="10">10</option>
                        <option value="12">12</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-sm mb-2">Image Upload</label>
                    <input type="file" id="image" name="image" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-300">
                </div>
                <div class="mb-4">
                    <label for="created_at" class="block text-sm mb-2">Created At</label>
                    <input type="datetime-local" id="created_at" name="created_at" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-300">
                </div>
            </div>
            <button type="submit" class="w-full p-2 bg-blue-600 rounded text-white">Submit</button>
        </form>
    </div>
</body>
</html>
