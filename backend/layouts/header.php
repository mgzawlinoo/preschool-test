<?php include './auth-check.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Little Stars Preschool - Admin Dashboard</title>
    <base href="/backend/">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/bootstrap-icons.min.css">
    <link href="./css/style.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Schoolbell&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: lightyellow !important;
        }
        .page-item .page-link {
            border: 1px solid red !important;
            font-weight: normal !important;
        }
        .page-item.active .page-link {
            background-color: red !important;
        }
        h2.logo-title {
            color: yellow !important;
            font-weight:bold;
            font-family: 'Schoolbell', serif;
        }
    </style>
</head>

<body>