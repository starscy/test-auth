<?php
include $_SERVER['DOCUMENT_ROOT'] . '/core.php';

$error=null;

if (isset($_POST['login'], $_POST['password'])) {
    ['login' => $login, 'password' => $password] = $_POST;
    if (isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response']) {
        $secret = "6Lds57QlAAAAACSbA6lFYGfa7NC_zWhBufT9amXh";
        $ip = $_SERVER['REMOTE_ADDR'];
        $response = $_POST['g-recaptcha-response'];
        $rsp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$ip");
        $arr = json_decode($rsp, TRUE);
        if ($arr['success']) {
            $userProvider = new UserProvider($pdo);
            $user = $userProvider->getByUsernameAndPassword($login, $password);
            if (!$user) {
                $error = 'Пользователь с указанными учетными данными не найден';
            } else {
                $_SESSION['user'] = $user;
                header("Location: accountPage.php");
                die();
            }
        }
    } else {
        $error = 'captcha не заполнена';
    }
}

if($error) {
    echo $error;
}
include $_SERVER['DOCUMENT_ROOT'] . '/templates/Header.php';
?>

<form action="" method="post">
    <div>
        <div>
            <div>
                <label for="fieldEmail">Email\Тел.номер</label>
                <input id="fieldEmail" name="login"  type="text" placeholder="email/телефонный номер">
            </div>
            <div class="block">
                <label for="fieldPassword" class="text-gray-700 font-bold">Пароль</label>
                <input id="fieldPassword" name="password" type="password" value="<?=$_POST['password'] ?? '' ?>"  placeholder="******">
            </div>
            <div class="g-recaptcha" data-sitekey="6Lds57QlAAAAACmFIEHteV-npfg1ICtWDcXJjZL0"></div>
            <div class="block">
                <button type="submit" >
                    Войти
                </button>
                <a href="/register.php" >
                    У меня нет аккаунта
                </a>
            </div>
        </div>
    </div>
</form>
<?php
include $_SERVER['DOCUMENT_ROOT'] . '/templates/Footer.php';

