<?php

const PATH = "/Applications/Hearthstone/Logs/Power.log";

function __autoload($name) {
    include 'class/'.$name.'.php';
}

$arrAllGame = Game::returnAllGame();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Winning-or-losing-Hearthstone</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="jumbotron" style="text-align:center; margin-top:3px;">
            <b style="font-size:20px">하스스톤 전적 뷰</b>
        </div>
        <?php foreach ($arrAllGame as $stdGame): ?> 
        <div class="panel panel-default">
            <div class="panel-heading" style="text-align:center;">
                <b><?php Printer::printTile($stdGame); ?></b>
            </div>
            <div class="panel-body">
                <div>
                    <span><?php Printer::printOrder($stdGame); ?></span>
                </div>
                <div>승리 : <?php Printer::printWon($stdGame); ?></div>
                <div>패배 : <?php Printer::printLost($stdGame); ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>