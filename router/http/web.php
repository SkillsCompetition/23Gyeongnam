<?php

  get("/initial_data", function(){
    $json = json_decode(file_get_contents(ROOT."/resources/json/place.json"), true);

    DB::mq("TRUNCATE attraction");
    foreach($json["data"] as $v){
      unset($v["point"]);
      unset($v["review_cnt"]);
      attraction::insert($v);
    }
  });

  get("/", function(){
    view("index");
  });

  get("/login", function(){
    view("login");
  });

  post("/login", function(){
    err(P["userid"] != "admin" || P["password"] != "1234", "아이디 또는 비밀번호를 확인해주세요.");

    $_SESSION["user_101"] = P;

    move("로그인이 완료되었습니다.", "/");
  });

  get("/game", function(){
    view("game");
  });

  post("/record", function(){
    record::insert(P);
  });

  get("/record_data", function(){
    $json = record::all("ORDER BY score DESC, name ASC");

    echo json_encode($json);
  });

  get("/delete/record/$", function($idx){
    record::delete("idx = ?", $idx);
    move("삭제되었습니다.");
  });

  get("/attraction", function(){
    view("attraction");
  });

  get("/attraction_data", function(){
    $data = attraction::mq(
     "SELECT
        attraction.*, 
        COUNT(review.idx) as review_cnt,
        NVL(AVG(review.score), 0) as point
      FROM attraction
      LEFT JOIN review
      ON attraction.idx = review.attr_idx
      GROUP BY attraction.idx")->fetchAll();

    echo json_encode($data);                
  });

  get("/review", function(){
    $attraction = attraction::find("idx = ?", G["idx"]);

    view("review", get_defined_vars());
  });

  get("/review_data", function(){
    $review = review::all();

    echo json_encode($review);
  });

  post("/review", function(){
    err(emp_vali(P), "모든 값을 입력해주세요.");

    review::insert(P);
    move("리뷰가 작성되었습니다.", "/detail/".P["attr_idx"]);
  });

  get("/delete/review/$", function($idx){
    review::delete("idx = ?", $idx);
    move("삭제되었습니다.");
  });

  get("/modify/$", function($idx){
    $review = review::find("idx = ?", $idx);
    $attraction = attraction::find("idx = ?", $review["attr_idx"]);

    view("modify", get_defined_vars());
  });

  post("/modify", function(){
    review::update("idx = ?", P["idx"], P);

    move("수정이 완료되었습니다.", "/admin_r");
  });

  get("/detail/$", function($idx){
    $attraction = attraction::find("idx = ?", $idx);
    $reviews = review::findAll("attr_idx = ?", $idx);

    view("detail", get_defined_vars());
  });

  get("/review_point/$", function($idx){
    $data = array_map(function($v){
      return $v["score"];
    }, review::findAll("attr_idx = ?", $idx));

    echo json_encode($data);
  });

  middleware("user", function(){

    get("/logout", function(){
      session_destroy();

      move("로그아웃이 완료되었습니다.", "/");
    });

  });

  middleware("admin", function(){

    get("/admin_g", function(){
      view("admin_game");
    });

    get("/admin_r", function(){
      view("admin_review");
    });

  });

?>