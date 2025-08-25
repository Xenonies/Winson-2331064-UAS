<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $age = $_POST['age'] ?? '';
    $city = $_POST['city'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $hobbies = isset($_POST['hobbies']) ? implode(', ', $_POST['hobbies']) : '';
    
    // Validate required fields
    if (empty($name) || empty($email)) {
        die('Name and email are required');
    }
    
    // Insert into database
    try {
        $stmt = $pdo->prepare("INSERT INTO customers (name, email, age, city, gender, hobbies) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $age, $city, $gender, $hobbies]);
        
        header('Location: index.php?success=1');
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit();
}
?>