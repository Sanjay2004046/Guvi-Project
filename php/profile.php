<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set CORS headers
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');

// Include MongoDB library
require '../assets/vendor/autoload.php';

use MongoDB\Client;

// Connect to MongoDB
$client = new Client("mongodb://localhost:27017");
$collection = $client->guvidb->profile;

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the raw POST data
    $rawData = file_get_contents('php://input');

    // Attempt to decode the JSON data
    $data = json_decode($rawData, true);

    // Check if JSON decoding was successful
    if ($data === null) {
        echo json_encode(['success' => false, 'message' => 'Failed to decode JSON data']);
        exit;
    }

    // Retrieve email from the JSON data
    $email = $data['email'];

    // Check if a document with the provided email exists
    $existingDoc = $collection->findOne(['email' => $email]);

    // If a document exists, update it; otherwise, insert new data
    if ($existingDoc) {
        try {
            $result = $collection->updateOne(
                ['email' => $email],
                ['$set' => $data]
            );

            if ($result->getModifiedCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Data updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update data']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    } else {
        // Insert new data if no existing document found
        try {
            $result = $collection->insertOne($data);

            if ($result->getInsertedCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Data inserted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to insert data']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
