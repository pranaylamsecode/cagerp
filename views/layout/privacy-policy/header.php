<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CAG ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- You can use Bootstrap or your custom stylesheet -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- For Font Awesome 4.7.0 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .navbar-custom {
            background-color: #f58220;
        }
        .navbar-custom .nav-link, .navbar-custom .navbar-brand {
            color: black;
            font-weight: bold;
        }
        .navbar-custom .btn-outline-dark {
            border-color: #f58220;
            color: black;
        }
        .logo {
            height: 60px;
        }

        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.8; }
        h1 { color: #333; }
        .content { max-width: 800px; margin: auto; }
        a.button {
            display: inline-block;
            padding: 10px 20px;
            background: #c0392b;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }
        a.button:hover { background: #a93226; }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 6px;
            max-width: 400px;
            text-align: center;
        }

        .modal input {
            width: 90%;
            padding: 10px;
            margin: 15px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .modal button {
            background: #2980b9;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal button:hover { background: #1f618d; }
        .close {
            float: right;
            font-size: 18px;
            cursor: pointer;
            color: #888;
        }
        .close:hover { color: #000; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom px-3">
        <a class="navbar-brand" href="#">
            <img src="<?= base_url('uploads/school_content/admin_logo/1.png') ?>" class="logo" alt="CAG Logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>">HOME</a></li>
                <li class="nav-item"><a class="nav-link" href="#">ADMISSION</a></li>
                <li class="nav-item"><a class="nav-link" href="#">COMPLAIN</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('privacypolicy') ?>">PRIVACY POLICY</a></li>

                <li class="nav-item"><a class="nav-link" href="<?= base_url('terms') ?>">TERMS OF USE</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('deleteaccount') ?>">DELETE ACCOUNT</a></li>
                <li class="nav-item"><a class="nav-link" href="#">CONTACT US</a></li>
                <li class="nav-item">
                    <a class="btn btn-outline-dark ml-2" href="#"><i class="fa fa-user"></i> LOGIN</a>
                </li>
            </ul>
        </div>
    </nav>
