<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
session_start();

// Include the database connection file
require 'db.php';

// Function to read SQL from a file and extract the description
function getQueryFromFile($filename) {
    $filepath = __DIR__ . "/../queries/" . $filename;
    if (file_exists($filepath)) {
        $fileContents = file_get_contents($filepath);
        $lines = explode("\n", $fileContents);
        $description = '';
        $sql = '';

        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            if (strpos($trimmedLine, '--') === 0) {
                // This is a comment line, extract the description
                $description .= substr($trimmedLine, 2) . ' ';
            } else {
                // This is part of the SQL query
                $sql .= $line . "\n";
            }
        }

        return ['description' => trim($description), 'sql' => trim($sql)];
    }
    return false;
}

// Parse the requested URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Route the request based on the URI
switch ($uri) {
    case '/':
        require 'src/pages/home.php';
        break;
        
    case '/search':
        require 'src/pages/search.php';
        break;

    case '/about':
        require 'src/pages/about.php';
        break;

    case '/contact':
        require 'src/pages/contact.php';
        break;

    case '/modifyRecords':
        require 'src/pages/modifyRecords.php';
        break;

    default:
        http_response_code(404);
        require 'src/pages/404.php';
        break;
}

// Check if a query parameter is set
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $results = [];
    $queryName = '';

    // Map query parameter to corresponding file
    $queryFileMap = [
        'query1' => '7.sql',
        'query2' => '8.sql',
        'query3' => '9.sql',
        'query4' => '10.sql',
        'query5' => '11.sql',
        'query6' => '12.sql',
        'query7' => '13.sql',
        'query8' => '14.sql',
        'query9' => '15.sql',
        'query10' => '16.sql',
        'query11' => '17.sql',
        'query12' => '18.sql'
    ];

    // Execute the appropriate query based on the query parameter
    if (array_key_exists($query, $queryFileMap)) {
        $queryData = getQueryFromFile($queryFileMap[$query]);
        if ($queryData !== false) {
            if (empty(trim($queryData['sql']))) {
                // Handle the case where the query file is empty
                $queryName = 'Error';
                $results = [['Error' => 'Query file is empty: ' . htmlspecialchars($queryFileMap[$query])]];
            } else {
                $stmt = $pdo->query($queryData['sql']);
                $results = $stmt->fetchAll();
                $queryName = 'Query ' . substr($query, 5) . ': ' . $queryData['description'];
            }
        } else {
            // Handle the case where the query file was not found
            $queryName = 'Error';
            $results = [['Error' => 'Query file not found: ' . htmlspecialchars($queryFileMap[$query])]];
        }
    } else {
        // Handle the case where the query parameter is invalid
        $queryName = 'Error';
        $results = [['Error' => 'Invalid query parameter']];
    }

    // Store results in session and redirect
    $_SESSION['results'] = $results;
    header('Location: src/pages/display_results.php?queryName=' . urlencode($queryName));
    exit();
}

ob_end_flush();
?>
