<?php

class Condition
{
    //'게임 시작 로그'에 대한 조건을 반환합니다.
    public static function getGameLogCondition()
    {
       return array(
           "ameState.DebugPrintPower() - CREATE_GAME"
       ); 
    }

    // 플레이어 '직업 로그'에 대한 조건을 반환합니다.
    public static function getPlayerJobLogCondition()
    {
        return array(
            "FULL_ENTITY - Updating",
            "cardId=HERO",
        );
    }

    // 플레이어 이름에 대한 조건을 반환합니다.
    public static function getPlayerNameCondition()
    {
        return array(
            "PlayerName=",
        );
    }

    // 플레이어의 '차례'에 대한 조건을 반환합니다.
    public static function getPlayerOrderCondition()
    {
        return array(
            "CountMax=",
        );
    }

    // 플레이어의 '승패 결과'에 대한 조건을 반환합니다.
    public static function getPlayerLostCondition()
    {
        return array(
            "GameState.DebugPrintPower()",
            "value=LOST",
        );
    }

    public static function getPlayerWonCondition()
    {
        return array(
            "GameState.DebugPrintPower()",
            "value=WON",
        );
    }
}