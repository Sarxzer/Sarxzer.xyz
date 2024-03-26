<?php
// Get the Pastebin ID from the query parameters
$pastebinId = $_GET['id'];

// Build the URL
$url = "https://pastebin.com/raw/" . $pastebinId;

// Use file_get_contents to fetch the content
$content = file_get_contents($url);

// Check if the content was fetched successfully
if ($content === FALSE) {
    // Handle error here
    echo "Failed to fetch content from Pastebin";
} else {
    // Output the content
    echo $content;
}
?>