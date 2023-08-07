<div class="header_emp"></div>

<div class="content">

  <div class="review_section">
    <div class="wrap">
      <div class="title">
        <h1>리뷰 작성</h1>
        <div class="line"></div>
        <p>명소에 대해 리뷰를 작성해보세요</p>
      </div>

      <form class="review inputs" action="/modify" method="POST">
        <input type="text" name="idx" value="<?= $review["idx"] ?>" hidden>
        <div class="input_box">
          <label for="title">명소의 이름</label>
          <input type="text" name="title" id="title" value="<?= $attraction["title"] ?>" disabled>
        </div>
        
        <div class="input_box">
          <label for="visited_dt">방문날짜</label>
          <input type="date" name="visited_dt" id="visited_dt" value="<?= $review["visited_dt"] ?>" max="<?= date("Y-m-d") ?>">
        </div>

        <div class="input_box">
          <label for="score">별점</label>
          <div class="star_box flex jcsb aic">
            <div class="star">
              <div class="emp flex">★★★★★</div>
              <div class="fill flex" style="width:<?= $review["score"] * 18 ?>px;">★★★★★</div>
            </div>

            <p class="score_display">5.0</p>
            <input type="text" id="score" name="score" value="<?= $review["score"] ?>" hidden>
          </div>
        </div>

        <div class="input_box text">
          <label for="content">리뷰내용</label>
          <textarea name="content" id="content" placeholder="다른 방문자들을 위한 리뷰를 남겨주세요"><?= $review["content"] ?></textarea>
        </div>

        <button class="btn">수정</button>
      </form>
    </div>
  </div>

</div>