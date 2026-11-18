<!DOCTYPE html>
<html>
<head>
    <title>Subscription Expired</title>
    <link rel="stylesheet" href="<?= base_url('backend/bootstrap/css/bootstrap.min.css') ?>">
    <style>
        body {
            background: #f4f6f9;
        }
        .expired-box {
            max-width: 520px;
            margin: 10% auto;
            background: #ffffff;
            border-radius: 6px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }
        .expired-box h2 {
            color: #d9534f;
            margin-bottom: 15px;
        }
        .expired-box p {
            font-size: 15px;
            color: #555;
        }
        .expired-box .btn {
            margin: 10px 5px 0;
        }
        .expired-date {
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }
    </style>
</head>
<body>

<div class="expired-box">
    <h2>🚫 Subscription Expired</h2>

    <p>
        Your ERP subscription expired on
        <div class="expired-date">
            <?= date('d M Y', strtotime($end_date)) ?>
        </div>
    </p>

    <p>
        To continue using the system, please renew your subscription.
    </p>

    <a href="mailto:support@educron.com" class="btn btn-danger">
        Renew Subscription
    </a>

    <a href="<?= base_url('site/logout') ?>" class="btn btn-default">
        Logout
    </a>
</div>

</body>
</html>

