<html>
<head>

</head>
<style>
    .ajax-form input, .ajax-form textarea {
        width: 500px;
        height: 45px;
        border: 1px solid gray;
        border-radius: 5px;
        padding: 10px 15px;
        margin: 20px 0;
        max-width: 500px;
        line-height: 25px;
    }

    .ajax-form {
        width: 500px !important;
        position: absolute;
        left: 50%;
        margin-left: -250px;
    }

</style>
<body>

<form id="ajax-form-w0" class="ajax-form">
    <input type="text" name="name" placeholder="Имя" required autocomplete="name">
    <input type="email" name="email" placeholder="Email" required autocomplete="email">
    <textarea name="msg" placeholder="Текст сообщения" required></textarea>
    <input type="submit" value="Отправить">
    <div class="ajax-error"></div>
    <div class="ajax-success"></div>
</form>
<!-- Jquery CDN-->
<script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous">
</script>
<!-- /Jquery CDN-->
<script>
    $("[id^=ajax-form-w]").on("submit", function (e) {
        var _this = $(this),
            error = _this.find('.ajax-error');
        //Отключаем дефолтную отправку формы
        e.preventDefault();

        //Убираю предыдущие ошибки
        error.html('');

        //Для тех браузеров-динозавров, что не поддерживают аттрибут type='email'
        if ($("[name='email']").val().search(/.+@.+\.\w+/) === -1) {
            error.html('Неправильно указан email');
            return false;
        }

        $.post("ajaxSubmit.php", $(this).serializeArray(), function (response) {
            if (response.status) {
                console.log("yeap");
            } else {
                error.html(response.error);
            }
        }, "JSON");

    });
</script>
</body>
</html>
