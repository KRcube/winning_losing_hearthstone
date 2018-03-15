<?php

// 하스스톤 캐릭터 (직업, 승패, 선공후공)
class Player
{
    private $bIsPlayer;            // 자신 / 상대 구분
    private $strJobName;           // 직업
    private $strName;              // 닉네임
    private $bOrder;               // 순서 (ture - 선공 / false - 후공)
    private $bOutcome;             // 승패 (true - 승리 / false - 패배)

    public function returnArrData()
    {
        return array(
            "isPlayer" => $this->bIsPlayer,
            "Name" => $this->strName,
            "JobName" => $this->strJobName,
            "Order" => $this->bOrder,
            "Outcome" => $this->bOutcome, 
        );
    }

    // 플레이어 여부 설정
    public function setIsPlayer($bIsPlayer) {
        $this->bIsPlayer = $bIsPlayer;
    }

    // 로그를 이용해 직업 명을 설정합니다.
    public function setJobNamewhithLog($strJobLog)
    {
        $jobCode = $this->getJobCodeWhithLog($strJobLog);
        $this->setJobNameWithCode(trim($jobCode));

        // test
        //echo($this->strJobName);
        //echo("<br>");
    }

    // 로그에서 직업 코드를 가져옵니다.
    private function getJobCodeWhithLog($strJobLog)
    {
        $startPos = strpos($strJobLog, "cardId=");
        $endPos = strpos($strJobLog, "player");

        $strJobCode = substr($strJobLog, $startPos, $endPos - $startPos);
        $strJobCode = str_replace("cardId=", '', $strJobCode);

        return $strJobCode;
    }

    // code를 이용해 직업 이름 설정합니다.
    private function setJobNameWithCode($strJobCode)
    {
        $strJobName = "";

        switch($strJobCode)
        {
            case "HERO_01":
                $strJobName = "전사";
                break;
            case "HERO_02":
                $strJobName = "주술사";
                break;
            case "HERO_03":
                $strJobName = "도적";
                break;
            case "HERO_04":
                $strJobName = "성기사";
                break;
            case "HERO_05":
                $strJobName = "사냥꾼";
                break;
            case "HERO_06":
                $strJobName = "드루이드";
                break;
            case "HERO_07":
                $strJobName = "흑마법사";
                break;
            case "HERO_08":
                $strJobName = "마법사";
                break;
            case "HERO_09":
                $strJobName = "사제";
                break;
        }

        $this->strJobName = $strJobName;
    }

    // 로그를 이용해 플레이어의 이름을 설정합니다.
    public function setPlayerNameWhithLog($strNameLog)
    {
        $this->strName = $this->getPlayerNameWhithLog($strNameLog);

        // test
        // echo($this->strName);
        // echo("<br>");
    }

    // 로그에서 플레이어 이름을 가져옵니다.
    private function getPlayerNameWhithLog($strNameLog)
    {
        $startPos = strpos($strNameLog, "PlayerName=");
        $endPos = strlen($strNameLog);

        $strName = substr($strNameLog, $startPos, $endPos - $startPos);
        $strName = str_replace("PlayerName=", '', $strName);

        return $strName;
    }

    // 로그에서 플레이어의 차례를 가져옵니다.
    public function setOrderWithLog($strOrderLog)
    {
        $count = $this->getCountMaxWhithLog($strOrderLog);

        if($count == 3) { 
            $this->bOrder = true; 
        } else {
            $this->bOrder = false;
        }

        // test
        //echo($this->bOrder ? "선공" : "후공");
        //echo("<br>");
    }

    // 로그에서 첫 시작시 가지는 카드 숫자를 가져옵니다.
    private function getCountMaxWhithLog($strOrderLog)
    {
        $startPos = strpos($strOrderLog, "CountMax=");
        $endPos = strlen($strOrderLog);

        $strMaxCount = substr($strOrderLog, $startPos, $endPos - $startPos);
        $strMaxCount = str_replace("CountMax=", '', $strMaxCount);

        return $strMaxCount;
    }

    // 패배 로그르 통해 플레이어의 승패 결과를 설정합니다.
    public function setGameOutcome($strLostLog)
    {   
        // 패배 확인
        if(strpos($strLostLog, $this->strName) === false) {
            $this->bOutcome = true;
        } else{
            $this->bOutcome = false;
        }
    }
}