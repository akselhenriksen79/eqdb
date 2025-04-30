<?php
// Simple test script for SQLite3 database

// Database connection
function getConnection() {
    $db = new SQLite3('everquest_items.db');
    return $db;
}

// Test database connection
try {
    $db = getConnection();
    echo "Database connection successful!<br>";
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Test query - count total items
$result = $db->query("SELECT COUNT(*) as count FROM items");
$row = $result->fetchArray(SQLITE3_ASSOC);
echo "Total items in database: " . $row['count'] . "<br>";

// Test query - get table structure
echo "<h3>Database Tables:</h3>";
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
echo "<ul>";
while ($table = $tables->fetchArray(SQLITE3_ASSOC)) {
    echo "<li>" . $table['name'] . "</li>";
}
echo "</ul>";

// Test query - sample data from items table
echo "<h3>Sample Items:</h3>";
$items = $db->query("SELECT * FROM items LIMIT 5");
echo "<table border='1'>";
$first = true;
while ($item = $items->fetchArray(SQLITE3_ASSOC)) {
    if ($first) {
        echo "<tr>";
        foreach (array_keys($item) as $column) {
            echo "<th>" . htmlspecialchars($column) . "</th>";
        }
        echo "</tr>";
        $first = false;
    }

    echo "<tr>";
    foreach ($item as $value) {
        echo "<td>" . htmlspecialchars($value) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

$db->close();
?>