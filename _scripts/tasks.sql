-- Create Table
CREATE TABLE IF NOT EXISTS tasks (
    id BIGSERIAL,
    title VARCHAR(40) NOT NULL,
    is_done BOOLEAN NOT NULL DEFAULT FAlSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

-- Insert Dummy Data
INSERT INTO tasks (
    title
) 
VALUES 
(
    'task1'
), 
(
    'task2'
), 
(
    'task3'
);