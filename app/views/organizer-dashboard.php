<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Organizer Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 30px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .08);
            text-align: center;
        }

        .card h2 {
            margin: 0;
            font-size: 32px;
            color: #2a5298;
        }

        .card p {
            margin-top: 10px;
            color: #555;
            font-size: 15px;
        }
    </style>
</head>

<body>

    <?php include __DIR__ . '/partials/nav.php'; ?>


    <h1>📊 Organizer Statistics</h1>

    <div class="grid">
        <div class="card">
            <h2><?= $stats['total_matches'] ?></h2>
            <p>Total Matches</p>
        </div>

        <div class="card">
            <h2><?= $stats['approved'] ?></h2>
            <p>Approved Matches</p>
        </div>

        <div class="card">
            <h2><?= $stats['pending'] ?></h2>
            <p>Pending Matches</p>
        </div>

        <div class="card">
            <h2><?= $stats['refused'] ?></h2>
            <p>Refused Matches</p>
        </div>

        <div class="card">
            <h2><?= $stats['tickets_sold'] ?></h2>
            <p>Tickets Sold</p>
        </div>

        <div class="card">
            <h2><?= number_format($stats['total_revenue'], 2) ?> MAD</h2>
            <p>Total Revenue</p>
        </div>
    </div>

</body>

</html>