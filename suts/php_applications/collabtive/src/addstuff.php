<?php
include("init.php");

$runs = $_GET["runs"] > 0 ? $_GET["runs"] : 10;

$projectObj = new project();
$milestoneObj = new milestone();
$tasklistObj = new tasklist();
$taskObj = new task();
$messageObj = new message();

for ($i = 0; $i < $runs; $i++) {
    $endString = date("d.m.Y", strtotime("+" . ($i + 1) . "days"));
    $todayString = date("d.m.Y");

    $projectId = $projectObj->add("Testproject " . $i, "Testproject", $endString, 0, 1);
    for ($j = 0; $j < 5; $j++) {
        $milestoneId = $milestoneObj->add($projectId, "Testmilestone" . $i . $j, "", $todayString, $endString);
        $milestoneObj->assign($milestoneId, $_SESSION["userid"]);

        for ($k = 0; $k < 2; $k++) {

            $tasklistId = $tasklistObj->add_liste($projectId, "Testliste" . $j . $k, "", 0, $milestoneId);
            for ($l = 0; $l < 3; $l++) {
                $taskId = $taskObj->add($todayString, $endString, "Testtask" . $k . $l, "Test", $tasklistId, $projectId);
                $taskObj->assign($taskId, $_SESSION["userid"]);
            }

            $messageId = $messageObj->add($projectId,"Testmessage"  . $k . $l,"
            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.", $_SESSION["userid"], $_SESSION["username"], 0, $milestoneId );
        }
    }
}