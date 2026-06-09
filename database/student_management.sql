CREATE DATABASE IF NOT EXISTS student_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE student_management;
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS logs;
DROP TABLE IF EXISTS attendance;
DROP TABLE IF EXISTS grades;
DROP TABLE IF EXISTS subjects;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS teachers;
DROP TABLE IF EXISTS groups_list;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE groups_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    course INT NOT NULL,
    specialty VARCHAR(150),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(120),
    phone VARCHAR(50),
    department VARCHAR(120),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    birth_date DATE,
    email VARCHAR(120),
    phone VARCHAR(50),
    address VARCHAR(255),
    admission_year INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES groups_list(id) ON DELETE CASCADE
);

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NULL,
    name VARCHAR(150) NOT NULL,
    hours_count INT DEFAULT 0,
    semester INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
);

CREATE TABLE grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    grade_value INT NOT NULL,
    grade_date DATE NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    CHECK (grade_value BETWEEN 1 AND 5)
);

CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    lesson_date DATE NOT NULL,
    status VARCHAR(20) NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO roles (name, description) VALUES
('admin', 'Администратор системы'),
('teacher', 'Преподаватель'),
('employee', 'Сотрудник учебной части');

INSERT INTO users (role_id, full_name, email, password) VALUES
(1, 'Администратор', 'admin@mail.com', '$2y$10$8pCkWH4xviFu1zQz0W3S0uvWvPYdQfRxTSvjku2P85uJ41gd8Vrfi');

INSERT INTO groups_list (name, course, specialty) VALUES
('ИС-21', 2, 'Информационные системы'),
('ПО-31', 3, 'Программное обеспечение'),
('ДО-11', 1, 'Дизайн и обработка информации');

INSERT INTO teachers (full_name, email, phone, department) VALUES
('Смирнова Елена Павловна', 'smirnova@mail.com', '+375291111111', 'Информатика'),
('Орлов Андрей Николаевич', 'orlov@mail.com', '+375292222222', 'Математика');

INSERT INTO students (group_id, full_name, birth_date, email, phone, address, admission_year) VALUES
(1, 'Петров Иван Сергеевич', '2007-03-12', 'petrov@mail.com', '+375291234567', 'г. Минск, ул. Студенческая, 5', 2024),
(1, 'Сидорова Анна Викторовна', '2007-07-20', 'sidorova@mail.com', '+375292345678', 'г. Минск, пр. Независимости, 12', 2024),
(2, 'Козлов Максим Андреевич', '2006-11-05', 'kozlov@mail.com', '+375293456789', 'г. Минск, ул. Центральная, 8', 2023);

INSERT INTO subjects (teacher_id, name, hours_count, semester) VALUES
(1, 'Базы данных', 72, 3),
(1, 'Веб-программирование', 90, 4),
(2, 'Математика', 80, 2);

INSERT INTO grades (student_id, subject_id, grade_value, grade_date, comment) VALUES
(1, 1, 5, '2026-02-10', 'Отличная работа'),
(2, 1, 4, '2026-02-10', 'Хорошо'),
(3, 2, 5, '2026-02-11', 'Отлично');

INSERT INTO attendance (student_id, subject_id, lesson_date, status, comment) VALUES
(1, 1, '2026-02-10', 'present', ''),
(2, 1, '2026-02-10', 'absent', 'Болезнь'),
(3, 2, '2026-02-11', 'late', 'Опоздал на 10 минут');

INSERT INTO logs (user_id, action) VALUES
(1, 'Создание начальных данных');
