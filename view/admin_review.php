<div class="header_emp"></div>

<div class="content">

  <div class="admin_game_section">
    <div class="wrap">
      <div class="title">
        <h1>리뷰 관리</h1>
        <div class="line"></div>
        <p>리뷰를 관리해보세요</p>
      </div>

      <div class="admin_game">
        <div class="top flex jcsb">

          <div class="input_box">
            <label for="sort_type">정렬</label>
            <select name="sort_type" id="sort_type" onchange="setData()">
              <option value="idx_ASC">날짜 오름차순</option>
              <option value="idx_DESC" selected>날짜 내림차순</option>
              <option value="score_ASC">별점 오름차순</option>
              <option value="score_DESC">별점 내림차순</option>
            </select>
          </div>


          <div class="btn_box">
            <div class="input_box">
              <label for="search">검색창</label>
              <input type="text" id="search">
            </div>

            <div class="btn" onclick="setData()">검색</div>
          </div>

        </div>

        <table>
          <thead>
            <tr>
              <th>번호</th>
              <th>내용</th>
              <th>별점</th>
              <th>방문일</th>
              <th>등록일</th>
              <th>비고</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<script>

  let json_data = []

  function loadData(){
    $.getJSON("/review_data")
      .then(res => {
        json_data = res;

        setData();
      })
  }

  function setData(){
    const [sort, dir] = $("#sort_type").val().split("_");
    const keyword = new RegExp(`(${$("#search").val()})`, "g");

    json_data.sort((a, b) => dir == "ASC" ? a[sort] - b[sort] : b[sort] - a[sort]);
    $(".admin_game tbody").html([...json_data].map(v => {
      const change = {
        idx : v.idx,
        content : v.content,
        score : v.score,
        visited_dt : v.visited_dt,
        create_dt : v.create_dt
      };
      let chk = false; 

      for(let key in change){
        if(!chk) chk = keyword.test(change[key]);
        change[key] = change[key].replace(keyword, "<span>$1</span>");
      }


      if(!chk) return "";
      else return `
        <tr>
          <td>${change.idx}</td>
          <td>${change.content}</td>
          <td>${change.score}</td>
          <td>${change.visited_dt}</td>
          <td>${change.create_dt}</td>
          <td>
            <div class="btn_box">
              <a href="/modify/${v.idx}" class="btn">수정</a>
              <a href="/delete/review/${v.idx}" class="btn tomato">삭제</a>
            </div>
          </td>
        </tr>
      `
    }));
  }

  loadData();

</script>