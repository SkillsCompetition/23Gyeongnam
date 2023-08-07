  <div class="header_emp"></div>

  <div class="content">

    <div class="game_section">
      <div class="wrap">
        <div class="title">
          <h1>관광게임하기</h1>
          <div class="line"></div>
          <p>관광 게임을 즐겨보세요</p>
        </div>

        <div class="game">
          <div class="top flex jcsb">
            <div class="btn_box">
              <div class="btn" onclick="Game.changeGame(this)" data-game="snake">호두과자 먹는 캐릭터 게임</div>
              <div class="btn" onclick="Game.changeGame(this)" data-game="drop">청안 명물 받아먹기 게임</div>
            </div>

            <div class="btn_box">
              <div class="input_box">
                <label for="name">이름</label>
                <input type="text" name="name" id="name" placeholder="이름을 입력하세요">
              </div>
              <div class="btn" onclick="Game.openGame()">게임 시작</div>
            </div>
          </div>

          <div class="gamezone" style="display: none;">
            <div class="snake">
              <div class="canvas_box">
                <canvas id="snake" width="600" height="600"></canvas>
              </div>
              <div class="col-flex">
                <div class="game_btn_box">
                  <h3>---님</h3>
                  <div class="game_status">
                    <div class="timer">
                      <h3>남은 시간</h3>
                      <h1 class="timer_display">00:00</h1>
                    </div>

                    <div class="score">
                      <h3>점수</h3>
                      <h1 class="score_display"><span>0</span>점</h1>
                    </div>
                  </div>
                  
                  <div class="btn_box full">
                    <div class="btn staus_btn" onclick="Snake.changeStatus()">일시정지</div>
                    <div class="btn" onclick="Snake.restart()">다시하기</div>
                    <div class="btn" onclick="Snake.restart()">초기화</div>
                  </div>
                </div>
  
                <div class="info">
                  <div class="flex name_box aic">
                    <img src="resources/img/game/icon.png" alt="">
                    <h2>호두과자 먹는 뱀 게임</h2>
                  </div>
  
                  <div class="btn">규칙 설명</div>
  
                  <div class="description">
                    <div style="height: 20px;"></div>
                    <p>20 X 20의 칸에서 뱀이 움직입니다. 
                      뱀의 방향은 방향키(△, ▽, ◁, ▷)를 통해 제어합니다.
                      랜덤으로 생성되는 호두과자를 하나씩 먹을 때마다 뱀의 길이도 늘어납니다.
                      벽에 닿으면 뱀은 죽고, 게임이 끝납니다.</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="drop" style="display: none;">
              <div class="canvas_box">
                <canvas id="drop" width="600" height="600"></canvas>
              </div>
              <div class="col-flex">
                <div class="game_btn_box">
                  <h3>---님</h3>
                  <div class="game_status">
                    <div class="timer">
                      <h3>남은 시간</h3>
                      <h1 class="timer_display">00:00</h1>
                    </div>

                    <div class="score">
                      <h3>점수</h3>
                      <h1 class="score_display"><span>0</span>점</h1>
                    </div>
                  </div>
                </div>
  
                <div class="info">
                  <div class="flex name_box aic">
                    <img src="resources/img/game/icon.png" alt="">
                    <h2>천안 명물 맞추기 게임</h2>
                  </div>
  
                  <div class="btn">규칙 설명</div>
  
                  <div class="description">
                    <div style="height: 20px;"></div>
                    <p>하늘에서 천안의 명물인 호두과자, 병천순대, 거봉과 다른 물건들이 떨어집니다.
                      캐릭터를 방향키(◁, ▷)로 움직여 떨어지는 천안의 명물인 호두과자, 병천순대, 거봉을 
                      먹고, 다른물건을 피하는 게임입니다.
                      3번 이상 다른 물건을 맞으면 게임이 종료됩니다.
                      게임은 3분동안 진행되고, 물건이 떨어지는 속도도 더 빨라집니다.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <template>

    <div class="result_modal">
      <div class="main inputs aic">
        <h3 class="title">스네이크게임 ( 정연수님 )</h3>

        <h1 class="score">0점</h1>

        <div class="record">
          <h3>기록 목록</h3>

          <div class="container col-flex">
            <div class="top">
              <div>사용자의 이름</div>
              <div>게임 번호</div>
              <div>게임 결과</div>
              <div>시간</div>
            </div>
            <div class="tbody col-flex">

            </div>
          </div>
        </div>
      </div>
      <div class="btn_box">
        <div class="btn" onclick="Game.save(this)">저장</div>
        <div class="btn" onclick="Modal.close()">돌아가기</div>
      </div>
    </div>

  </template>