<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        
        .content {
            margin: auto;
            width: 50%; 
            height: 100%;
            text-align: center;
            max-width: 800px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .content h1 {
            font-size: 72px;
            margin: 0;
        }
        .content p {
            font-size: 24px;
            margin: 10px 0;
        }
        .content a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            color: #fff;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .content a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/../components/header.php'; ?>

    <div class="content">
        <h1>404</h1>
        <p>Oops! The page you're looking for doesn't exist.</p>
        
    </div>

    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
