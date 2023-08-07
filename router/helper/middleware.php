<?php

  function user(){
    err(@!USER, "로그인 후 이용해주세요.", "/login");
  }

  function admin(){
    err(@USER["userid"] != "admin", "관리자만 접근 가능합니다.", "/");
  }
  
?>