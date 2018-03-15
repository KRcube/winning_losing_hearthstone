<?php

header('Content-Type: text/html; charset=UTF-8');
const PATH = "/Applications/Hearthstone/Logs/Power.log";

function __autoload($name) {
    include 'class/'.$name.'.php';
}

$arrGame = Game::getOGamePart();
for($i=0; $i < count($arrGame); $i++)
{
    print_r($arrGame[$i]->returnArrData());
    echo("<br> <br>");
}