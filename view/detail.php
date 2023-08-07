<div class="content">

  <div class="detail_section">
    <div class="wrap">
      <div class="title">
        <h1>명소의 정보</h1>
        <div class="line"></div>
        <p>명소의 정보를 확인해보세요</p>
      </div>

      <div class="detail flex">
        <div class="img_box">
          <img src="/resources/img/place/<?= $idx ?>.jpg" alt="">
        </div>

        <div class="text_box">
          <div class="flex jcsb aic">
            <h1><?= $attraction["title"] ?></h1>

            <p><?= $attraction["distance"] ?>km</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="star_section">
    <div class="wrap">
      <div class="title">
        <h1>별점</h1>
        <div class="line"></div>
        <p>명소의 별점을 확인해보세요.</p>
      </div>

      <div class="star_box">
        <h1 class="attraction_star">
          <span>★</span>
          <span></span>점
        </h1>

        <div class="graph">
          <div class="left">
            <div>0점</div>
            <div>1점</div>
            <div>2점</div>
            <div>3점</div>
            <div>4점</div>
            <div>5점</div>
          </div>
          <div class="right">
            <div class="line">
              <div class="point"></div>
            </div>
            <div class="line">
              <div class="point"></div>
            </div>
            <div class="line">
              <div class="point"></div>
            </div>
            <div class="line">
              <div class="point"></div>
            </div>
            <div class="line">
              <div class="point"></div>
            </div>
            <div class="line">
              <div class="point"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="reviewlist_section">
    <div class="wrap">
      <div class="title">
        <h1>리뷰 목록</h1>
        <div class="line"></div>
        <p>명소에 남긴 리뷰를 확인해보세요.</p>
      </div>

      <div class="reviewlist">
        <?php foreach($reviews as $v): ?>
          <div class="item">
            <div class="top flex jcsb aic">
              <h3><?= $attraction['title'] ?></h3>
              <small><span>★</span><?= $v["score"] ?></small>
            </div>

            <p><?= $v["content"] ?></p>

            <small>방문일 : <?= $v["visited_dt"] ?></small>
          </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>

</div>


<a href="/review?idx=<?= $idx ?>" class="review_write btn">
  리뷰 작성
</a>

<script>
  Review.graph(<?= $idx ?>);
</script>