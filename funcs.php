<?php
// Регистрация

function registration():bool {
    global $pdo;

    $login = !empty($_POST['login']) ? trim($_POST['login']) : '';
    $pass = !empty($_POST['pass']) ? trim($_POST['pass']) : '';

    if (empty($login) || empty($pass)) {
        $_SESSION['errors'] = 'Поля логин/пароль обязательны';
        return false;
    }

// Подготовленный запрос SQL с помощью метода prepare()

    $res = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login = ?");
    $res->execute([$login]); //запуск запрса


//fetchColumn возвращает данные одного столбца
    if ($res->fetchColumn()) {
        $_SESSION['errors'] = 'Данное имя уже используется';
        return false;
    }
// Хеширование пароля
    $pass = password_hash($pass, PASSWORD_DEFAULT);
    $res = $pdo->prepare("INSERT INTO users (login, pass) VALUES (?, ?)");
    

    if ($res->execute([$login, $pass])) {
        $_SESSION['success'] = 'Успешная регистрация';
        return true;
    }else {
        $_SESSION['errors'] = 'Ошибка регистрации';
        return false;
    }

}


// Авторизация

function login():bool {
    global $pdo;

    $login = !empty($_POST['login']) ? trim($_POST['login']) : '';
    $pass = !empty($_POST['pass']) ? trim($_POST['pass']) : '';

    if (empty($login) || empty($pass)) {
        $_SESSION['errors'] = 'Поля логин/пароль обязательны';
        return false;
    }

    



    $res = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $res->execute([$login]);

    if (!$user = $res->fetch()) {
        $_SESSION['errors'] = 'Неверный логин или пароль';
        return false;
    }


// password_verify - Проверяет, что пароль соответствует хэшу

    if (!password_verify($pass, $user['pass'])) {
        $_SESSION['errors'] = 'Неверный логин или пароль';
        return false;
    }else {
        $_SESSION['success'] = 'Вы успешно авторизовались';
        $_SESSION['user']['name'] = $user['login'];
        $_SESSION['user']['id'] = $user['id'];
        return true;
    }

}

// Cообщения

function save_message():bool {
    global $pdo;

    $message = !empty($_POST['message']) ? trim($_POST['message']) : '';

    if (!isset( $_SESSION['user']['name'])) {
        $_SESSION['errors'] = 'Необходимо авторизоваться';
        return false;
    }

    if (empty($message)) {
        $_SESSION['errors'] = 'Введите текст сообщения';
        return false;
    }


    $res = $pdo->prepare("INSERT INTO message (name, message) VALUES (?,?)");



    if ($res->execute([$_SESSION['user']['name'], $message])) {
        $_SESSION['success'] = 'Сообщение добавлено';
        return true;
    } else {
        $_SESSION['errors'] = 'Ошибка!';
        return false;
    }
}


function get_message() {
    global $pdo;

    $res = $pdo->query("SELECT * FROM message");
    return $res->fetchAll();

}