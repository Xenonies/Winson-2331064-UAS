<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi database dengan error handling
try {
    require_once 'database.php';
} catch (Exception $e) {
    die("Error connecting to database: " . $e->getMessage());
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
        $stmt->execute([$delete_id]);
        $delete_success = "Customer deleted successfully!";
    } catch (PDOException $e) {
        $delete_error = "Error deleting customer: " . $e->getMessage();
    }
}

// Handle search
$search = $_GET['search'] ?? '';
$whereClause = '';
$params = [];

if (!empty($search)) {
    $whereClause = 'WHERE city LIKE ?';
    $params = ["%$search%"];
}

// Fetch customers dengan error handling
try {
    $stmt = $pdo->prepare("SELECT * FROM customers $whereClause ORDER BY created_at DESC");
    $stmt->execute($params);
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching customers: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">Customer<span>Manager</span></div>
        </div>
    </header>

    <div class="container">
        <!-- Add Customer Form -->
        <section class="section">
            <h2 class="section-title">Add New Customer</h2>
            <?php if (isset($_GET['success'])): ?>
                <div class="success-message">Customer added successfully!</div>
            <?php endif; ?>
            <?php if (isset($delete_success)): ?>
                <div class="success-message"><?= $delete_success ?></div>
            <?php endif; ?>
            <?php if (isset($delete_error)): ?>
                <div class="error-message"><?= $delete_error ?></div>
            <?php endif; ?>
            <form id="customerForm" action="process.php" method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" min="1" max="120">
                </div>
                
                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city">
                </div>
                
                <div class="form-group">
                    <label>Gender:</label>
                    <div class="radio-group">
                        <label><input type="radio" name="gender" value="Male"> Male</label>
                        <label><input type="radio" name="gender" value="Female"> Female</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Hobbies:</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="hobbies[]" value="Reading"> Reading</label>
                        <label><input type="checkbox" name="hobbies[]" value="Traveling"> Traveling</label>
                        <label><input type="checkbox" name="hobbies[]" value="Sports"> Sports</label>
                        <label><input type="checkbox" name="hobbies[]" value="Coding"> Coding</label>
                        <label><input type="checkbox" name="hobbies[]" value="Singing"> Singing</label>
                        <label><input type="checkbox" name="hobbies[]" value="Drawing"> Drawing</label>
                    </div>
                </div>
                
                <button type="submit" class="btn">Submit</button>
            </form>
        </section>

        <!-- Customer List -->
        <section class="section">
            <h2 class="section-title">Customer List</h2>
            
            <form id="searchForm" class="search-box">
                <input type="text" id="searchCity" placeholder="Search by City" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn">Search</button>
                <?php if (!empty($search)): ?>
                    <button type="button" id="clearSearch" class="btn" style="margin-left: 10px; background: #666;">Clear</button>
                <?php endif; ?>
            </form>
            
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Age</th>
                        <th>City</th>
                        <th>Gender</th>
                        <th>Hobbies</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($customers) > 0): ?>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><?= htmlspecialchars($customer['name']) ?></td>
                                <td><?= htmlspecialchars($customer['email']) ?></td>
                                <td><?= htmlspecialchars($customer['age']) ?></td>
                                <td><?= htmlspecialchars($customer['city']) ?></td>
                                <td><?= htmlspecialchars($customer['gender']) ?></td>
                                <td><?= htmlspecialchars($customer['hobbies']) ?></td>
                                <td>
                                    <button class="btn-delete" onclick="confirmDelete(<?= $customer['id'] ?>, '<?= htmlspecialchars($customer['name']) ?>')">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No customers found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3>Confirm Delete</h3>
            <p>Are you sure you want to delete customer: <span id="customerName"></span>?</p>
            <div class="modal-actions">
                <button id="confirmDelete" class="btn-danger">Yes, Delete</button>
                <button id="cancelDelete" class="btn-secondary">Cancel</button>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>