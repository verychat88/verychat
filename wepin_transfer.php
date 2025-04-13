
<?php
// wepin_transfer.php

// Access Token 체크
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo "Authorization 헤더 없음";
    exit;
}

// JSON 파라미터 받기
$input = json_decode(file_get_contents("php://input"), true);
$to = $input['to'] ?? '';
$amount = $input['amount'] ?? '';

if (!$to || !$amount) {
    http_response_code(400);
    echo "지갑 주소와 수량을 입력해주세요.";
    exit;
}

// 운영자 지갑 주소 (from)
$from = "0x5Dd6593237eA26C012a9e41bb4D71018644C62b2";

// 로그 저장 (DB 대신 파일로 저장 테스트용)
$log = "[" . date("Y-m-d H:i:s") . "] from: $from → to: $to / amount: $amount\n";
file_put_contents("transfer_log.txt", $log, FILE_APPEND);

echo json_encode([
  "status" => "success",
  "from" => $from,
  "to" => $to,
  "amount" => $amount
]);
?>
