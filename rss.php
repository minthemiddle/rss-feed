<?php
// Connect to SQLite database
$db_file = 'db.sqlite';

try {
 $pdo = new PDO('sqlite:' . $db_file);
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
 exit('Failed to connect to database!');
}

// Fetch data from the database
$stmt = $pdo->prepare('SELECT * FROM news ORDER BY timestamp DESC');
$stmt->execute();
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generate the RSS feed
header('Content-Type: application/xml; charset=utf-8');
echo <<<XML
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
<channel>
<title>Your Website Name</title>
<link>https://example.com/</link>
<description>Short description of your website or feed</description>
<language>en-us</language>
XML;

foreach ($news as $item) {
  echo '<item>' . PHP_EOL;
  echo '<title>' . htmlspecialchars($item['title'], ENT_QUOTES) . '</title>' . PHP_EOL;
  echo '<pubDate>' . date('r', strtotime($item['timestamp'])) . '</pubDate>' . PHP_EOL;
  echo '</item>' . PHP_EOL;
}

echo <<<XML
</channel>
</rss>
XML;
?>
