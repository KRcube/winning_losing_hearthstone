<?php

class Printer
{
    // 제목을 출력합니다.
    public static function printTile($stdGame)
    {
        $player = $stdGame->oneself->JobName." [플레이어]";
        $target = $stdGame->target->JobName." [상대]";

        echo($player." vs ".$target);
    }

    // 승리 문구를 출력합니다.
    public function printWon($stdGame)
    {    
        if($stdGame->oneself->Outcome) {
            $str = "플레이어";
        } else {
            $str = "상대";
        }

        echo($str);
    }

    // 패배 문구를 출력합니다.
    public function printLost($stdGame)
    {    
        if(!$stdGame->oneself->Outcome) {
            $str = "플레이어";
        } else {
            $str = "상대";
        }

        echo($str);
    }

    // 두 명의 플레이어의 순서를 출력합니다.
    function printOrder($stdGame)
    {
        $strFirst = "선공은 ";
        $strLast = "후공은 ";

        if($stdGame->oneself->Order) {
            $strFirst = $strFirst."'플레이어', ";
            $strLast = $strLast."'상대방'이었습니다.";
        } else {
            $strFirst = $strFirst."'상대방', ";
            $strLast = $strLast."'플레이어'였습니다.";
        }

        echo($strFirst.$strLast);
    }
}