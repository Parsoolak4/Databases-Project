<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        .content {
            margin: auto;
            width: 50%; 
            text-align: center;
            max-width: 800px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/header.php'; ?>

    <div class="content">
        <h1>About Us</h1>
        <p>In this project, our team has developed a miniature database application system. We have evaluated a number of queries and transactions against the database using a DBMS.</p>
        <h2>Project Description</h2>
        <p>We created this application to help the Montréal Youth Soccer Club manage and organize their operations by keeping track of the club's members. The Montréal Youth Soccer Club (MYSC) is a nonprofit organization dedicated to developing, promoting, and enhancing youth soccer in different areas. The club provides its members with services tailored to their long-term development to become professional soccer players. The club offers an optimal soccer program to members aged between 4 and 10 years old. YSC can have one main location as the Head location and many other branches spread across different areas. The players join either Boys teams or Girls teams. Each club member can be associated with one location at any given time but can move from one location to another over time.</p>
    </div>

    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
