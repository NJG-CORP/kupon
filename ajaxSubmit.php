<?php
$adminEmail = "denis@sidorov-denis.ru";

//Проверки на хацкеров клиентской части
if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['msg']))
    die(json_encode([
        "status" => false,
        "error" => "Не все поля заполнены!"
    ]));

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    die(json_encode([
        "status" => false,
        "error" => "Неправильно указан email"
    ]));

//Сохраняю данные
$pdo = (new PDO("mysql:host=host;dbname=dbname", 'user', 'pass'))
    ->prepare("INSERT INTO `sample_table` (`name`,`email`,`msg`) VALUES(':name',':email',':msg')");
$pdo->execute($_POST);

//Отправка сообщения
if (mail(
    $adminEmail,
    "Обратная связь от посетителя \"{$_POST['name']}\" ",
    "Имя: {$_POST['name']}\r\nEmail: {$_POST['email']}\r\nСообщение: {$_POST['msg']}"))
    echo json_encode([
        "status" => true
    ]);
else
    echo json_encode([
        "status" => false,
        "error" => "Ошибка отправки"
    ]);
