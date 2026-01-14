<?php
require("./init.php");
if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}
$taskobj = new globalTaskOverview();
$project = new project();
$user = new user();
$action = getArrayVal($_GET, "action");
$id = getArrayVal($_GET, "id");
$tid = getArrayVal($_GET, "tid");
$text = getArrayVal($_POST, "theText");

$cleanGet = cleanArray($_GET);

$template->addTemplateDir(CL_ROOT . "/plugins/globalTaskOverview/templates/");


if ($action == "tasks") {
    $title = $langfile["viewalltasks"];
    $template->assign("title", $title);
    $usr = getArrayVal($_GET, "usr");
    $projectFiltered = getArrayVal($_GET, "project");


    $users = $user->getAllUsers(1000000);

	$opros = $project->getProjects(1, 10000);
    $clopros = $project->getProjects(0, 10000);
    $template->assign("opros", $opros);
    $template->assign("clopros", $clopros);

    $template->assign("users", $users);
    $template->display( "tasksOverviewView.tpl");
}
if ($action == "tasksJson") {
    $title = $langfile["viewalltasks"];
    $template->assign("title", $title);
    $usr = getArrayVal($_GET, "usr");
    $projectFiltered = getArrayVal($_GET, "project");


    $limit = 150;
    if(isset($cleanGet["limit"]))
    {
        $limit = $cleanGet["limit"];
    }
    $offset = 0;
    if(isset($cleanGet["offset"]))
    {
        $offset = $cleanGet["offset"];
    }

    $tasksCount = 0;
    if ($usr > 0 && $projectFiltered > 0) {
        $tasksByUser = $taskobj->getMyTasks($usr, $limit, $offset);
        $tasksByProject = $taskobj->getOpenProjectTasks($projectFiltered, $limit, $offset);
        $tasks = array();
        foreach ($tasksByProject as $pt)
        {
            foreach ($tasksByUser as $ut)
            {
                if ($ut["ID"] == $pt["ID"])
                {
                    array_push($tasks, $pt);
                }
            }
        }
    } elseif ($usr > 0) {
        $tasks = $taskobj->getMyTasks($usr);
        $tasksCount = $taskobj->countMyTasks($usr);
    } elseif ($projectFiltered > 0) {
        $tasks = $taskobj->getOpenProjectTasks($projectFiltered, $limit, $offset);
        $tasksCount = $taskobj->countOpenProjectTasks($projectFiltered);

    } else {
        $tasks = $taskobj->getAllOpenTasks($limit, $offset);
        $tasksCount = $taskobj->countAllOpenTasks();
    }

    $globalTasks["items"] = $tasks;
    $globalTasks["count"] = $tasksCount;

    echo json_encode($globalTasks);
}
