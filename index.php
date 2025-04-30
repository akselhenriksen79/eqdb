<?php
// Database connection
function getConnection() {
    $db = new SQLite3('everquest_items.db');
    return $db;
}

// Get page from URL
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Handle search
$searchResults = [];
$searchQuery = '';
if ($page == 'search' && isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    $db = getConnection();
    $stmt = $db->prepare("SELECT * FROM items WHERE name LIKE :query LIMIT 50");
    $stmt->bindValue(':query', '%'.$searchQuery.'%', SQLITE3_TEXT);
    $result = $stmt->execute();

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $searchResults[] = $row;
    }
}

// Handle item detail
$item = null;
$stats = [];
$effects = [];
if ($page == 'item' && isset($_GET['id'])) {
    $itemId = $_GET['id'];
    $db = getConnection();

    // Get item
    $stmt = $db->prepare("SELECT * FROM items WHERE item_id = :id");
    $stmt->bindValue(':id', $itemId, SQLITE3_TEXT);
    $result = $stmt->execute();
    $item = $result->fetchArray(SQLITE3_ASSOC);

    // Get stats
    $stmt = $db->prepare("SELECT * FROM stats WHERE item_id = :id");
    $stmt->bindValue(':id', $itemId, SQLITE3_TEXT);
    $result = $stmt->execute();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $stats[] = $row;
    }

    // Get effects
    $stmt = $db->prepare("SELECT * FROM effects WHERE item_id = :id");
    $stmt->bindValue(':id', $itemId, SQLITE3_TEXT);
    $result = $stmt->execute();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $effects[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>EverQuest Item Database</title>
</head>

<body>
	<?php if ($page == 'home'): ?>
	<!-- Home Page -->
	<h1>EverQuest Item Database</h1>
	<form action="index.php" method="get">
		<input type="hidden" name="page" value="search">
		<input type="text" name="query" placeholder="Search items...">
		<button type="submit">Search</button>
	</form>

	<?php elseif ($page == 'search'): ?>
	<!-- Search Results Page -->
	<h1>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h1>
	<a href="index.php">Back to Search</a>

	<ul>
		<?php foreach ($searchResults as $item): ?>
		<li>
			<a href="index.php?page=item&id=<?php echo $item['item_id']; ?>">
				<?php echo htmlspecialchars($item['name']); ?>
			</a>
			(<?php echo htmlspecialchars($item['type']); ?>)
		</li>
		<?php endforeach; ?>
	</ul>

	<?php elseif ($page == 'item' && $item): ?>
	<!-- Item Detail Page -->
	<a href="index.php">Home</a> | <a href="javascript:history.back()">Back</a>

	<h1><?php echo htmlspecialchars($item['name']); ?></h1>
	<p>Type: <?php echo htmlspecialchars($item['type']); ?></p>
	<p>Slot: <?php echo htmlspecialchars($item['slot']); ?></p>

	<?php if (!empty($item['ac'])): ?>
	<p>AC: <?php echo htmlspecialchars($item['ac']); ?></p>
	<?php endif; ?>

	<?php if (!empty($item['level_requirement'])): ?>
	<p>Required Level: <?php echo htmlspecialchars($item['level_requirement']); ?></p>
	<?php endif; ?>

	<h2>Stats</h2>
	<ul>
		<?php foreach ($stats as $stat): ?>
		<li>
			<?php echo htmlspecialchars($stat['stat_name']); ?>:
			<?php echo htmlspecialchars($stat['value']); ?>
			<?php if ($stat['is_heroic']): ?> (Heroic)<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>

	<h2>Effects</h2>
	<ul>
		<?php foreach ($effects as $effect): ?>
		<li>
			<?php echo htmlspecialchars($effect['effect_name']); ?>:
			<?php echo htmlspecialchars($effect['effect_value']); ?>
		</li>
		<?php endforeach; ?>
	</ul>

	<?php if (!empty($item['source_url'])): ?>
	<p><a href="<?php echo htmlspecialchars($item['source_url']); ?>" target="_blank">View Original Source</a></p>
	<?php endif; ?>
	<?php endif; ?>
</body>

</html>