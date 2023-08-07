<!DOCTYPE html>
<html lang="zxx">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="/js/jquery-3.6.0.js"></script>
  <script src="/js/script.js"></script>
  <link rel="stylesheet" href="/style.css">
  <title>Document</title>
</head>
<body>

  <header>
    <div class="wrap flex jcsb aic">
      <div class="logo_box">
        <a href="/"><img src="/resources/img/logo.png" alt="" class="logo"></a>
      </div>

      <div class="menu_nav flex">
        <a href="#" class="depth1">천안시</a>
        <a href="/attraction" class="depth1">천안 명소</a>
        <div class="depth_box">
          <a href="/game" class="depth1">관광게임하기</a>
          <div class="sub_menu">
            <a href="#" class="depth2">호두과자 먹는 캐릭터 게임</a>
            <a href="#" class="depth2">천안 명물 받아먹기 게임</a>
          </div>
        </div>
        <?php if(@USER["userid"] == "admin"):?>
        <div class="depth_box">
          <a href="/" class="depth1">관리자</a>
          <div class="sub_menu">
            <a href="/admin_g" class="depth2">게임 관리</a>
            <a href="/admin_r" class="depth2">리뷰 관리</a>
          </div>
        </div>
        <?php endif ?>
      </div>

      <div class="utility flex jcfe">

        <div class="btn_box">
          <?php if(@USER): ?>
            <a href="/logout" class="btn">로그아웃</a>
          <?php else: ?>
            <a href="/login" class="btn">로그인</a>
            <a href="#" class="btn">회원가입</a>
          <?php endif ?>
        </div>
      </div>
    </div>
  </header>