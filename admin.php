<?php

// use function PHPSTORM_META\type;
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

header('content-type:text/html;charset=utf-8');
$html = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="icon" href="/bob.ico" type="image/x-icon" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <title>admin page</title>
        <style>
            header, footer {
            display: block;
            margin: 4px;
            padding: 5px;
            min-height: 100px;
            border: 1px solid white;
            border-radius: 7pt;
            background: white;
        }
        </style>
    </head>
    <body>
        <header></header>
        <div class="card justify-content-center align-items-center" style="width: 20rem; margin: 0 auto; float: none; margin-bottom: 10px; justify-content:center; align-items:center;">
            <img class="card-img-top" src="https://i.imgur.com/YQWUR9b.jpg" alt="Card image cap" loading="eager">
            <div class="card-body">
                <h3 class="card-title">Login</h3>
                <p class="card-title">Here is the secret place. I bet you can never login.</p>
                <p class="card-title"></p>
                <p class="card-title"></p>
                <form action="/admin" method="post">
                    <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="password" aria-label="password" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Login</button>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    </html>
    ';


    if (isset($_POST["password"])) {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom -> loadHTML($html);
        if ($_POST["password"] === "thisisbobpizza") {
            foreach ($dom -> getElementsByTagName("img") as $img) {
                $img -> setAttribute('src', 'https://i.imgur.com/dO045eV.png');
            }
            foreach ($dom -> getElementsByTagName("h3") as $h3) {
                $h3 -> nodeValue = "Really ... ";
            }
            $array = array("OK ... You really login ...", "Raise your hand and say you got me.", "And then I will change my password!!!");
            $index = 0;
            foreach ($dom -> getElementsByTagName("p") as $p) {
                $p -> nodeValue = $array[$index];
                $index += 1;
            }
            $dom -> getElementsByTagName("div") -> item(1) -> removeChild($dom -> getElementsByTagName("form") -> item(0));
            $html = $dom -> saveHTML();
        } else {
            http_response_code(404);
            foreach ($dom -> getElementsByTagName("img") as $img) {
                $img -> setAttribute('src', 'https://i.imgur.com/LiiYqU1.png');
            }
            foreach ($dom -> getElementsByTagName("h3") as $h3) {
                $h3 -> nodeValue = "HAHAHAHAHA";
            }
            $array = array("Are you kidding me?", "", "");
            $index = 0;
            foreach ($dom -> getElementsByTagName("p") as $p) {
                $p -> nodeValue = $array[$index];
                $index += 1;
            }
            $html = $dom -> saveHTML();
        }   
    }

    echo $html;
    libxml_use_internal_errors(false);
?>