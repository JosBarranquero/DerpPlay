<?php
include_once('app.php');
global $app;
$app = new App();
$app->start_session();

if (!isset($_GET['id']) || $_GET['id'] == "") {
    header('Location: main.php');
}

$statement = $app->getUserInfo($_GET['id']);
if (!$statement) {
    $app->head('Error');
    $app->nav();

    $app->showError();
} elseif (count($result = $statement->fetchAll()) == 0) {
    header('Location: main.php');   // User doesn't exist
} else {
    $user = $result[0];
    $app->head($user[USER_NAME]);
    $app->nav();

    echo "<p><img src='".$user[USER_IMG]."' class='avatar' alt='Avatar de ".$user[USER_NAME]."'/>";
    echo $user[USER_NAME]."</p>";    

    $statement = $app->getUserVideos($_GET['id']);
    if (!$statement) {
        $app->showError();
    } elseif (count($result = $statement->fetchAll()) == 0) {
        echo "<p>Este usuario aún no ha subido vídeos</p>";
    } else {
        echo "<table class='videos'>";
    
        foreach ($result as $row) {
            echo "<tr>";

            echo "<td>";
            echo "<img src='".$row[VIDEO_PREVIEW]."' alt='".$row[VIDEO_NAME]."' class='preview'/>";
            echo "</td>";

            echo "<td>";
            echo "<a href='video.php?id=".$row[VIDEO_ID]."'><div class='title'>".$row[VIDEO_NAME]."</div></a><br>";
            echo "<div class='upload-date'>Subido el ".$app->formatDate($row[VIDEO_UPLOAD])."</div>";
            echo "</td>";
            
            echo "</tr>";
        }

        echo "</table>";
    }
}

$app->foot();
?>
