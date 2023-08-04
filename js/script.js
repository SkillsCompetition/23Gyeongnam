const dd = console.log;
const ls = localStorage;

const App = {
  
  init(){
    $.ajaxSetup({ cache : false })
    App.hook();

    if(location.pathname.includes("attraction")) Attraction.init();
    if(location.pathname.includes("attraction") || location.pathname.includes("review")) Review.init();
    if(location.pathname.includes("game")) {
      Snake.init()
      Drop.init();
    };
  },

  hook(){
    $(document)
      .on("mousemove", ".review .star", Review.star)
      .on("keydown", Snake.changeDirection)
      .on("keydown keypress", Drop.move)
  }

}

const Attraction = {

  async init(){
    await Attraction.loadData();

    Attraction.settingInterval();
    Attraction.settingContainer();
  },
  
  settingContainer(){
    $(".attraction2").html(Attraction.data.map((v, i) => {
      return `
        <div class="item">
          <img src="resources/img/place/${v.idx}.jpg" alt="">
          <div class="text_box">
            <div class="flex aic jcsb">
              <h2>${v.title}</h2>
              <small>${v.distance}km</small>
            </div>
            <div class="bottom flex jcsb aic">
              <small>별점 : ${v.point}점</small>
              <small>리뷰 : ${v.review_cnt}개</small>
            </div>
            <hr>
            <div class="btn_box full">
              <div class="btn" onclick="Review.openReview(${i})">리뷰 작성</div>
            </div>
          </div>
        </div>
      `
    }))
  },

  settingInterval(){
    setInterval(async () => {
      Attraction.loadData();
      Attraction.settingContainer()
    }, 5000);
  },

  loadData(){
    return $.getJSON("/resources/json/place.json")
      .then(res => {
        Attraction.data = res.data;

        return res;
      });
  },
}

const Review = {

  init(){
    Review.settingForm();
    Review.settingContainer();
  },

  settingContainer(){
    const data = JSON.parse(ls["review_list"]);

    $(".reviewlist").html(data.map(v => {
      return `
        <div class="item">
          <div class="top flex jcsb aic">
            <h3>${v.title}</h3>
            <small><span>★</span>${v.score}</small>
          </div>

          <p>${v.content}</p>

          <small>${v.visited_date}</small>
        </div>
      `
    }))
  },

  settingForm(){
    const data = JSON.parse(ls["review"])
    const now = new Date(+new Date() + 32400000).toISOString().split("T")[0];

    $(".review #title").val(data.title);
    $(".review #visited_date").attr("max", now);
  },

  openReview(idx){
    ls["review"] = JSON.stringify(Attraction.data[idx]);

    location.assign("/review.html");
  },

  star(e){
    let score = (e.offsetX/18).toFixed(1);
    score = score > 5 ? 5.0 : (score < 0 ? 0.0 : score);

    $(".star .fill").css("width", `${18 * score}px`);
    $(".star_box .score_display").html(score)
    $(".star_box #score").val(score)
  },

  submit(){
    const data = {
      title : $(".review #title").val(),
      visited_date : $(".review #visited_date").val(),
      score : $(".review #score").val(),
      content : $(".review #content").val()
    }

    if(!data.title.trim() || !data.visited_date.trim() || !data.content.trim()){
      return alert("모든 값을 입력해주세요.");
    }

    const arr = JSON.parse(ls["review_list"] || "[]");
    arr.push(data);

    ls["review_list"] = JSON.stringify(arr);

    navigation.addEventListener("navigate", (e) => {
      e.intercept({
        async handler(){
          const html = await fetch(e.destination.url);
          const text = await html.text();

          const replace = /<body>([\w\W]*)<\/body>/.exec(text)[1];

          alert("리뷰가 등록되었습니다.")

          document.startViewTransition(() => {
            document.body.innerHTML = replace;

            Attraction.init();
            Review.init();
          });
        }
      })
    }, { once : true });

    location.assign("/attraction.html");
  }

}

const Timer = {
  remain : "",
  interval : null,

  settingTimer(time, ended){
    Timer.remain = time;
    Timer.end = ended;

    Timer.changeDisplay();
  },

  start(){
    clearInterval(Timer.interval);
    Timer.setInterval();
  },

  setInterval(){
    Timer.interval = setInterval(() => {
      Timer.remain--;

      Timer.changeDisplay();

      if(Timer.remain <= 0) return Timer.end();
    }, 1000)
  },

  changeDisplay(){
    const min = String(Math.floor(Timer.remain/60));
    const sec = String(Timer.remain%60);

    $(".timer_display").html(`${min.padStart(2, "0")}:${sec.padStart(2, "0")}`)
  }

}

