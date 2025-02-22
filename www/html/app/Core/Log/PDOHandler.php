<?php

namespace App\Core\Log;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use PDO;

class PDOHandler extends AbstractProcessingHandler
{
    protected $pdo;
    protected $table;

    /**
     * 생성자
     *
     * @param PDO    $pdo   PDO 인스턴스
     * @param string $table 로그를 저장할 테이블명 (기본: logs)
     * @param int    $level 로그 레벨 (기본: Logger::DEBUG)
     * @param bool   $bubble 버블링 여부 (기본: true)
     */
    public function __construct(PDO $pdo, $table = 'logs', $level = \Monolog\Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->pdo = $pdo;
        $this->table = $table;
    }

    /**
     * 로그 기록을 데이터베이스에 저장합니다.
     *
     * @param LogRecord $record 로그 레코드
     */
    protected function write(LogRecord $record): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->table} (channel, level, message, datetime, extra) 
             VALUES (:channel, :level, :message, :datetime, :extra)"
        );

        $stmt->execute([
            ':channel'  => $record->channel,
            ':level'    => $record->level,
            ':message'  => $record->message,
            ':datetime' => $record->datetime->format('Y-m-d H:i:s'),
            ':extra'    => json_encode($record->extra)
        ]);
    }
}
