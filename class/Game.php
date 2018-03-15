<?php

include_once('LogParser.php');

// power.log에 기록되어 있는 게임 한 판 정보들을 담습니다.
class Game
{
    private $strStartLog;
    private $arrPlayer = array();

    public function __construct($strStartLog, $arrGameLog)
    {
        $this->strStartLog = $strStartLog;
        $this->setArrPlayer($arrGameLog);
    }

    // 객체의 데이터를 배열 형태로 바꾼 후 반환합니다.
    public function returnArrData()
    {
        return array(
            "index" => $this->strStartLog,
            "oneself" => $this->arrPlayer[0]->returnArrData(),
            "target" => $this->arrPlayer[1]->returnArrData()
        );
    }

    // 플레이어의 정보를 설정합니다.
    private function setArrPlayer($arrGameLog)
    {
        $arrPlayer = array();

        $arrJobLog = LogParser::getArrPlayerJobLog($arrGameLog);
        $arrNameLog = LogParser::getArrPlayerNameLog($arrGameLog);
        $arrOrderLog = LogParser::getArrPlayerOrderLog($arrGameLog);
        $strLostLog = LogParser::getArrPlayerLostLog($arrGameLog)[0];


        for($i=0; $i<2; $i++) {
            $arrPlayer[] = new Player($arrGameLog);
            $arrPlayer[$i]->setIsPlayer($i == 0 ? true : false);
            $arrPlayer[$i]->setJobNamewhithLog($arrJobLog[$i]);
            $arrPlayer[$i]->setPlayerNameWhithLog($arrNameLog[$i]);
            $arrPlayer[$i]->setOrderWithLog($arrOrderLog[$i]);
            $arrPlayer[$i]->setGameOutcome($strLostLog);
        }

        $this->arrPlayer = $arrPlayer;
    }

    // 전체 로그에서 게임을 분리해 그 갯수만큼 게임 객체를 생성 후 반환합니다.
    public static function getOGamePart()
    {
        $arrLogContext = LogParser::getArrLogContext();
        $arrGameStartLog = LogParser::getArrPartGameLog($arrLogContext);

        //print_r($arrGameStartLog);

        $arrOrder = array_keys($arrGameStartLog);
        array_push($arrOrder, count($arrLogContext));

        $iGameCount = count($arrGameStartLog);
        $arrGameObject = array();

        for($i=0; $i < $iGameCount; $i++)
        {
            $iStartOrder = $arrOrder[$i];
            $iEndOrder = $arrOrder[$i+1];
        
            $arrGamePartLog = self::getArrDivisionGameLog($iStartOrder, $iEndOrder, $arrLogContext);
            $arrGameObject[] = new Game($arrGameStartLog[$iStartOrder], $arrGamePartLog);
        }

        return $arrGameObject;
    }

    // 각 게임의 시작과 끝을 이용해 로그를 분리 후 반환합니다.
    private static function getArrDivisionGameLog($start, $end, $arrLogContext)
    {
        $arrDivisionedGameLog = array();

        for(; $start < $end; $start++) {
            $arrDivisionedGameLog[] = $arrLogContext[$start];
        }

        return $arrDivisionedGameLog;
    }
}