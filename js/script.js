const dd = console.log;
const ls = localStorage;

const App = {
  
  init(){
    $.ajaxSetup({ cache : false })
    App.hook();

    if(location.pathname.includes("attraction")) Attraction.init();
    if(location.pathname.includes("attraction") || location.pathname.includes("review")) Review.init();
  },

  hook(){
    $(document)
      .on("mousemove", ".review .star", Review.star)
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

$(() => App.init());