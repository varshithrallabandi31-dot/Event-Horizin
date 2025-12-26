<?php
require_once __DIR__ . '/app/libs/KitHelper.php';

// Mock Data
$event = [
    'id' => 1,
    'title' => 'Neon Nights: Rooftop Party',
    'start_time' => '2025-12-25 20:00:00',
    'location_name' => 'Skyline Lounge, NYC',
    'image_url' => 'https://images.unsplash.com/photo-1545128485-c400e7702796?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'category' => 'Nightlife'
];

$user = [
    'id' => 123,
    'name' => 'John Doe'
];

try {
    $pdfContent = KitHelper::generate($event, $user);
    file_put_contents(__DIR__ . '/sample_kit.pdf', $pdfContent);
    echo "PDF generated successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
