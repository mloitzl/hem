<?php
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        img {
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        .loading-text {
            margin-top: 10px;
            font-size: 26px;
            color: #333;
            font: small "Verdana",Trebuchet MS,Arial,sans-serif
        }
    </style>
</head>
<body>
    <img src="/hem/templates/img/logo40.gif" alt="Loading...">
    <div class="loading-text">Loading ...</div>
    <script>
        setTimeout(function() {
            window.location.href = "/hem/";
        }, 2000);
    </script>
</body>
</html>';
// header("Location: /hem/");
// exit();
?>