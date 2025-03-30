<?php 
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "venue_db";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Base URL for images
$base_url = "uploads/"; // Ensure this matches your actual uploads folder
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Venue Listings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        .hidden-details { display: none; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Venue Listings</h1>

        <div id="venueList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            // Fetch venues
            $sql = "SELECT * FROM venues ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($venue = $result->fetch_assoc()) {
                    // Decode JSON tags safely
                    $tags = json_decode($venue["tags"], true);
                    $tags_display = is_array($tags) ? implode(", ", $tags) : "No Tags";

                    // ✅ Fix Image Path Handling
                    $imagePath = $venue["image"];

                    // Ensure correct path
                    if (!empty($imagePath) && is_file(__DIR__ . "/" . $imagePath)) {
                        $image = $imagePath;
                    } else {
                        $image = "uploads/default_court.jpg"; // Default image
                    }

                    // Unique ID for toggling details
                    $detailsId = "details" . $venue["id"];

                    echo "
                    <div class='bg-white rounded-lg shadow-md overflow-hidden transform transition-all hover:shadow-lg cursor-pointer' onclick='toggleDetails(\"$detailsId\")'>
                        <img src='{$image}' alt='{$venue["name"]}' class='w-full h-48 object-cover'/>
                        <div class='p-4'>
                            <h2 class='text-xl font-bold'>{$venue["name"]}</h2>
                            <p class='text-gray-600'>Price: ₱{$venue["price"]}</p>
                            <p class='text-gray-600'>Capacity: {$venue["capacity"]}</p>
                            <p class='text-gray-600'>Tags: $tags_display</p>
                            <div class='hidden-details mt-2' id='$detailsId'>
                                <p class='text-gray-600'>Location: (Lat: {$venue["lat"]}, Lng: {$venue["lng"]})</p>
                                <p class='text-gray-600'>Category: {$venue["category"]}</p>
                                <p class='text-gray-600'>Description: {$venue["description"]}</p>
                                <a href='venue_info.php?venueId={$venue["id"]}' class='mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700'>View Venue</a>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<p class='text-gray-600'>No venues available.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function toggleDetails(id) {
            document.getElementById(id).classList.toggle('hidden-details');
        }
    </script>
</body>
</html>
