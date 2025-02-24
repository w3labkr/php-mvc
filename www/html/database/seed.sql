DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `logs`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    `reset_password_token` VARCHAR(255) DEFAULT NULL,
    `reset_password_token_expires` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `channel` VARCHAR(50) NOT NULL,        -- 로그 채널 (예: app, security, database 등)
    `level` VARCHAR(20) NOT NULL,          -- 로그 레벨 (예: DEBUG, INFO, WARNING, ERROR)
    `message` TEXT NOT NULL,               -- 로그 메시지
    `context` JSON NULL,                   -- 추가 데이터 (JSON 형식)
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- 로그 생성 시간
    INDEX idx_channel (channel),
    INDEX idx_level (level),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
