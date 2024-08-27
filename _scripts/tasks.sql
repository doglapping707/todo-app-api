-- テーブル作成
CREATE TABLE IF NOT EXISTS tasks (
    id BIGSERIAL,
    title VARCHAR(40) NOT NULL,
    is_done BOOLEAN NOT NULL DEFAULT FAlSE,
    user_id BIGSERIAL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- ダミーデータ
INSERT INTO tasks (
    title,
    user_id
) 
VALUES 
(
    'task1',
    1
), 
(
    'task2',
    2
), 
(
    'task3',
    3
);