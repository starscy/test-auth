<?php
include $_SERVER['DOCUMENT_ROOT'] . '/core.php';

if(!isAuthUser()) {
    header("Location: index.php");
    die();
}

if(isset($_POST['name'], $_POST['phone'], $_POST['email'], $_POST['password'])) {
    ['name' => $name, 'phone' => $phone, 'email' => $email,'password' => $password] = $_POST;
    $userProvider = new UserProvider($pdo);
    try{
        $userProvider->updateDataUser($_SESSION['user']['id'], $name, $phone, $email, $password);
        $user = $userProvider->getByUsernameAndPassword($email, $password);
        $_SESSION['user'] = $user;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
include $_SERVER['DOCUMENT_ROOT'] . '/templates/Header.php';
?>
<h2>Страница профиля</h2>
<div>Имя - <?=$_SESSION['user']['name']?></div>
<div>Телефон - <?=$_SESSION['user']['phone']?></div>
<div>Почта - <?=$_SESSION['user']['email']?></div>
<div>Пароль - <?=$_SESSION['user']['password']?></div>

<form method="post">
    <div>
        <div>
            <div>
                <label for="fieldName">Имя</label>
                <input id="fieldName" name="name" value="<?=$_SESSION['user']['name']?>" type="text" placeholder="name">
            </div>
            <div>
                <label for="fieldEmail">Телефон</label>
                <input id="fieldEmail" name="phone" value="<?=$_SESSION['user']['phone'] ?>" type="text" placeholder="phone">
            </div>
            <div>
                <label for="fieldEmail">Почта</label>
                <input id="fieldEmail" name="email" value="<?=$_SESSION['user']['email'] ?>" type="email" placeholder="email">
            </div>
            <div class="block">
                <label for="fieldPassword">Пароль</label>
                <input id="fieldPassword" name="password" type="password" value="<?=$_SESSION['user']['password']?>" placeholder="******">
            </div>
            <div class="block">
                <button type="submit">
    Принять изменения
                </button>
            </div>
        </div>
    </div>
</form>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/templates/Footer.php';
