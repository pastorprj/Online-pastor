<?php

require_once("classes/Pastor.php");

echo "Answers <br>";

$pastor = Pastor::getInstance();
$answers = $pastor->getAnswers();

echo "<table>";
foreach ($answers as $id => $text) {
    echo "<tr>";
    echo "<td> $id </td> <td> $text </td>";
    echo "<td>" .  getKeywordsString($id) .  "</td>";
    echo "</tr>";
}
echo "</table>";


function getKeywordsString($answerId) {
    global $pastor;
    $result = "";
    $keywordIds = $pastor->getKeywordIdsForAnswerId($answerId);
    $keywords = $pastor->getKeywords();
    foreach ($keywordIds as $keywordId) {
        $result .= $keywords[$keywordId] . "    ";
    }
    return $result;
}