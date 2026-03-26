<?php
require("init.php");
$template->addTemplateDir(CL_ROOT . "/plugins/conference/templates/");
// Set the desktop icon in the top icon menue


$action = getArrayVal($_GET,"action");
$cleanGet = cleanArray($_GET);

if(!$action)
{
    $roomID = $cleanGet["roomID"];
    if($roomID != "") {
        $template->assign("roomID", $roomID);
    }

    $userObj = new user();
    $template->assign("allUsers", $userObj->getAllUsers(100000));

    $template->display("conference.tpl");
}