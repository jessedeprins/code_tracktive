<?php
function addXP($userId, $amount, $conn) {

    // XP toevoegen
    $sql = "UPDATE Profiel SET xp = xp + ? WHERE Gebruikernr = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $amount, $userId);
    $stmt->execute();

    // Nieuwe XP ophalen
    $sql2 = "SELECT xp FROM Profiel WHERE Gebruikernr = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $userId);
    $stmt2->execute();
    $result = $stmt2->get_result()->fetch_assoc();
    $xp = $result['xp'];

    // XP thresholds per rank
    $rankThresholds = [
        1 => 0,
        2 => 100,
        3 => 300,
        4 => 500,
        5 => 1000,
        6 => 2000,
        7 => 3500,
        8 => 5000
    ];

    // Rank bepalen
    $rank = 1;
    foreach ($rankThresholds as $r => $neededXP) {
        if ($xp >= $neededXP) {
            $rank = $r;
        }
    }

    // Rank opslaan
    $sql3 = "UPDATE Profiel SET `rank` = ? WHERE Gebruikernr = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("ii", $rank, $userId);
    $stmt3->execute();
}
?>
