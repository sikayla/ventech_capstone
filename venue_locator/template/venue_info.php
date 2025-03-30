<?php
require '../config/db.php'; // Database connection file

// Get the venue_id from the URL
$venue_id = isset($_GET['venue_id']) ? intval($_GET['venue_id']) : 0;
$venue_details = null;

// Fetch venue details if venue_id is present
if ($venue_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM venue_details WHERE venue_id = ?");
    $stmt->bind_param("i", $venue_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $venue_details = $result->fetch_assoc();
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Photo Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-4">
    <!-- Display Venue Details if Exists -->
    <?php if ($venue_details): ?>
        <div class="bg-white p-4 rounded shadow mb-4">
            <h1 class="text-2xl font-bold mb-2"><?= htmlspecialchars($venue_details['venue_name']) ?></h1>
            <img src="<?= htmlspecialchars($venue_details['header_photo']) ?>" class="w-full rounded mb-4">
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-2xl font-bold text-blue-600 mb-4"><i class="fas fa-camera"></i> Photo Gallery</h2>
                    <img src="<?= htmlspecialchars($venue_details['main_image']) ?>" class="w-full rounded mb-4">

                    <div class="flex space-x-2 overflow-x-auto">
                        <?php
                        $thumbnails = json_decode($venue_details['thumbnails'], true) ?? [];
                        foreach ($thumbnails as $thumb):
                        ?>
                            <img src="<?= htmlspecialchars($thumb) ?>" class="w-24 h-24 rounded">
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="bg-white p-4 rounded shadow mt-4">
                    <p><?= nl2br(htmlspecialchars($venue_details['description'])) ?></p>
                </div>

                <?php if (!empty($venue_details['video_tour'])): ?>
                    <div class="bg-white p-4 rounded shadow mt-4">
                        <h2 class="text-2xl font-bold text-blue-600 mb-4"><i class="fas fa-video"></i> Video Tour</h2>
                        <video controls class="w-full">
                            <source src="<?= htmlspecialchars($venue_details['video_tour']) ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-4">
                <div class="bg-white p-4 rounded shadow">
                    <p><strong>Phone:</strong> <?= htmlspecialchars($venue_details['phone']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($venue_details['email']) ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($venue_details['address']) ?></p>
                    <a href="<?= htmlspecialchars($venue_details['maps_url']) ?>" class="text-blue-600" target="_blank">
                        <i class="fas fa-map-marker-alt"></i> Get Directions
                    </a>
                </div>

                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-lg font-bold mb-2"><i class="fas fa-share-alt"></i> Social Profiles</h3>
                    <?php if (!empty($venue_details['facebook'])): ?>
                        <p><a href="<?= htmlspecialchars($venue_details['facebook']) ?>" class="text-blue-600" target="_blank"><i class="fab fa-facebook"></i> Facebook</a></p>
                    <?php endif; ?>
                    <?php if (!empty($venue_details['twitter'])): ?>
                        <p><a href="<?= htmlspecialchars($venue_details['twitter']) ?>" class="text-blue-600" target="_blank"><i class="fab fa-twitter"></i> Twitter</a></p>
                    <?php endif; ?>
                    <?php if (!empty($venue_details['instagram'])): ?>
                        <p><a href="<?= htmlspecialchars($venue_details['instagram']) ?>" class="text-blue-600" target="_blank"><i class="fab fa-instagram"></i> Instagram</a></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- Show Form Only If No Venue Details -->
        <form action="save_venue_info.php" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow">
            <h1 class="text-2xl font-bold mb-2">Submit Venue Details</h1>
            <input type="hidden" name="venue_id" value="<?= htmlspecialchars($venue_id) ?>">

            <input type="text" name="venue_name" class="w-full p-2 border rounded mb-4" placeholder="Enter Venue Name">
            <textarea name="description" class="w-full h-32 p-2 border rounded mb-4" placeholder="Enter Description"></textarea>

            <input type="text" name="phone" class="w-full p-2 border rounded mb-4" placeholder="Phone">
            <input type="email" name="email" class="w-full p-2 border rounded mb-4" placeholder="Email">
            <input type="text" name="address" class="w-full p-2 border rounded mb-4" placeholder="Address">
            <input type="url" name="maps_url" class="w-full p-2 border rounded mb-4" placeholder="Google Maps URL">

            <input type="file" name="header_photo" class="w-full p-2 border rounded mb-4">
            <input type="file" name="main_image" class="w-full p-2 border rounded mb-4">
            
            <label class="block font-bold">Gallery Images</label>
            <input type="file" name="thumbnails[]" multiple class="w-full p-2 border rounded mb-4">
            
            <input type="url" name="facebook" class="w-full p-2 border rounded mb-4" placeholder="Facebook URL">
            <input type="url" name="twitter" class="w-full p-2 border rounded mb-4" placeholder="Twitter URL">
            <input type="url" name="instagram" class="w-full p-2 border rounded mb-4" placeholder="Instagram URL">
            
            <input type="file" name="video_tour" class="w-full p-2 border rounded mb-4">

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded w-full">Submit</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
