<?php
include_once('app.php');
global $app;
$app = new App();
$app->start_session();
$app->head("Página principal");
$app->nav();

$statement = $app->getVideos();

if (!$statement) {
    $app->showError();
} else if (count($result = $statement->fetchAll()) == 0) {
    echo "<p>No hay ningún vídeo... Sé el primero en subir uno</p>";
} else {
    echo "<table class='videos'>";
    
    foreach ($result as $row) {
        echo "<tr>";

        echo "<td>";
        echo "<img src='".$row[VIDEO_PREVIEW]."' alt='".$row[VIDEO_NAME]."' class='preview'/>";
        echo "</td>";

        echo "<td>";
        echo "<a href='video.php?id=".$row[VIDEO_ID]."'><div class='title'>".$row[VIDEO_NAME]."</div></a><br>";
        echo "por <a href='user.php?id=".$row[USER_ID]."'>".$row[USER_NAME]."</a> <div class='upload-date'>el ".$app->formatDate($row[VIDEO_UPLOAD])."</div>";
        echo "</td>";
        
        echo "</tr>";
    }

    echo "</table>";
}

$app->foot();
?>