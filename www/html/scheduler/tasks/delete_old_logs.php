<?php

use App\Core\Database;
use App\Core\Log\PDOHandler;
use Monolog\Logger;

return function($scheduler) {
    $scheduler->call(function() {
        // 데이터베이스 연결
        $db = Database::getInstance();

        // 로거 생성: 로그 기록은 logs 테이블에 저장 (로그 레벨 INFO 이상)
        $logger = new Logger('delete_old_logs');
        $logger->pushHandler(new PDOHandler($db, 'logs', Logger::INFO));

        try {
            // 트랜잭션 시작
            $db->beginTransaction();
            // 3개월 이전의 로그 삭제
            $stmt = $db->prepare("DELETE FROM logs WHERE datetime < DATE_SUB(NOW(), INTERVAL 3 MONTH)");
            $stmt->execute();
            $deleted = $stmt->rowCount();

            // 트랜잭션 커밋
            $db->commit();

            // 삭제 작업에 대한 로그 기록 (현재 시각의 로그는 삭제 대상이 아님)
            $logger->info("Deleted old logs", ['deleted' => $deleted]);
        } catch (\Exception $e) {
            // 오류 발생 시 롤백 및 에러 로그 기록
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $logger->error("Error deleting old logs", ['error' => $e->getMessage()]);
        }
    })
    // 매일 02:00에 실행
    ->daily('02:00');
};
