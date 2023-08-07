<div class="header_emp"></div>

<div class="content">

  <div class="admin_game_section">
    <div class="wrap">
      <div class="title">
        <h1>게임 관리</h1>
        <div class="line"></div>
        <p>게임을 관리해보세요</p>
      </div>

      <div class="admin_game">
        <div class="top flex jcsb">

          <div class="input_box">
            <label for="sort_type">정렬</label>
            <select name="sort_type" id="sort_type" onchange="setData()">
              <option value="idx_ASC">날짜 오름차순</option>
              <option value="idx_DESC" selected>날짜 내림차순</option>
              <option value="score_ASC">점수 오름차순</option>
              <option value="score_DESC">점수 내림차순</option>
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
              <th>게임 이름</th>
              <th>사용자 이름</th>
              <th>게임 결과</th>
              <th>데이터 삽입 날짜</th>
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
    $.getJSON("/record_data")
      .then(res => {
        json_data = res;

        setData();
      })
  }

  function setData(){
    const game = {
      "snake" : "호두과자 먹는 캐릭터 게임",
      "drop" : "천안 명물 받아먹기 게임",
    }
    const [sort, dir] = $("#sort_type").val().split("_");
    const keyword = new RegExp(`(${$("#search").val()})`, "g");

    json_data.sort((a, b) => dir == "ASC" ? a[sort] - b[sort] : b[sort] - a[sort]);
    $(".admin_game tbody").html([...json_data].map(v => {
      const change = {
        idx : v.idx,
        game : game[v.game],
        name : v.name,
        score : v.score,
        create_dt : v.create_dt
      };
      let chk = false; 
      let name = v.name;

      if((/[A-z]/g).test(name)){
        const length = v.name.length;
        change.name = name.substr(0, 3).padEnd(length, "*");
      }

      if((/[ㄱ-힣]/g).test(name)){
        change.name = kor.reduce((acc, v) => acc.replace(...v), name);
      }

      for(let key in change){
        if(!chk) chk = keyword.test(change[key]);
        change[key] = change[key].replace(keyword, "<span>$1</span>");
      }


      if(!chk) return "";
      else return `
        <tr>
          <td>${change.idx}</td>
          <td>${change.game}</td>
          <td>${change.name}</td>
          <td>${change.score}점</td>
          <td>${change.create_dt}</td>
          <td><a href="/delete/record/${v.idx}" class="btn tomato">삭제</a></td>
        </tr>
      `
    }));
  }

  loadData();

</script>