const Game = {
  user : null,

  changeGame(target){
    if(!Game.user) return alert("이름을 입력해주세요.");
    const game = $(target).attr("data-game");

    $(".gamezone > div").hide();
    $(`.gamezone > .${game}`).show();

    if(game == "snake"){
      Drop.reset();

      Snake.reset()
      Snake.gameStart();
    }else{
      Snake.reset()
      
      Drop.reset();
      Drop.gameStart();
    }
  },

  openGame(){
    if(Game.user) return;

    const username = $("#name").val().trim();
    if(!username) return alert("이름을 입력해주세요.");
    
    Game.user = username;
    $("#name").prop("disabled", true);

    $(".gamezone").show();
    Snake.reset();
    Snake.gameStart();
  }
}

const Snake = {
  isPlay : false,
  moveInterval : null,
  data : [[15, 15], [14, 15], [13, 15]],
  nowFood : null,
  score : 0,
  moveX : 1,
  moveY : 0,
  timestamp : 0,

  init(){
    Snake.reset();
  },

  reset(){
    const canvas = $('#snake')[0];
    const ctx = canvas.getContext("2d");

    ctx.clearRect(0, 0, 600, 600);

    Snake.isPlay = false;
    Snake.nowFood = null;
    Snake.data = [[15, 15], [14, 15], [13, 15]];
    Snake.score = 0;

    clearInterval(Timer.interval);
    Timer.settingTimer(180, Snake.gameOver);

    clearInterval(Snake.moveInterval);
    Snake.moveInterval = null;

    Snake.drawBackground();
    Snake.drawSnake();
    Snake.changeScore();

    $(".game_btn_box > h3").html(Game.user + "님")
  },

  restart(){
    Snake.reset();
    Snake.gameStart();
  },

  changeStatus(){
    if(Snake.isPlay){
      Snake.isPlay = false;
      clearInterval(Snake.moveInterval);
      clearInterval(Timer.interval);

      $(".staus_btn").html("재생")
    }else{
      Snake.isPlay = true;
      Snake.setMoveInterval();
      Timer.setInterval();

      $(".staus_btn").html("일시정지")
    }
  },

  gameStart(){
    Snake.isPlay = true;

    Snake.setMoveInterval();
    Snake.randFood();
    Snake.reDraw();

    Timer.start();
  },

  gameOver(){
    clearInterval(Timer.interval);

    Snake.showResult();
    Snake.reset();
  },

  showResult(){
    Modal.open("result");

    $(".result_modal h3.title").html(`호두과자먹기 캐릭터 게임 ( ${Game.user}님 )`);
    $(".result_modal .score").html(`${Snake.score}점`);
  },

  changeDirection(e){
    if(!Snake.isPlay) return;

    switch(e.key){
      case "ArrowUp":
        e.preventDefault();
        if(Snake.moveY == 1) return;

        Snake.moveX = 0;
        Snake.moveY = -1;
        break;
      case "ArrowLeft":
        e.preventDefault();
        if(Snake.moveX == 1) return;

        Snake.moveX = -1;
        Snake.moveY = 0;
        break;
      case "ArrowRight":
        e.preventDefault();
        if(Snake.moveX == -1) return;

        Snake.moveX = 1;
        Snake.moveY = 0;
        break;
      case "ArrowDown":
        e.preventDefault();
        if(Snake.moveY == -1) return;

        Snake.moveX = 0;
        Snake.moveY = 1;
        break;
    }
  },

  drawBackground(){
    const canvas = $('#snake')[0];
    const ctx = canvas.getContext("2d");

    for(let h = 0; h < 30; h++){
      for(let w = 0; w < 30; w++){
        ctx.fillStyle = (w + h)%2 == 0 ? "#9acd32af" : "#9acd32cf";
        ctx.fillRect(w * 20, h * 20, 20, 20);        
      }
    }
  },

  drawSnake(){
    const canvas = $('#snake')[0];
    const ctx = canvas.getContext("2d");

    Snake.data.forEach(([ x, y ], i) => {
      ctx.fillStyle = "red";
      ctx.fillRect(x * 20, y * 20, 20, 20);   
    });
  },

  drawFood(){
    const canvas = $('#snake')[0];
    const ctx = canvas.getContext("2d");
    const [x, y] = Snake.nowFood;

    ctx.fillStyle = "brown";
    ctx.fillRect(x * 20, y * 20, 20, 20);
  },

  move(){
    let [x, y] = [...Snake.data[0]];
    
    x += Snake.moveX;
    y += Snake.moveY;

    if(Snake.crashWall(x, y)) return Snake.gameOver();

    if(Snake.nowFood[0] == x && Snake.nowFood[1] == y){
      Snake.score++;
      Snake.changeScore();

      Snake.randFood();
    }
    
    Snake.data.unshift([x, y]);
    Snake.data.pop();
  },

  randFood(){
    Snake.nowFood = [
      Math.floor(Math.random() * 30),
      Math.floor(Math.random() * 30)
    ];
  },

  crashWall(x, y){
    return x > 29 || y > 29 || x < 0 || y < 0;
  },

  setMoveInterval(){
    Snake.moveInterval = setInterval(() => {
      Snake.timestamp += 0.1;

      if(Snake.timestamp >= 1){
        Snake.timestamp = 0;
        Snake.move();
      }
      Snake.reDraw();
    }, 50)
  },

  changeScore(){
    $(".score_display span").html(Snake.score);
  },

  reDraw(){
    const canvas = $('#snake')[0];
    const ctx = canvas.getContext("2d");

    ctx.clearRect(0, 0, 600, 600);

    Snake.drawBackground();
    Snake.drawSnake();
    Snake.drawFood();
  }
}

