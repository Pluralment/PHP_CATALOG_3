<!DOCTYPE html>
<html lang="ru">
<head>
    <link href="FormStyles.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Форма работы с локальными каталогами</title>
</head>
<body>
<div class="formContainer">
    <form action="CatalogScript.php" method="post">
        <h2>Введите каталог</h2>
        <div class="formField multiInput">
            <label for="name">Каталог</label>
            <input type="text" name="catalog-name" id="name" placeholder="Имя" value="">
        </div>
        <div class="formField inputRight submitField">
            <input type="submit" value="Отправить">
        </div>
    </form>
</div>
</body>
</html>