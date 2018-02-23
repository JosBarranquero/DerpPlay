<?php
include_once('app.php');
global $app;
$app = new App();
$app->start_session();

if (!isset($_GET['query']) || $_GET['query'] == "")
    header('Location: main.php');

$app->head("Buscando ".$_GET['query']);
$app->nav();

$statement = $app->searchVideo($_GET['query']);

if (!$statement) {
    $app->showError();
} else if (count($result = $statement->fetchAll()) == 0) {
    echo "<p>No hay vídeos que coincidan con tu búsqueda</p>";
} else {
    echo "<table class='videos'>";
    
    foreach ($result as $row) {
        echo "<tr>";

        echo "<td>";
        echo "<img src='".$row[VIDEO_PREVIEW]."' alt='".$row[VIDEO_NAME]."' class='preview'/>";
        echo "</td>";

        echo "<td>";
        echo "<a href='video.php?id=".$row[VIDEO_ID]."'><div class='title'>".$row[VIDEO_NAME]."</div></a><br>";
        echo "por <a href='user.php?id=".$row[USER_ID]."'>".$row[USER_NAME]."</a> el ".$app->formatDate($row[VIDEO_UPLOAD]);
        echo "</td>";
        
        echo "</tr>";
    }

    echo "</table>";
}

$app->foot();
?>