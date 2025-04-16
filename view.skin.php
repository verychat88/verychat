<?php
// 페이지 맨 위에 넣어보세요
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<?php
// -------------------------
// 업데이트 요청 처리 (AJAX)
// -------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trade_update'])) {
    // _common.php 는 이미 로드되어 있다고 가정합니다.
    header('Content-Type: application/json');

    // 로그인 확인
    if (!isset($member['mb_id'])) {
        echo json_encode(["success" => false, "error" => "로그인 후 이용해 주세요."]);
        exit;
    }

    // POST 데이터 받기  
    // partner는 isset 여부를 구분하기 위해 기본값을 null로 처리
    $wr_id  = isset($_POST['wr_id']) ? intval($_POST['wr_id']) : 0;
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';
    $partner = (isset($_POST['partner'])) ? trim($_POST['partner']) : null;

    if ($wr_id <= 0 || $status === '') {
        echo json_encode(["success" => false, "error" => "잘못된 요청입니다."]);
        exit;
    }

    // 권한 확인: 글 작성자 본인 또는 관리자여야 함
    $sql = "SELECT mb_id, wr_6 FROM g5_write_verytrade WHERE wr_id = '{$wr_id}'";
    $row = sql_fetch($sql);
    if (!$row) {
        echo json_encode(["success" => false, "error" => "존재하지 않는 게시글입니다."]);
        exit;
    }
    if ($row['mb_id'] !== $member['mb_id'] && !$is_admin) {
        echo json_encode(["success" => false, "error" => "권한이 없습니다."]);
        exit;
    }

    // 상태에 따른 처리
    // 작성자가 '대기'에서 '거래중'으로 변경할 때는 partner가 필수입니다.
    if ($status === '거래중' && ($partner === null || $partner === "")) {
        echo json_encode(["success" => false, "error" => "거래할 상대 아이디를 입력해 주세요."]);
        exit;
    }
    // '대기'로 변경할 때는 거래 상대정보 초기화
    if ($status === '대기') {
        $partner = "";
    }
    // 관리자 등에서 '거래완료'로 변경 시 partner 값이 넘어오지 않는 경우  
    // DB에 저장된 기존 값을 유지 (이미 저장된 값이 없으면 빈값으로 업데이트됨)
    else if ($status === '거래완료' && ($partner === null || $partner === "")) {
        $partner = $row['wr_6'];
    }

    // 업데이트 쿼리: 거래상태(wr_5)와 거래할 상대아이디(wr_6)를 업데이트
    $sql = "UPDATE g5_write_verytrade SET wr_5 = '{$status}', wr_6 = '{$partner}' WHERE wr_id = '{$wr_id}'";
    $result = sql_query($sql);
    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "데이터베이스 업데이트 실패"]);
    }
    exit;
}
?>

