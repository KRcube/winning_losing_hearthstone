<?php

// 하스스톤의 'power.log'에서 필요한 정보를 가져옵니다.
class LogParser
{
    // 로그를 배열의 형태로 반환합니다.
    public static function getArrLogContext()
    {
        return explode("\n", self::getContextByFile());
    }
    
    // 게임의 시작점들을 찾은 후 배열 형태로 반환합니다. (순서 포함)
    public static function getArrPartGameLog($arrContext)
    {
        $arrPartGameCondition = Condition::getGameLogCondition();
        return self::getOrderNumberWhenCondition($arrContext, $arrPartGameCondition);
    }

    // 플레이어의 직업에 대한 로그를 찾은 후 배열 형태로 반환합니다.
    public static function getArrPlayerJobLog($arrContext)
    {
        $jobCondition = Condition::getPlayerJobLogCondition();
        return LogParser::getLogsWhenCondition($arrContext, $jobCondition);
    }

    // 플레이어의 이름에 대한 로그를 찾은 후 배열 형태로 반환합니다.
    public static function getArrPlayerNameLog($arrContext)
    {
        $playerNameCondition = Condition::getPlayerNameCondition();
        return LogParser::getLogsWhenCondition($arrContext, $playerNameCondition);
    }

    // 플레이어의 게임 순서에 대한 로그를 찾은 후 배열 형태로 반환합니다.
    public static function getArrPlayerOrderLog($arrContext)
    {
        $playerOrderCondition = Condition::getPlayerOrderCondition();
        return LogParser::getLogsWhenCondition($arrContext, $playerOrderCondition);
    }

     // 플레이어의 게임 순서에 대한 로그를 찾은 후 배열 형태로 반환합니다.
    public static function getArrPlayerLostLog($arrContext)
    {
        $playerLostCondition = Condition::getPlayerLostCondition();
        return LogParser::getLogsWhenCondition($arrContext, $playerLostCondition);
    }

    public static function getArrPlayerWonLog($arrContext)
    {
        $playerWonCondition = Condition::getPlayerWonCondition();
        return LogParser::getLogsWhenCondition($arrContext, $playerWonCondition);
    }

    // 조건을 통해 로그의 목록을 가져옵니다. (순서 포함)
    public static function getOrderNumberWhenCondition($arrContext, $arrCondition)
    {
        $arrOrder = array();
        $iContextLength = count($arrContext);

        for($i=0; $i < $iContextLength; $i++) 
        {
            $bResult = LogParser::isLogCondition($arrContext[$i], $arrCondition);
            
            if($bResult) {
                $arrOrder[$i] = $arrContext[$i];
            }
        }

        return $arrOrder;
    }

    // 조건을 통해 원하는 로그를 가져옵니다.
    public static function getLogsWhenCondition($arrContext, $arrCondition) 
    {
        $arrResult = array();
        $iContextLength = count($arrContext);

        for($i=0; $i < $iContextLength; $i++) 
        {
            $bResult = LogParser::isLogCondition($arrContext[$i], $arrCondition);
            
            if($bResult) {
                $arrResult[] = $arrContext[$i];
            }
        }

        return $arrResult;
    }

    // 로그 한 줄이 조건에 해당하는지 검사합니다.
    public static function isLogCondition($strContext, $arrCondition)
    {
        $iConditionCount = count($arrCondition); 
        $bresult = true;

        for($i=0; $i < $iConditionCount; $i++)
        {
            $bIs = strpos($strContext, $arrCondition[$i]);

            if($bIs === false) { 
                $bresult = false;
                break; 
            }
        }

        return $bresult;
    } 

    // 파일에서 로그를 전부 반환옵니다.
    private static function getContextByFile()
    {
        $powerlogFile = fopen(PATH, "r");
        return fread($powerlogFile, filesize(PATH));
    }
}