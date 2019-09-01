<?php
include('dao.php');
include('phpmailer524/class.phpmailer.php');

class App {
    protected $dao;

    function __construct() {
        $this->dao = new DerpDao();
    }

    function head($title) {
        echo "<!DOCTYPE html>";
        echo "<html lang=\"es\">
            <head>
                <meta charset=\"utf-8\"/>
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />
                <title>$title - DerpPlay</title>

                <link rel=\"icon\" type=\"image/png\" href=\"../img/logo-s.png\">
                <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/style.css\">
                <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap.min.css\">
            </head>
            <body>
                <script src=\"../js/jquery.js\"></script>
                <script src=\"../js/bootstrap.min.js\"></script>
                <header>
                    <a href=\"../index.php\" title=\"Página principal\"><img src=\"../img/logo.png\" alt=\"Logo DerpPlay\" class='logo'/></a>";
            if ($this->isLogged()) {
                echo "<form action=\"search.php\" method=\"GET\" class=\"search-form\">
                <input type=\"text\" name=\"query\" maxlength=\"45\">
                <input type=\"submit\" value=\"Buscar\"/>
                </form>";
            }
            echo "</header>";
    }

    function nav() {
        if (!$this->isLogged()) echo "<br/><br/>";
        echo" <nav><ul>";
        if ($this->isLogged()) {
            echo "<li><a href=\"main.php\">Inicio</a></li>";
            echo "<li><a href=\"user.php?id=".$this->getUserId($_SESSION['user'])."\">Mi cuenta</a></li>";
            echo "<li><a href='videoupload.php'>Subir vídeo</a></li>";
            echo "<li><a href=\"settings.php\">Ajustes</a></li>";
            echo "<li><a href=\"logout.php\">Cerrar sesión</a></li>";
        } else {
            echo "<li><a href=\"login.php\">Iniciar sesión</a></li>";
            echo "<li><a href=\"register.php\">Registrarse</a></li>";
        }
        echo "</ul></nav>";
        echo "<div id=\"content\">";
    }

    function foot() {
        echo "</div>
        <footer><p>BitBits, ".date('Y')."</p></footer>
            </body>
        </html>";
    }

    /* Function which checks if the user variable is set under SESSION */
    function isLogged() {
        return isset($_SESSION['user']);
    }

    /* Function which is called in every PHP which needs a session */
    function start_session() {
        session_start();
        if (!$this->isLogged())
            header('Location: login.php');
    }

    /* Unsets the user variable in SESSION and returns to Login */
    function destroy_session() {
        if (isset($_SESSION['user']))
            unset($_SESSION['user']);
        session_destroy();
        header('Location: ../index.php');
    }

    /* Method which initialises the session by setting the user variable in SESSION */
    function init_session($user) {
        if (!$this->isLogged())
            $_SESSION['user'] = $user;
    }

    /* Method which checks if the DAO is connected to the database */
    function isConnected()
    {
        return $this->dao->isConnected();
    }

    /* Method which prints to screen the DAO error */
    function showError()
    {
        echo "<p class=\"error\">" . $this->dao->getError() . "</p>";
    }

    function sendEmail($to, $user, $hash) {
        $sent = true;
        $mail = new PHPMailer(true);    // the true param means it will throw exceptions on errors, which we need to catch
        $mail->IsSMTP();        // telling the class to use SMTP

        $from = "assistappbitbits@gmail.com";
        $password = "";
        $subject = "Confirma el registro en DerpPlay";

        try {
            $mail->SMTPDebug = 2;            // enables SMTP debug information (for testing)
            //$mail->SMTPDebug  = 0;
            $mail->SMTPAuth = true;            // enable SMTP authentication
            $mail->SMTPSecure = "None";                    // sets the prefix to the server
            $mail->Host = "smtp.gmail.com";        // sets GMAIL as the SMTP server
            $mail->Port = 587;                    // set the SMTP port for the GMAIL server
            $mail->SMTPSecure = 'tls';
            $mail->Username = $from;            // GMAIL username
            $mail->Password = $password;        // GMAIL password
            $mail->AddAddress($to);            // Receiver email
            $mail->SetFrom($from, 'DerpPlay');        // email sender
            $mail->AddReplyTo($from, 'DerpPlay');        // email to reply
            $mail->Subject = $subject;            // subject of the message
            $mail->AltBody = $hash;    // optional - MsgHTML will create an alternate automatically
            $mail->MsgHTML("Confirma tu cuenta de DerpPlay en <a href='https://bitbits.hopto.org/DerpPlay/php/confirm.php?user=$user&hash=$hash'>este enlace</a>");            // message in the email
            $mail->Send();
        } catch (phpmailerException $e) {
            $sent = false;
        } catch (Exception $e) {
            $sent = false;
        }
        return $sent;
    }

    function userLogin($user, $pass) {
        return $this->dao->userLogin($user, $pass);
    }

    function getUserId($email) {
        return $this->dao->getUserId($email);
    }

    function getUserEmail() {
        return $_SESSION['user'];
    }

    function userSignup($username, $email, $pass, $hash) {
        return $this->dao->userSignup($username, $email, $pass, $hash);
    }

    function userVerify($user, $hash) {
        return $this->dao->userVerify($user, $hash);
    }

    function checkEmail($email) {
        return $this->dao->checkEmail($email);
    }

    function checkUsername($username) {
        return $this->dao->checkUsername($username);
    }

    function uploadFile($file, $path, $id) {
        //create uploads and put permissions
        $FileType = pathinfo(basename($file["name"], PATHINFO_EXTENSION));
        $target_dir = $path;
        $target_file = $target_dir . $id .".". $FileType;
        $uploadOk = true;
        
        // Check file size bigger than 64 kb
        if (!move_uploaded_file($img["tmp_name"], $target_file)) {
            $uploadOk = false;
        }
        return $uploadOk;
    }



    function getVideos() {
        return $this->dao->getVideos();
    }

    function searchVideo($query) {
        return $this->dao->searchVideo($query);
    }

    function getVideo($id) {
        return $this->dao->getVideo($id);
    }

    function formatDate($mysqldate) {
        $time = strtotime($mysqldate);
        return date("d/m/Y H:i", $time);
    }



    function getUserInfo($id) {
        return $this->dao->getUserInfo($id);
    }

    function getUserVideos($uploader) {
        return $this->dao->getUserVideos($uploader);
    }
}
?>
