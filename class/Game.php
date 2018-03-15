<?php

include_once('LogParser.php');

// power.log에 기록되어 있는 게임 한 판 정보들을 담습니다.
class Game
{
    Const SAVE_PATH = './resource/OutcomeData/outcome';

    private $strStartLog;
    private $arrPlayer = array();

    private static $arrGameIndex = array();

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

    // JSON형식으로 저장합니다.
    public function saveJsonFormatFile()
    {
        $strFileName = $this->getSaveFileName();
        $oOutcomeFile = fopen($strFileName, "w");

        $strJSONData = json_encode($this->returnArrData(), JSON_UNESCAPED_UNICODE);
        fwrite($oOutcomeFile, $strJSONData);
        fclose($oOutcomeFile);
    }

    // 파일의 이름을 가져옵니다.
    private function getSaveFileName()
    {
        $iGameCount = 1;
        $strPath = "";

        while(true) 
        {
            $strPath = Game::SAVE_PATH.$iGameCount.".txt";
            if(!file_exists($strPath)) break;

            $iGameCount += 1;
        }

        return $strPath;
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

    public static function returnAllGame()
    {
        $arrSavedAllGameData = array_reverse(self::loadSavedAllGameData());
        $arrNewAllGameData = self::loadNewAllGameData();
        $iNewGameCount = count($arrNewAllGameData);

        if($iNewGameCount != 0) 
        {
            for($i=0; $i < count($iNewGameCount); $i++)  {
                array_unshift($arrSavedAllGameData, $arrNewAllGameData[$i]);
            }
        }

        return $arrSavedAllGameData;
    }

    // 전체 로그에서 게임을 분리해 그 갯수만큼 게임 객체를 생성 후 반환합니다.
    private static function loadNewAllGameData()
    {
        $arrLogContext = LogParser::getArrLogContext();
        $arrGameStartLog = LogParser::getArrPartGameLog($arrLogContext);

        $arrOrder = array_keys($arrGameStartLog);
        array_push($arrOrder, count($arrLogContext));

        $iGameCount = count($arrGameStartLog);
        $arrNewGameData = array();

        for($i=0; $i < $iGameCount; $i++)
        {
            $iStartOrder = $arrOrder[$i];
            $iEndOrder = $arrOrder[$i+1];

            $strGameStartLog = $arrGameStartLog[$iStartOrder];

            if(!in_array($strGameStartLog, self::$arrGameIndex)) 
            {
                $arrGamePartLog = self::getArrDivisionGameLog($iStartOrder, $iEndOrder, $arrLogContext);
                $oGameObject = new Game($strGameStartLog, $arrGamePartLog);
                $oGameObject->saveJsonFormatFile();

                $arrNewGameData[] = $oGameObject->returnArrData();
            }
        }

        return $arrNewGameData;
    }

    // 저장되어 있는 모든 게임들을 불러옵니다.
    private static function loadSavedAllGameData()
    {
        $iGameCount = 1;
        $strPath = "";
        $arrSavedGame = array();

        while(true) 
        {
            $strPath = Game::SAVE_PATH.$iGameCount.".txt";
            if(!file_exists($strPath)) break;

            $foutcomeFile = fopen($strPath, "r");
            $strContext = fread($foutcomeFile, filesize($strPath));
            $stdOutcome = json_decode($strContext);

            self::$arrGameIndex[] = $stdOutcome->index;
            $arrSavedGame[] = $stdOutcome;

            $iGameCount += 1;
        }

        return $arrSavedGame;
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