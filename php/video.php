<?php
include_once('app.php');
global $app;
$app = new App();
$app->start_session();

if (!isset($_GET['id'])) {
    header('Location: main.php');
}
$statement = $app->getVideo($_GET['id']);
if (!$statement) {
    $app->head('Error');
    $app->nav();

    $app->showError();
} elseif (count($result = $statement->fetchAll()) == 0) {
    $app->head('Vídeo inválido');
    $app->nav();

    echo "<p>El vídeo especificado no existe</p>";
} else {
    $video = $result[0];
    
    $app->head($video[VIDEO_NAME]);
    $app->nav();

    echo "<video class='player' controls poster='".$video[VIDEO_PREVIEW]."' src='".$video[VIDEO_URI]."'>Tu navegador no es compatible con DerpPlay</video>";
    echo "<div class='below-video'>";
    echo "<div class='title'>".$video[VIDEO_NAME]."</div><br/>";
    echo "<a href=user.php?id='".$video[USER_ID]."'>";
    echo "<img src='".$video[USER_IMG]."' alt='Avatar de ".$video[USER_NAME]."' class='avatar'/>".$video[USER_NAME]."</a><br/>";
    echo "Subido el ".$app->formatDate($video[VIDEO_UPLOAD])."<br/>";
    echo "<p class='description'>".$video[VIDEO_DESC]."</p>";
    echo "</div>";
}
$app->foot();
?>