<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet: 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<!-- 게시물 읽기 시작 { -->
<article id="bo_v" style="width:<?php echo $width; ?>">
  <header>
    <h2 id="bo_v_title">
      <?php if ($category_name) { ?>
        <span class="bo_v_cate"><?php echo $view['ca_name']; // 분류 출력 ?></span>
      <?php } ?>
      <span class="bo_v_tit">
        <?php echo cut_str(get_text($view['wr_subject']), 70); // 글제목 출력 ?>
      </span>
    </h2>
  </header>

  <section id="bo_v_info">
    <h2>페이지 정보</h2>
    <div class="profile_info">
      <div class="pf_img">
        <?php echo get_member_profile_img($view['mb_id']); ?>
      </div>
      <div class="profile_info_ct">
        <span class="sound_only">작성자</span>
        <strong><?php echo $view['name']; ?><?php if ($is_ip_view) { echo "&nbsp;($ip)"; } ?></strong><br>
        <span class="sound_only">댓글</span>
        <strong>
          <a href="#bo_vc">
            <i class="fa fa-commenting-o" aria-hidden="true"></i>
            <?php echo number_format($view['wr_comment']); ?>건
          </a>
        </strong>
        <span class="sound_only">조회</span>
        <strong>
          <i class="fa fa-eye" aria-hidden="true"></i> <?php echo number_format($view['wr_hit']); ?>회
        </strong>
        <strong class="if_date">
          <span class="sound_only">작성일</span>
          <i class="fa fa-clock-o" aria-hidden="true"></i>
          <?php echo date("y-m-d H:i", strtotime($view['wr_datetime'])); ?>
        </strong>
      </div>
    </div>
  </section>

  <!-- 거래정보 영역 시작 -->
  <?php if (isset($view['wr_1']) && $view['wr_1']) { ?>
    <section id="bo_trade_info">
      <h2>거래정보</h2>
        <form method="POST" action="/bbs/wepin_transfer.php" onsubmit="return confirm('정말 거래를 완료하시겠습니까?')">
    <input type="hidden" name="wr_id" value="<?php echo $view['wr_id']; ?>">
    <input type="hidden" name="to" value="<?php echo $view['wr_trade_buyer_wallet']; ?>">
    <input type="hidden" name="amount" value="<?php echo $view['wr_trade_amount']; ?>">
    <button type="submit" class="btn_b01">✅ 거래 완료 (베리 전송)</button>
</form>

      <ul class="trade-details">
        <li><strong>거래유형:</strong> <?php echo $view['wr_1']; ?></li>
        <li><strong>1개당 가격:</strong> <?php echo $view['wr_2']; ?> 원</li>
        <li><strong>수량:</strong> <?php echo $view['wr_3']; ?></li>
        <li><strong>총액:</strong> <?php echo $view['wr_4']; ?></li>
        <li class="<?php echo ($view['wr_5'] === '거래완료') ? 'trade-completed' : ''; ?>">
          <strong>거래상태:</strong> <?php echo $view['wr_5']; ?>
        </li>
        <li><strong>거래날짜:</strong> <?php echo date("Y-m-d H:i", strtotime($view['wr_datetime'])); ?></li>
      </ul>
      <!-- 오른쪽에 거래 신청 버튼 -->
      <div class="trade-apply">
        <a href="https://open.kakao.com/o/gu5lbjqh" target="_blank" class="btn_trade_apply">채팅방이동</a>
      </div>
<!-- 작성자용 거래 상태 변경 (대기 ↔ 거래중) -->
<?php if (isset($member['mb_id']) && $view['mb_id'] === $member['mb_id'] && ($view['wr_5'] === '대기' || $view['wr_5'] === '거래중')) { ?>
  <div class="trade-status-update" style="display: flex; align-items: center;">
    <label for="trade_partner">거래할 상대 아이디:</label>
    <input type="text" id="trade_partner" name="trade_partner" 
           value="<?php echo isset($view['wr_6']) ? $view['wr_6'] : ''; ?>" 
           placeholder="거래 상대 아이디" 
           <?php echo ($view['wr_5'] === '거래중') ? 'disabled style="background-color:#eee;"' : ''; ?>>
    <button type="button" id="btn_toggle_status" class="btn_toggle_status" style="margin-left: 5px;">
      <?php echo ($view['wr_5'] == '대기') ? '거래중으로 변경' : '대기로 변경'; ?>
    </button>
    <!-- 항상 보이는 새로고침(확인) 버튼 -->
    <button type="button" id="btn_refresh_status" class="btn_refresh_status" style="margin-left: 5px;">
      확인
    </button>
  </div>
<?php } ?>

<!-- 관리자용: 거래중 → 거래완료 상태 변경 -->
<?php if (isset($is_admin) && $is_admin && $view['wr_5'] === '거래중') { ?>
  <div class="trade-status-update-admin" style="display: flex; align-items: center;">
    <button type="button" id="btn_complete_status" class="btn_complete_status">
      거래완료로 변경 (관리자)
    </button>
    <!-- 항상 보이는 새로고침(확인) 버튼 -->
    <button type="button" id="btn_refresh_admin" class="btn_refresh_admin" style="margin-left: 5px;">
      확인
    </button>
  </div>
<?php } ?>

    </section>
  <?php } ?>
  <!-- 거래정보 영역 끝 -->

  <!-- 게시물 상단 버튼 시작 { -->
  <div id="bo_v_top">
    <?php ob_start(); ?>
    <ul class="btn_bo_user bo_v_com">
      <li>
        <a href="<?php echo $list_href; ?>" class="btn_b01 btn" title="목록">
          <i class="fa fa-list" aria-hidden="true"></i>
          <span class="sound_only">목록</span>
        </a>
      </li>
      <?php if ($reply_href) { ?>
        <li>
          <a href="<?php echo $reply_href; ?>" class="btn_b01 btn" title="답변">
            <i class="fa fa-reply" aria-hidden="true"></i>
            <span class="sound_only">답변</span>
          </a>
        </li>
      <?php } ?>
      <?php if ($write_href) { ?>
        <li>
          <a href="<?php echo $write_href; ?>" class="btn_b01 btn" title="글쓰기">
            <i class="fa fa-pencil" aria-hidden="true"></i>
            <span class="sound_only">글쓰기</span>
          </a>
        </li>
      <?php } ?>
      <?php if($update_href || $delete_href || $copy_href || $move_href || $search_href) { ?>
        <li>
          <button type="button" class="btn_more_opt is_view_btn btn_b01 btn">
            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
            <span class="sound_only">게시판 리스트 옵션</span>
          </button>
          <ul class="more_opt is_view_btn">
            <?php if ($update_href) { ?>
              <li><a href="<?php echo $update_href; ?>">수정<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></li>
            <?php } ?>
            <?php if ($delete_href) { ?>
              <li><a href="<?php echo $delete_href; ?>" onclick="del(this.href); return false;">삭제<i class="fa fa-trash-o" aria-hidden="true"></i></a></li>
            <?php } ?>
            <?php if ($copy_href) { ?>
              <li><a href="<?php echo $copy_href; ?>" onclick="board_move(this.href); return false;">복사<i class="fa fa-files-o" aria-hidden="true"></i></a></li>
            <?php } ?>
            <?php if ($move_href) { ?>
              <li><a href="<?php echo $move_href; ?>" onclick="board_move(this.href); return false;">이동<i class="fa fa-arrows" aria-hidden="true"></i></a></li>
            <?php } ?>
            <?php if ($search_href) { ?>
              <li><a href="<?php echo $search_href; ?>">검색<i class="fa fa-search" aria-hidden="true"></i></a></li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
    </ul>
    <script>
      jQuery(function($){
        // 게시판 보기 버튼 옵션
        $(".btn_more_opt.is_view_btn").on("click", function(e) {
          e.stopPropagation();
          $(".more_opt.is_view_btn").toggle();
        });
        $(document).on("click", function (e) {
          if(!$(e.target).closest('.is_view_btn').length) {
            $(".more_opt.is_view_btn").hide();
          }
        });
      });
    </script>
    <?php
    $link_buttons = ob_get_contents();
    ob_end_flush();
    ?>
  </div>
  <!-- } 게시물 상단 버튼 끝 -->



  <?php
  $cnt = 0;
  if ($view['file']['count']) {
    for ($i=0; $i<count($view['file']); $i++) {
      if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
        $cnt++;
    }
  }
  ?>

  <?php if($cnt) { ?>
    <!-- 첨부파일 시작 { -->
    <section id="bo_v_file">
      <h2>첨부파일</h2>
      <ul>
      <?php
      for ($i=0; $i<count($view['file']); $i++) {
        if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
      ?>
          <li>
            <i class="fa fa-folder-open" aria-hidden="true"></i>
            <a href="<?php echo $view['file'][$i]['href']; ?>" class="view_file_download">
              <strong><?php echo $view['file'][$i]['source']; ?></strong> <?php echo $view['file'][$i]['content']; ?>
              (<?php echo $view['file'][$i]['size']; ?>)
            </a>
            <br>
            <span class="bo_v_file_cnt">
              <?php echo $view['file'][$i]['download']; ?>회 다운로드 | DATE : <?php echo $view['file'][$i]['datetime']; ?>
            </span>
          </li>
      <?php
        }
      }
      ?>
      </ul>
    </section>
    <!-- } 첨부파일 끝 -->
  <?php } ?>

  <?php if(isset($view['link']) && array_filter($view['link'])) { ?>
    <!-- 관련링크 시작 { -->
    <section id="bo_v_link">
      <h2>관련링크</h2>
      <ul>
      <?php
      $cnt = 0;
      for ($i=1; $i<=count($view['link']); $i++) {
        if ($view['link'][$i]) {
          $cnt++;
          $link = cut_str($view['link'][$i], 70);
      ?>
            <li>
              <i class="fa fa-link" aria-hidden="true"></i>
              <a href="<?php echo $view['link_href'][$i]; ?>" target="_blank">
                <strong><?php echo $link; ?></strong>
              </a>
              <br>
              <span class="bo_v_link_cnt">
                <?php echo $view['link_hit'][$i]; ?>회 연결
              </span>
            </li>
      <?php
        }
      }
      ?>
      </ul>
    </section>
    <!-- } 관련링크 끝 -->
  <?php } ?>

  <?php if ($prev_href || $next_href) { ?>
    <ul class="bo_v_nb">
      <?php if ($prev_href) { ?>
        <li class="btn_prv">
          <span class="nb_tit">
            <i class="fa fa-chevron-up" aria-hidden="true"></i> 이전글
          </span>
          <a href="<?php echo $prev_href; ?>"><?php echo $prev_wr_subject; ?></a>
          <span class="nb_date"><?php echo str_replace('-', '.', substr($prev_wr_date, 2, 8)); ?></span>
        </li>
      <?php } ?>
      <?php if ($next_href) { ?>
        <li class="btn_next">
          <span class="nb_tit">
            <i class="fa fa-chevron-down" aria-hidden="true"></i> 다음글
          </span>
          <a href="<?php echo $next_href; ?>"><?php echo $next_wr_subject; ?></a>
          <span class="nb_date"><?php echo str_replace('-', '.', substr($next_wr_date, 2, 8)); ?></span>
        </li>
      <?php } ?>
    </ul>
  <?php } ?>

  <?php
  ?>
</article>
<!-- } 게시글 읽기 끝 -->

<script>
<?php if ($board['bo_download_point'] < 0) { ?>
  $(function() {
    $("a.view_file_download").click(function() {
      if (!g5_is_member) {
        alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
        return false;
      }
      var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']); ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";
      if (confirm(msg)) {
        var href = $(this).attr("href") + "&js=on";
        $(this).attr("href", href);
        return true;
      } else {
        return false;
      }
    });
  });
<?php } ?>

function board_move(href) {
  window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
</script>

<script>
$(function() {
  $("a.view_image").click(function() {
    window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
    return false;
  });

  $("#good_button, #nogood_button").click(function() {
    var $tx;
    if (this.id == "good_button")
      $tx = $("#bo_v_act_good");
    else
      $tx = $("#bo_v_act_nogood");

    excute_good(this.href, $(this), $tx);
    return false;
  });

  $("#bo_v_atc").viewimageresize();
});

/* 추천/비추천 함수 */
function excute_good(href, $el, $tx) {
  $.post(
    href,
    { js: "on" },
    function(data) {
      if (data.error) {
        alert(data.error);
        return false;
      }
      if (data.count) {
        $el.find("strong").text(number_format(String(data.count)));
        if ($tx.attr("id").search("nogood") > -1) {
          $tx.text("이 글을 비추천하셨습니다.");
          $tx.fadeIn(200).delay(2500).fadeOut(200);
        } else {
          $tx.text("이 글을 추천하셨습니다.");
          $tx.fadeIn(200).delay(2500).fadeOut(200);
        }
      }
    },
    "json"
  );
}

/* 거래상태 변경 AJAX 처리 (현재 뷰페이지 자체에서 처리) */
/* 거래상태 변경 AJAX 처리 (현재 뷰페이지 자체에서 처리) */
jQuery(function($) {
    // 작성자용: 대기 ↔ 거래중 상태 전환
    $("#btn_toggle_status").click(function() {
        var partner = $("#trade_partner").val().trim();
        var currentStatus = "<?php echo $view['wr_5']; ?>";
        var newStatus = (currentStatus === '대기') ? '거래중' : '대기';

        if (currentStatus === '대기' && partner === "") {
            alert("거래할 상대 아이디를 입력해 주세요.");
            $("#trade_partner").focus();
            return;
        }

        // AJAX POST 요청으로 업데이트 실행 (즉시 실행)
        $.post(window.location.href, {
            trade_update: 1,
            wr_id: "<?php echo $view['wr_id']; ?>",
            status: newStatus,
            partner: partner
        }, function(data) {
            if (data.success) {
                alert("거래상태가 변경되었습니다.");
                // 거래상태 표시 업데이트 (옵션)
                $(".trade-details li").each(function() {
                    if ($(this).text().indexOf("거래상태:") > -1) {
                        $(this).html("<strong>거래상태:</strong> " + newStatus);
                    }
                });
                // 버튼 텍스트 업데이트
                $("#btn_toggle_status").text((newStatus === '대기') ? '거래중으로 변경' : '대기로 변경');
                // 거래 상대 입력란 처리
                if(newStatus === '대기'){
                    $("#trade_partner").val("").prop("disabled", false).css("background-color", "");
                } else if(newStatus === '거래중'){
                    $("#trade_partner").prop("disabled", true).css("background-color", "#eee");
                }
            } else {
                alert("상태 변경에 실패하였습니다.\n" + data.error);
            }
        }, "json");
    });

    // 작성자용 새로고침(확인) 버튼: 클릭하면 페이지 새로고침
    $("#btn_refresh_status").click(function() {
        window.location.reload();
    });

    // 관리자용: 거래중 → 거래완료 상태 변경
    $("#btn_complete_status").click(function() {
        $.post(window.location.href, {
            trade_update: 1,
            wr_id: "<?php echo $view['wr_id']; ?>",
            status: "거래완료"
            // partner 값은 전송하지 않아 서버에서 기존 값을 유지함
        }, function(data) {
            if (data.success) {
                alert("거래상태가 거래완료로 변경되었습니다.");
                $(".trade-details li").each(function() {
                    if ($(this).text().indexOf("거래상태:") > -1) {
                        $(this).html("<strong>거래상태:</strong> 거래완료");
                    }
                });
                $(".trade-status-update-admin").hide();
            } else {
                alert("상태 변경에 실패하였습니다.\n" + data.error);
            }
        }, "json");
    });

    // 관리자용 새로고침(확인) 버튼: 클릭하면 페이지 새로고침
    $("#btn_refresh_admin").click(function() {
        window.location.reload();
    });
});


</script>
<!-- } 게시글 읽기 끝 -->
