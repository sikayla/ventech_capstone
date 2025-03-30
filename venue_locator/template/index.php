<?php
$title = "Find Suitable Venues Around Bacoor Campus";


// Array of places (Fixed duplicate entry and improved structure)
$places = [
    ["name" => "Practicing Physical Education (P.E.)", "image" => "/venue_locator/images/act.png"],
    ["name" => "Las Piñas City", "image" => "/venue_locator/images/venue2.jpg"],
    ["name" => "Mandaluyong", "image" => "/venue_locator/images/venue2.jpg"],
    ["name" => "San Juan", "image" => "/venue_locator/images/venue2.jpg"],
    ["name" => "Parañaque", "image" => "/venue_locator/images/venue2.jpg"]
];

// Array of venues (Fixed typos & improved readability)

$venue = [
    
    "logo" => "/venue_locator/images/logo.png",
    
  ];

// Database Connection (Improved Security & Error Handling)
$host = "localhost";
$user = "root"; // Change if needed
$pass = "";
$dbname = "venue_db"; 

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Unique Categories, Prices, and Capacities in One Query (Optimized)
$query = "SELECT DISTINCT category, category2 AS price_range, category3 AS capacity FROM venues";
$filterResult = $conn->query($query);

// Fetch All Venues (Optimized)
$sql = "SELECT * FROM venues ORDER BY created_at DESC";
$venues = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

$image_url = [ "logo" => "/venue_locator/images/logo.png" ]; // Fix for logo usag

$base_url = "/venue_locator/uploads/";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventech Locator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    .bg-gray-800.p-4.flex.justify-between.items-center {
    color: white;
}
@media (min-width: 1024px) {
    .lg\:grid-cols-2 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        width: 150%;
        margin-left: -25%;
    }
}
    </style>
</head>
<body class="bg-gray-100">
<header class="bg-gray-800 p-4 flex justify-between items-center">
        <div class="flex items-center">
            <img alt="Ventech" class="h-10 w-10" src="<?= $venue['logo'] ?>" width="40" height="40"/>
            <span class="ml-2 text-xl font-bold">ventech venues</span>
        </div>
        <nav class="flex space-x-4 relative z-50">
    <a class="hover:underline" href="venue_form.php">Submit Venue</a>

    <div class="relative group">
        <a class="hover:underline cursor-pointer flex items-center" href="#">
            Explore <i class="fas fa-chevron-down ml-1"></i>
        </a>

        <!-- Dropdown Menu -->
        <div class="absolute hidden group-hover:block bg-white text-black mt-2 py-2 w-48 shadow-lg border border-gray-200 z-50">
            <a href="index.php" class="block px-4 py-2 hover:bg-gray-200">Home</a>
            <a href="venue_list.php" class="block px-4 py-2 hover:bg-gray-200">List Venues</a>
            <a  href="manage_bookings.php" class="block px-4 py-2 hover:bg-gray-200">Bookings</a>
            <a href="find.php" class="block px-4 py-2 hover:bg-gray-200">Find Venues</a>
        </div>
    </div>

    <a href="#" class="hover:underline">Help</a>
    <a href="signin.php" class="hover:underline">Sign In</a>
</nav>

    </header>
    <main>
        <div class="relative">
            <img src="/venue_locator/images/venue2.jpg" alt="Beautiful beach resort with clear blue water and palm trees" class="w-full h-96 object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Find the perfect event place</h1>
                <p class="text-lg md:text-xl mb-8">Discover hundreds of great places in the Philippines to host your special event</p>
                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                    <input type="text" placeholder="Search for venues" class="px-4 py-2 rounded-md text-black">
                    <select class="px-4 py-2 rounded-md text-black">
                        <option>All Regions</option>
                    </select>
                    <select class="px-4 py-2 rounded-md text-black">
                        <option>Choose a category</option>
                    </select>
                    <button class="px-4 py-2 bg-blue-600 rounded-md hover:bg-blue-700">Search</button>
                </div>
            </div>
        </div>
    </main>
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold text-center mb-2"><?= $title; ?></h1>
        <p class="text-center text-gray-600 mb-8">Explore the best locations around the country from our listings.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($places as $place): ?>
                <div class="relative">
                    <img src="<?= $place['image']; ?>" alt="Event place in <?= htmlspecialchars($place['name'], ENT_QUOTES, 'UTF-8'); ?>" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
                        <span class="text-white text-xl font-semibold"><?= htmlspecialchars($place['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="container mx-auto max-w-6xl py-8">
    <h1 class="text-3xl font-bold text-center mb-2">Where do you want to host your event?</h1>
    <p class="text-center text-gray-600 mb-8">Find the right venue based on your requirements. Explore our Listings.</p>

     <!-- Venue Cards -->
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
                    if (!empty($imagePath) && file_exists($imagePath)) {
                        $image = $imagePath;
                    } else {
                        $image = "uploads/default_court.jpg";
                    }

                    // Unique details ID
                    $detailsId = "details" . $venue["id"];

                    echo "
                    <div class='bg-white rounded-lg shadow-md overflow-hidden'>
                        <img src='{$image}' alt='{$venue["name"]}' class='w-full h-48 object-cover'/>
                        <div class='p-4'>
                            <h2 class='text-xl font-bold'>{$venue["name"]}</h2>
                            <p class='text-gray-600'>Description: {$venue["description"]}</p>
                            <p class='text-gray-600'>Price: ₱{$venue["price"]}</p>
                            <p class='text-gray-600 cursor-pointer text-blue-500' onclick='toggleDetails(\"$detailsId\")'>More details...</p>
                            <div class='hidden-details' id='$detailsId'>
                                <p class='text-gray-600'>Location: (Lat: {$venue["lat"]}, Lng: {$venue["lng"]})</p>
                                <p class='text-gray-600'>Capacity: {$venue["capacity"]}</p>
                                <p class='text-gray-600'>Tags: $tags_display</p>
                                <p class='text-gray-600'>Category: {$venue["category"]}</p>
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

  
   

</div>
<script>
        function toggleDetails(id) {
            document.getElementById(id).classList.toggle('hidden-details');
        }
    </script>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


