<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
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
      top: -80px;
  }
  .shape:last-child{
      background: linear-gradient(
          to right,
          #ff512f,
          #f09819
      );
      right: -70px;
      bottom: -80px;
      
  }
  form{
      height: auto;
      width: auto;
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
  button{
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
    min-height: 800px
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

.stolbik3 img {
    cursor: pointer;
    margin-top: 5px;
    width: 28px;
    height: 28px;
    margin-left: 27.5px;
}
#menu-toggle:checked ~ .menu li {
    border: 1px solid #333;
    height: 2.5em;
    padding: 0.5em;
    transition: height 400ms cubic-bezier(0.23, 1, 0.32, 1);
  }
  @media (max-width: 480px) {
    form{
        margin-top: 20px;
    }
    .footer {
        margin-top: 15px;
    }
    .stolbik3{
        margin-top: 30px;
    }
}
</style>
</head>
<body>
<div class="container">
    <!--Шапка-->
    <?php require_once "blocks/header.php" ?>
    <!-- Конец шапки, начало основного блока -->
<div class="auth-form">
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
</div>
    <form method="post" action="vendor/auth.php">
        <h3>Вход в аккаунт</h3>

        <label for="username">Логин</label>
        <input class="auth-input" type="text" placeholder="Введите ваш логин" id="login" name="login">

        <label for="password">Пароль</label>
        <input class="auth-input" type="password" placeholder="Введите пароль" id="password" name="password">

        <button type="submit">Войти</button>

        <div class="social">
          <div class="go"><i class="fab fa-google"></i> Google</div>
          <div class="fb"><i class="fab fa-facebook"></i>  Facebook</div>
        </div>
        <br>
        <p style="margin-top: 5px; text-align: center;">Нет аккаунта?
            <a href="reg index.php">Зарегистрироваться</a>
        </p>
    </form>
    </div>
</div>

<!-- footer -->
<?php require_once "blocks/footer.php" ?>

<script src="js/script.js"></script>
</body>
</html>