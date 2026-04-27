<?php
require("./init.php");

if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}

$commentobj = new taskComments();
$taskobj = new task();

$action = getArrayVal($_GET, "action");
$id = getArrayVal($_GET, "id");
$tid = getArrayVal($_GET, "tid");
$text = getArrayVal($_POST, "theText");

$template->addTemplateDir(CL_ROOT . "/plugins/taskComments/templates/");

if ($action == "showproject") {
    $comments = $commentobj->getCommentsByTask($tid);
    $template->assign("comments", $comments);
    $template->assign("countComments", count($comments));
    $template->display("showcomments.tpl");
}

if (!$userpermissions["tasks"]["edit"]) {
    $errtxt = $langfile["nopermission"];
    $noperm = $langfile["accessdenied"];
    $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
    $template->display("error.tpl");
    die();
}

if ($action == "add") {
    $tid = getArrayVal($_POST, "theTask");

    if ($commentobj->addComment($tid, $userid, $text)) {
        $comments = $commentobj->getCommentsByTask($tid);

        if ($settings["mailnotify"]) {
            $thetask = $taskobj->getTask($tid);
            $assigned = $taskobj->getUsers($tid);
            // make array one dimensional to have only usernames and userids listed
            $assigned = reduceArray($assigned);
			// loop through array
            foreach($assigned as $memKey => $member) {
                // make sure we have an integer
				$member = (int) $member;
				// if member == 0, the input var was a string (i.e. the username)
                if ($member > 0 and is_int($memKey)) {
                    $usr = (object) new user();
                    $user = $usr->getProfile($member);

                    if (!empty($user["email"])) {
                        // send email
                        $themail = new emailer($settings);
                        $themail->send_mail($user["email"], $langfile["commentaddedsubject"], $langfile["hello"] . ",<br /><br/>$langfile[commentwasadded] \"$thetask[title]\":<br />$text<br /><br /><a href = \"" . $url . "managetask.php?action=showtask&id=$thetask[project]&tid=$tid\">$langfile[openinbrowser]</a>");
                    }
                }
            }
        }

        $template->assign("comments", $comments);
        $template->assign("countComments", count($comments));
        $template->display("showcomments.tpl");
    }
} elseif ($action == "del") {
    if ($commentobj->deleteComment($id)) {
    	echo "ok";
	}
}
