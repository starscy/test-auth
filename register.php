<?php
include $_SERVER['DOCUMENT_ROOT'] . '/core.php';

$error=null;

try{
    throw new EmailExistsException();
} catch (EmailExistsException) {
    $error = 'email exception';
}

if(!empty($_POST)) {
    if(isset($_POST['name'], $_POST['phone'], $_POST['email'], $_POST['password'], $_POST['password_repeat'])) {
        ['name' => $name, 'phone' => $phone, 'email' => $email,'password' => $password, 'password_repeat' => $password_repeat] = $_POST;
        if($password === $password_repeat) {
            $userProvider = new UserProvider($pdo);
            try{
                $userProvider->registerUser($name, $phone, $email, $password);
            } catch (EmailExistsException) {
                $error = 'Пользователь с таким email уже существует';
            } catch (PhoneExistsException) {
                $error = 'Пользователь с таким телефоном уже существует';
            } catch (UsernameExistsException) {
                $error = 'Пользователь с таким именем уже существует';
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
            $user = $userProvider->getByUsernameAndPassword($email, $password);
            if (!$user) {
                $error = 'Пользователь с указанными учетными данными не найден';
            } else {
                $_SESSION['user'] = $user;
                header("Location: accountPage.php");
                die();
            }
        } else {
            echo 'Пароли не совпадают';
        }
    }
}
include $_SERVER['DOCUMENT_ROOT'] . '/templates/Header.php';
echo $error;
?>
<script src="https://www.google.com/recaptcha/api.js"></script>
<form method="post">
    <div>
        <div>
            <div>
                <label for="fieldName">Имя</label>
                <input id="fieldName" name="name" value="<?=$_POST['name']?>" type="text" placeholder="name">
            </div>
            <div>
                <label for="fieldEmail">Телефон</label>
                <input id="fieldEmail" name="phone" value="<?=$_POST['phone'] ?>" type="text" placeholder="phone">
            </div>
            <div>
                <label for="fieldEmail">Почта</label>
                <input id="fieldEmail" name="email" value="<?=$_POST['email'] ?>" type="email" placeholder="email">
            </div>
            <div class="block">
                <label for="fieldPassword">Пароль</label>
                <input id="fieldPassword" name="password" type="password" value="<?=$_POST['password']?>" placeholder="******">
            </div>
            <div class="block">
                <label for="fieldPassword">Повторите пароль</label>
                <input id="fieldPassword" name="password_repeat" type="password" placeholder="******">
            </div>
            <div class="g-recaptcha" data-sitekey="6Lds57QlAAAAACmFIEHteV-npfg1ICtWDcXJjZL0"></div>
            <div class="block">
                <button type="submit">
                Зарегистрироваться
                </button>
            </div>
        </div>
    </div>
</form>
<?php
include $_SERVER['DOCUMENT_ROOT'] . '/templates/Footer.php';
