<?php

require_once("classes/Pastor.php");

echo "Keywords <br>";

$pastor = Pastor::getInstance();
$keywords = $pastor->getKeywords();

echo "<table>";
foreach ($keywords as $id => $text) {
    echo "<tr>";
    echo "<td> $id </td> <td> $text </td>";
    echo "</tr>";
}
echo "</table>";