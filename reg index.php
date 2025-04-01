<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/scroll.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <style media="screen">
        *,
  *:before,
  *:after{
      padding: 0;
      margin: 0;
      box-sizing: border-box;
  }
  .background{
      width: 430px;
      height: 520px;
      position: absolute;
      transform: translate(-50%,-50%);
      left: 50%;
      top: 50%;
      margin-top: 50px;
  }
  .background .shape{
      height: 200px;
      width: 200px;
      position: absolute;
      border-radius: 50%;
  }
  .shape:first-child{
      background: linear-gradient(
          #1845ad,
          #23a2f6
      );
      left: -70px;
      top: -180px;
  }
  .shape:last-child{
      background: linear-gradient(
          to right,
          #ff512f,
          #f09819
      );
      right: -75px;
      bottom: -195px;
  }
  form{
      height: 730px;
      width: 400px;
      background-color: rgba(255,255,255,0.13);
      position: absolute;
      transform: translate(-50%,-50%);
      top: 50%;
      left: 50%;
      border-radius: 10px;
      backdrop-filter: blur(10px);
      border: 2px solid rgba(255,255,255,0.1);
      box-shadow: 0 0 40px rgba(8,7,16,0.6);
      padding: 35px 35px;
      margin-top: 50px;
  }
  form *{
      font-family: 'Poppins',sans-serif;
      color: #ffffff;
      letter-spacing: 0.5px;
      outline: none;
      border: none;
  }
  form h3{
      font-size: 32px;
      font-weight: 500;
      line-height: 42px;
      text-align: center;
  }
  
  label{
      display: block;
      margin-top: 30px;
      font-size: 16px;
      font-weight: 500;
  }
  .auth-input{
      display: block;
      height: 50px;
      width: 100%;
      background-color: rgba(255,255,255,0.07);
      border-radius: 3px;
      padding: 0 10px;
      margin-top: 8px;
      font-size: 14px;
      font-weight: 300;
  }
  ::placeholder{
      color: #e5e5e5;
  }
  .zareg{
      margin-top: 50px;
      width: 100%;
      background-color: #ffffff;
      color: #080710;
      padding: 15px 0;
      font-size: 18px;
      font-weight: 600;
      border-radius: 5px;
      cursor: pointer;
  }
  .social{
    margin-top: 30px;
    display: flex;
  }
  .social div{
    background: red;
    width: 150px;
    border-radius: 3px;
    padding: 5px 10px 10px 5px;
    background-color: rgba(255,255,255,0.27);
    color: #eaf0fb;
    text-align: center;
  }
  .social div:hover{
    background-color: rgba(255,255,255,0.47);
  }
  .social .fb{
    margin-left: 25px;
  }
  .social i{
    margin-right: 4px;
  }
  .go{
    cursor: pointer;
  }
  .fb{
    cursor: pointer;
  }

/* footer */
html, body{
    height: 100%;
    position: relative;
}
.container{
    flex-direction: column;
    height: 100%;
    min-height: 800px;
    margin-bottom: 200px;
}
body{
    flex: 1 0 auto;
}
.footer{
    width: 100%;
    flex: 0 0 auto;
}
html {
    height: 100%;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    height: 100%;
    display: flex;
    flex-direction: column;
    margin: 0;
    padding: 0;
}

.container {
    flex-grow: 1; /* Основной контент растягивает оставшееся пространство */
}

.footer {
    width: 100%;
    height: 200px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    background-color: #030311e6;
    color: white;
    position: relative;
    bottom: 0;
    
}

.oglavlenie-footer {
    font-size: 24px;
    margin-top: 20px;
    margin-bottom: 15px;
    font-weight: bold;
}

.stolbik1, .stolbik2, .stolbik3 {
    font-size: 16px;
    margin: 0 30px;
    margin-bottom: 20px;
}

.stolbik1 a, .stolbik2 a {
    text-decoration: none;
    color: white;
}

.stolbik1 p, .stolbik2 p {
    margin-top: 7.5px;
}
.search-nav{
    cursor: pointer;
}
.stolbik3 img {
    cursor: pointer;
    margin-top: 5px;
    width: 28px;
    height: 28px;
    margin-left: 27.5px;
}

  #menu-toggle:checked ~ .menu li {
    border: 1px solid #333;
    height: 2em;
    padding: 0.5em;
    transition: height 400ms cubic-bezier(0.23, 1, 0.32, 1);
  }
  @media (max-width: 480px) {
    form{
        margin-top: 50px;
        width: 90%;
    }
    .footer {
        margin-top: 0px;
    }
    .stolbik3{
        margin-top: 30px;
    }
    #menu-toggle:checked ~ .menu li {
    border: 1px solid #333;
    height: 2.5em;
    padding: 0em;
    transition: height 400ms cubic-bezier(0.23, 1, 0.32, 1);
  }
}
</style>
</head>
<body>
<div class="container">
    <!--Шапка-->
    <?php require_once "blocks/header.php" ?>
    <!-- Конец шапки, начало основного блока -->
<div>

    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="post" action="vendor/regaction.php">
        <h3>Создание аккаунта</h3>
    
        <label for="username">Логин пользователя</label>
        <input class="auth-input" type="text" placeholder="Введите логин" id="login" name="login">
    
        <label for="password">Пароль</label>
        <input class="auth-input" type="password" placeholder="Введите пароль" id="password" name="password">
    
        <label for="phone">Номер телефона</label>
        <input class="auth-input" id="phone" name="phone" type="tel" placeholder="+7 (___) ___-__-__" required>
    
        <label for="email">Электронная почта</label>
        <input class="auth-input" id="email" name="email" type="email" placeholder="Введите вашу эл.почту" required>
        
        <button class="zareg" type="submit">Зарегистрироваться</button>

        <div class="social">
            <div class="go"><i class="fab fa-google"></i> Google</div>
            <div class="fb"><i class="fab fa-facebook"></i>  Facebook</div>
        </div>
    </form>
</div>

</div>

<!-- footer -->
<?php require_once "blocks/footer.php" ?>

<script src="js/script.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.getElementById('phone');

    phoneInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); // Удаляем все нецифровые символы

        // Если номер начинается с 7, убираем её из value
        if (value.startsWith('7')) {
            value = value.slice(1);
        }

        // Ограничиваем строку до 10 цифр
        value = value.slice(0, 10);

        // Форматируем
        let formattedValue = '';
        if (value.length > 0) {
            formattedValue += '+7 ';
        }
        if (value.length > 0) {
            formattedValue += '(' + value.slice(0, 3);
        }
        if (value.length >= 3) {
            formattedValue += ') ' + value.slice(3, 6);
        }
        if (value.length >= 6) {
            formattedValue += '-' + value.slice(6, 8);
        }
        if (value.length >= 8) {
            formattedValue += '-' + value.slice(8, 10);
        }

        e.target.value = formattedValue; // Обновляем значение поля
    });
});

</script>
</body>
</html>