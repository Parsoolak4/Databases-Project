<?php
session_start();

// Get the query name from the URL parameter
$queryName = isset($_GET['queryName']) ? $_GET['queryName'] : 'Query Results';

// Get the results from the session
$results = isset($_SESSION['results']) ? $_SESSION['results'] : [];

// Extract query number and description
$queryParts = explode(': ', $queryName, 2);
$queryNumber = $queryParts[0];
$queryDescription = isset($queryParts[1]) ? $queryParts[1] : '';

//!!!!!!!!!!!TODO this does not actually update the Database
// Handle form submission for modifying entries
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id = $_POST['id'];
    $columns = isset($results[0]) ? array_keys($results[0]) : [];
    $tableName = 'your_table_name'; // Replace with your actual table name

    // Prepare an update query
    $setClause = [];
    $values = [];
    foreach ($columns as $column) {
        if ($column !== 'id' && isset($_POST[$column])) { // Only update if the column is modified
            $setClause[] = "$column = ?";
            $values[] = $_POST[$column];
        }
    }
    $values[] = $id; // Add the id value for the WHERE clause

    if (!empty($setClause)) {
        $query = "UPDATE $tableName SET " . implode(', ', $setClause) . " WHERE id = ?";

        // Execute the query
        require_once '../../db.php'; // Ensure the connection is available
        if ($pdo) { // Using the $pdo object from the global scope
            $stmt = $pdo->prepare($query);
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($pdo->errorInfo()[2]));
            }
            if ($stmt->execute($values)) {
                echo "Update successful.";
            } else {
                echo "Update failed.";
            }
        } else {
            die('Database connection not established.');
        }

        // Reload the page to reflect changes
        header("Location: " . $_SERVER['PHP_SELF'] . "?queryName=" . urlencode($queryName));
        exit;
    }
}

// Clear the session results to prevent reuse on refresh
unset($_SESSION['results']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($queryNumber); ?></title>
    <link rel="stylesheet" href="../../public/css/display_results.css">
    <script>
        function enableEditing(index) {
            const table = document.querySelector("table tbody");
            const row = table.rows[index];
            const cells = Array.from(row.cells);

            cells.forEach((cell, i) => {
                if (i < cells.length - 1) { // Exclude the last column (Actions)
                    const originalContent = cell.innerText;
                    cell.setAttribute('data-original-content', originalContent);
                    cell.innerHTML = `<input type="text" name="${cell.getAttribute('data-column')}" value="${originalContent}" oninput="trackChanges(this)">`;
                }
            });

            // Replace Modify button with Save and Cancel buttons
            const actionCell = cells[cells.length - 1];
            actionCell.innerHTML = `
                <button onclick="saveChanges(${index})">Save</button>
                <button onclick="cancelEditing(${index})">Cancel</button>
            `;
        }

        function trackChanges(input) {
            input.setAttribute('data-modified', 'true');
        }

        function saveChanges(index) {
            const form = document.createElement('form');
            form.method = 'POST';
            const table = document.querySelector("table tbody");
            const row = table.rows[index];
            const cells = Array.from(row.cells);

            cells.forEach((cell, i) => {
                const input = cell.querySelector('input[data-modified="true"]');
                if (input) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = input.name;
                    hiddenInput.value = input.value;
                    form.appendChild(hiddenInput);
                }
            });

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            idInput.value = cells[0].innerText; // Assuming 'id' is the first column
            form.appendChild(idInput);

            const saveInput = document.createElement('input');
            saveInput.type = 'hidden';
            saveInput.name = 'save';
            saveInput.value = 'true';
            form.appendChild(saveInput);

            document.body.appendChild(form);
            form.submit();
        }

        function cancelEditing(index) {
            const table = document.querySelector("table tbody");
            const row = table.rows[index];
            const cells = Array.from(row.cells);
            cells.forEach((cell, i) => {
                if (i < cells.length - 1) { // Exclude the last column (Actions)
                    const originalContent = cell.getAttribute('data-original-content');
                    cell.innerHTML = originalContent;
                }
            });

            // Restore Modify and Delete buttons
            const actionCell = cells[cells.length - 1];
            actionCell.innerHTML = `
                <button class="modify" onclick="enableEditing(${index})">Modify</button>
                <button class="delete" onclick="deleteEntry(${index})">Delete</button>
            `;
        }

        function deleteEntry(index) {
            const table = document.querySelector("table tbody");
            if (confirm("Are you sure you want to delete this entry?")) {
                table.deleteRow(index);
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($queryNumber); ?></h1>
        <p class="description"><?php echo htmlspecialchars($queryDescription); ?></p>
        <?php if (empty($results)): ?>
            <p>No records found.</p>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <?php foreach (array_keys($results[0]) as $column): ?>
                                <th><?php echo htmlspecialchars($column); ?></th>
                            <?php endforeach; ?>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $index => $row): ?>
                            <tr>
                                <?php foreach ($row as $column => $value): ?>
                                    <td data-column="<?php echo htmlspecialchars($column); ?>"><?php echo htmlspecialchars($value); ?></td>
                                <?php endforeach; ?>
                                <td>
                                    <button class="modify" onclick="enableEditing(<?php echo $index; ?>)">Modify</button>
                                    <button class="delete" onclick="deleteEntry(<?php echo $index; ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <a href="/">Back to Queries</a>
    </div>
</body>
</html>
