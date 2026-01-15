CREATE TABLE grammar_tests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level VARCHAR(10), -- A1, A2, B1
    title VARCHAR(100)
);

CREATE TABLE grammar_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    test_id INT,
    question TEXT,
    type VARCHAR(20), -- mcq | reorder
    option_a TEXT,
    option_b TEXT,
    option_c TEXT,
    option_d TEXT,
    correct_answer TEXT,
    grammar_tag VARCHAR(50), -- tense, preposition
    FOREIGN KEY (test_id) REFERENCES grammar_tests(id)
);

CREATE TABLE grammar_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    test_id INT,
    score INT,
    wrong_tags TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ai_scenarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    prompt TEXT
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    level VARCHAR(5) DEFAULT 'A1',
    placement_done TINYINT(1) DEFAULT 0,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE study_planner (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    day VARCHAR(15),
    topic VARCHAR(50),
    completed TINYINT(1) DEFAULT 0
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    level VARCHAR(5) DEFAULT 'A1',
    placement_done TINYINT(1) DEFAULT 0,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE vocab_sets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level VARCHAR(5),        -- A1, A2, B1...
    title VARCHAR(100),      -- Örn: "Daily Routine", "Travel"
    image_url VARCHAR(255)   -- İstersen her pakete ikon ekleyebilirsin
);

CREATE TABLE vocab_words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    set_id INT,              -- Hangi pakete ait olduğu
    word VARCHAR(100),       -- Örn: "apple"
    meaning VARCHAR(255),    -- Örn: "elma"
    example VARCHAR(255),    -- Örn: "I eat an apple every day."
    FOREIGN KEY (set_id) REFERENCES vocab_sets(id)
);

INSERT INTO vocab_sets (level, title) VALUES 
('A1', 'Daily Routine'),
('A1', 'Food & Drinks'),
('A2', 'Travel Essentials');

INSERT INTO vocab_words (set_id, word, meaning, example) VALUES
(1, 'wake up', 'uyanmak', 'I wake up at 7 AM.'),
(1, 'brush', 'fırçalamak', 'I brush my teeth every morning.'),
(1, 'breakfast', 'kahvaltı', 'I eat eggs for breakfast.'),
(2, 'coffee', 'kahve', 'Do you want some coffee?'),
(2, 'bread', 'ekmek', 'I need to buy some bread.');
