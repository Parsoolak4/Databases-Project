<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YSMC</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="../../public/css/header.css">
</head>
<body>
<header>
    <div class="container">
        <h1 class="logo">Montréal Youth Soccer Club</h1>
        
        <nav id="nav-menu" class="nav-menu">
            <ul>
                <li><a href="/src/pages/home.php" class="<?= $uri == '/' ? 'active' : '' ?>">Home</a></li>
                <li><a href="/src/pages/search.php" class="<?= $uri == '/search' ? 'active' : '' ?>">Search</a></li>
                <li><a href="/src/pages/modifyRecords.php" class="<?= $uri == '/add' ? 'active' : '' ?>">Modify Records</a></li>
                <li><a href="/src/pages/about.php" class="<?= $uri == '/about' ? 'active' : '' ?>">About</a></li>
                <li><a href="/src/pages/contact.php" class="<?= $uri == '/contact' ? 'active' : '' ?>">Contact</a></li>
            </ul>
        </nav>



    </div>
</header>

</body>
</html>
