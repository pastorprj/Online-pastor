<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aemelyanov
 * Date: 2/1/12
 * Time: 11:08 PM
 * To change this template use File | Settings | File Templates.
 */

class Pastor {
    const DB_LOGIN = "root";
    const DB_PASSWORD = "analpastor";
    const DB_NAME = "pastor";

    const SELECT_KEYWORDS_QUERY = "select * from keywords";
    const SELECT_ANSWERS_QUERY = "select * from answers";
    const SELECT_LINK_QUERY = "select * from link";

    private $dbHandle = null;
    
    private static $pastor = null;
    
    private $keywords = null;
    private $answers = null;
    private $keywordIdToAnswerIds = null;

    public static function getInstance() {
        if (!self::$pastor) {
            self::$pastor = new Pastor();
        }
        return self::$pastor;
    }

    public function getKeywords() {
        return $this->keywords;
    }

    public function getAnswers() {
        return $this->answers;
    }

    public function getLinks() {
        return $this->keywordIdToAnswerIds;
    }

    public function getKeywordIdsForAnswerId($answerId) {
        $result = array();
        foreach ($this->keywordIdToAnswerIds as $keywordId => $answerIds) {
            if (in_array($answerId, $answerIds)) {
                array_push($result, $keywordId);
            }
        }
        return $result;
    }

    public function getKeywordId($keywordText) {
        foreach ($this->keywords as $id=>$text) {
            if ($text == $keywordText)
                return $id;
        }
        return null;
    }

    private function __construct() {
        $this->dbHandle = mysql_connect('localhost', self::DB_LOGIN, self::DB_PASSWORD);
        if (!$this->dbHandle)
            die("Unable to connect to db");
        mysql_select_db(self::DB_NAME, $this->dbHandle) or die( "Unable to select database");
        $this->initKeywords();
        $this->initAnswers();
        $this->initLink();
    }

    private function initKeywords() {
        $this->keywords = array();
        $keywordsTable = mysql_query(self::SELECT_KEYWORDS_QUERY);
        $numberKeywords = mysql_num_rows($keywordsTable);
        for ($i=0;$i<$numberKeywords;++$i) {
            $id = mysql_result($keywordsTable, $i, "id");
            $text = mysql_result($keywordsTable, $i, "text");
            $this->keywords[$id] = $text;
        }
    }

    private function initAnswers() {
        $this->answers = array();
        $answersTable = mysql_query(self::SELECT_ANSWERS_QUERY);
        $numberAnswers = mysql_num_rows($answersTable);
        for ($i=0;$i<$numberAnswers;++$i) {
            $id = mysql_result($answersTable, $i, "id");
            $text = mysql_result($answersTable, $i, "text");
            $this->answers[$id] = $text;
        }
    }

    private function initLink() {
        $this->keywordIdToAnswerId = array();
        $linksTable = mysql_query(self::SELECT_LINK_QUERY);
        $numberLinks = mysql_num_rows($linksTable);
        for ($i=0;$i<$numberLinks;++$i) {
            $keywordId = mysql_result($linksTable, $i, "keyword_id");
            $answerId = mysql_result($linksTable, $i, "answer_id");
            if (isset($this->keywordIdToAnswerIds[$keywordId])) {
                array_push($this->keywordIdToAnswerIds[$keywordId], $answerId);
            } else {
                $this->keywordIdToAnswerIds[$keywordId] = array($answerId);
            }
        }
    }

    public function process($question) {
        $keywordsTable = mysql_query(self::SELECT_KEYWORDS_QUERY);
        $numberKeywords = mysql_num_rows($keywordsTable);
        for ($i=0;$i<$numberKeywords;++$i) {
            $id = mysql_result($keywordsTable, $i, "id");
            $text = mysql_result($keywordsTable, $i, "text");
            echo "$id $text <br>";
        }

        return "SomeShit " . $question . "<br>";
    }

    public function __destruct() {
        mysql_close();
    }
}