const Drop = {
  isPlay : false,
  data : [
    {
      name : "car",
      score : -1,
      img : null
    },
    {
      name : "grape",
      score : 1,
      img : null
    },
    {
      name : "knife",
      score : -1,
      img : null
    },
    {
      name : "pan",
      score : -1,
      img : null
    },
    {
      name : "walnut-cookie",
      score : 1,
      img : null
    },
    {
      name : "soondae",
      score : 1,
      img : null
    },
  ],
  food : [],
  posX : 280,
  score : 0,

  init(){
    Drop.loadData().then(() => {
      Drop.reset();
      Drop.animate();
    });
  },

  move(e){
    if(!Drop.isPlay) return;
  
    switch(e.key){
      case "ArrowLeft":
        Drop.posX += -4;
        break;
      case "ArrowRight":
        Drop.posX += 4;
        break;
    }
  },

  reset(){
    Drop.isPlay = false;
    Drop.posX = 280;
    Drop.score = 0;
    Drop.food = [];
    
    clearInterval(Timer.interval);
    Timer.settingTimer(180, Drop.gameOver);

    clearInterval(Drop.rand);
    Drop.randFood();

    Drop.changeScore();
    Drop.reDraw();
  },

  gameStart(){
    Drop.isPlay = true;
    Timer.start();
  },

  gameOver(){
    Drop.reset();
    Drop.showResult();
  },

  showResult(){
    Modal.open("result");

    $(".result_modal h3.title").html(`천안 명물 받아먹기 게임 ( ${Game.user}님 )`);
    $(".result_modal .score").html(`${Drop.score}점`);
  },

  drawBackground(){
    const canvas = $('#drop')[0];
    const ctx = canvas.getContext("2d");

    ctx.fillStyle = "yellowgreen";
    ctx.fillRect(0, 0, 600, 600);
  },

  drawPlayer(){
    const canvas = $('#drop')[0];
    const ctx = canvas.getContext("2d");

    ctx.drawImage(Drop.user, Drop.posX, 600 - 120);
  },

  drawFood(){
    const canvas = $('#drop')[0];
    const ctx = canvas.getContext("2d");
    
    Drop.food.forEach((v, i) => {
      if(v.posY > 600 || v.crash || !Drop.isPlay) return;
      const img = Drop.data[v.foodIdx].img;

      Drop.food[i].posY += v.speed;
      ctx.drawImage(img, v.posX, v.posY, 40, 40);

      if(Drop.crashFood(v.posX, v.posY)){
        if(Drop.score + Drop.data[v.foodIdx].score < 0) return Drop.gameOver();

        Drop.score += Drop.data[v.foodIdx].score;
        Drop.food[i].crash = true;

        Drop.changeScore();
      }
    });
  },

  reDraw(){
    Drop.drawBackground();
    Drop.drawPlayer();
    Drop.drawFood();
  },

  crashFood(x, y){
    if(y < 440) return false;
    const userX = Drop.posX

    return (userX < x && x < userX + 40) || (userX < x +40 && x + 40 < userX + 40);
  },

  randFood(){
    Drop.rand = setInterval(() => {
      if(Drop.isPlay) Drop.food.push({
        foodIdx : Math.floor(Math.random() * Drop.data.length),
        posX : Math.random() * 560,
        posY : -40,
        speed : Math.random() + 1,
      });
    }, 1000);
  },

  changeScore(){
    $(".score_display span").html(Drop.score);
  },

  animate(){
    window.requestAnimationFrame(() => {
      Drop.reDraw();
      
      Drop.animate();
    })
  },

  loadData(){
    const user = new Promise(res => {
      $("<img>", { src : "/resources/img/game/222.png" })[0]
        .onload = (e) => {
          Drop.user = e.target;
          res();
        }
    });

    const food = Drop.data.map((v, i) => {
      return new Promise(res => {
        $("<img>", { src : `/resources/img/game/${v.name}.png` })[0]
          .onload = (e) => {
            Drop.data[i].img = e.target;
            res();
          }
      });
    });

    return Promise.all([...food, user]);
  }
}


const Modal = {
  template : (target) => $($("template")[0].content).find(`.${target}_modal`).clone(),

  open(target){
    $("body").css("overflow", "hidden");

    $(".modal") 
      .addClass("open")
      .html(Modal.template(target))
  },

  close(){
    $("body").css("overflow", "");

    $(".modal")
      .removeClass("open")
      .html("")
  }
}

$(() => App.init());