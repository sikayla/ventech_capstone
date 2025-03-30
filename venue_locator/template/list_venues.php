<?php 
// Database Configuration
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

$image_url = [ "logo" => "/venue_locator/images/logo.png" ]; // Fix for logo usage

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Venues</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { background-color: #1a1a2e; }
        .dropdown-menu { max-height: 300px; overflow-y: auto; }
        .venue-card { display: block; } /* Default display */
        @media (min-width: 1024px) {
            .lg\:grid-cols-2 { grid-template-columns: repeat(3, minmax(0, 1fr)); width: 150%; margin-left: -25%; }
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-gray-900 text-white">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="flex items-center">
                <img src="<?= $image_url['logo'] ?>" alt="Primo Venues Logo" class="h-10 w-10"/>
                <span class="ml-2 text-xl font-bold">Primo Venues</span>
            </div>
            <nav class="flex space-x-4">
            
                <a class="hover:underline" href="#">Submit Venue</a>
                <div class="relative group">
                    <a class="hover:underline" href="#">Explore <i class="fas fa-chevron-down"></i></a>
                    <div class="absolute hidden group-hover:block bg-white text-black mt-2 py-2 w-48 shadow-lg">
                        <a href="index.php" class="block px-4 py-2 hover:bg-gray-200">Home</a>
                        <a href="list_venues.php" class="block px-4 py-2 hover:bg-gray-200">List Venues</a>
                        <a href="manage_bookings.php" class="block px-4 py-2 hover:bg-gray-200">Bookings</a>
                        <a href="find.php" class="block px-4 py-2 hover:bg-gray-200">Find Venues</a>
                    </div>
                </div>
                <a href="#" class="hover:underline">Help</a>
                <a href="signin.php" class="hover:underline">Sign In</a>
            </nav>
        </div>
    </header>

 

    <!-- Venue Cards -->
    <div class="container mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php foreach ($venues as $venue) : ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden venue-card"
                 data-category="<?= strtolower($venue['category']) ?>" 
                 data-price="<?= strtolower($venue['category2']) ?>" 
                 data-person="<?= strtolower($venue['category3']) ?>">

                <img src="<?= htmlspecialchars($venue['image']) ?>" alt="<?= htmlspecialchars($venue['name']) ?>" class="w-full h-40 object-cover">
                <div class="p-4">
                    <p class="text-gray-600 text-sm mb-1">
                        ₱<?= number_format($venue['price'], 2) ?> • <?= htmlspecialchars($venue['category']) ?>
                    </p>
                    <h2 class="text-md font-semibold text-gray-800 mb-1"><?= htmlspecialchars($venue['name']) ?></h2>
                    <p class="text-gray-600 text-xs mb-3"><?= substr(htmlspecialchars($venue['description']), 0, 80) ?>...</p>
                    <a href="venue_details.php?id=<?= $venue['id'] ?>" class="text-blue-500 text-sm hover:underline">
                        View Venue <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
