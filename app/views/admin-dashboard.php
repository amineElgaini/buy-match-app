<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f3f6;
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
            text-align: center;
        }

        .card h2 {
            margin: 0;
            font-size: 30px;
            color: #c0392b;
        }

        .card p {
            margin-top: 10px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>

<body>

    <?php include __DIR__ . '/partials/nav.php'; ?>


    <h1>🛠 Admin Statistics</h1>

    <div class="grid">
        <div class="card">
            <h2><?= $stats['users'] ?></h2>
            <p>Total Users</p>
        </div>

        <div class="card">
            <h2><?= $stats['organizers'] ?></h2>
            <p>Organizers</p>
        </div>

        <div class="card">
            <h2><?= $stats['matches'] ?></h2>
            <p>Total Matches</p>
        </div>

        <div class="card">
            <h2><?= $stats['approved'] ?></h2>
            <p>Approved Matches</p>
        </div>

        <div class="card">
            <h2><?= $stats['pending'] ?></h2>
            <p>Pending Approval</p>
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