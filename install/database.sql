-- ==========================================
-- Company Portal Database
-- Version : 1.1
-- Author  : Tricknology
-- Compatible :
-- MySQL 8.x / 9.x
-- PHP 8.x
-- IIS / Apache
-- ==========================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ==========================================
-- USERS
-- ==========================================

CREATE TABLE users
(
    id INT AUTO_INCREMENT PRIMARY KEY,

    fullname VARCHAR(150) NOT NULL,

    username VARCHAR(50) NOT NULL UNIQUE,

    email VARCHAR(150) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    phone VARCHAR(20),

    department_id INT DEFAULT NULL,

    role ENUM
    (
        'admin',
        'user'
    ) DEFAULT 'user',

    status ENUM
    (
        'active',
        'disabled'
    ) DEFAULT 'active',

    remember_token VARCHAR(255),

    last_login DATETIME DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NULL DEFAULT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- DEPARTMENTS
-- ==========================================

CREATE TABLE departments
(
    id INT AUTO_INCREMENT PRIMARY KEY,

    department_name VARCHAR(100) NOT NULL,

    description TEXT,

    status ENUM
    (
        'active',
        'disabled'
    ) DEFAULT 'active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- EMPLOYEES
-- ==========================================

CREATE TABLE employees
(
    id INT AUTO_INCREMENT PRIMARY KEY,

    employee_code VARCHAR(30) UNIQUE,

    fullname VARCHAR(150),

    email VARCHAR(150),

    phone VARCHAR(20),

    designation VARCHAR(100),

    department_id INT,

    address TEXT,

    joining_date DATE,

    profile_photo VARCHAR(255),

    status ENUM
    (
        'active',
        'disabled'
    ) DEFAULT 'active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- ANNOUNCEMENTS
-- ==========================================

CREATE TABLE announcements
(
    id INT AUTO_INCREMENT PRIMARY KEY,

    title VARCHAR(255),

    description TEXT,

    created_by INT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- DOCUMENTS
-- ==========================================

CREATE TABLE documents
(
    id INT AUTO_INCREMENT PRIMARY KEY,

    title VARCHAR(255),

    description TEXT,

    filename VARCHAR(255),

    filesize BIGINT,

    uploaded_by INT,

    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- UPLOADS
-- ==========================================

CREATE TABLE uploads
(
    id INT AUTO_INCREMENT PRIMARY KEY,

    title VARCHAR(255) NOT NULL,

    description TEXT,

    filename VARCHAR(255) NOT NULL,

    original_name VARCHAR(255),

    filesize BIGINT,

    filetype VARCHAR(100),

    uploaded_by INT,

    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- ACTIVITY LOGS
-- ==========================================

CREATE TABLE activity_logs
(
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT,

    activity VARCHAR(255) NOT NULL,

    ip_address VARCHAR(50),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- SETTINGS
-- ==========================================

CREATE TABLE settings
(
    id INT AUTO_INCREMENT PRIMARY KEY,

    company_name VARCHAR(255),

    company_email VARCHAR(255),

    company_phone VARCHAR(50),

    company_address TEXT,

    company_website VARCHAR(255),

    company_logo VARCHAR(255),

    timezone VARCHAR(100) DEFAULT 'Asia/Kolkata',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NULL DEFAULT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- DEFAULT DEPARTMENTS
-- ==========================================

INSERT INTO departments
(
    department_name,
    description
)
VALUES
('Administration','Administration Department'),
('Human Resource','HR Department'),
('IT','IT Department'),
('Accounts','Accounts Department'),
('Sales','Sales Department');

-- ==========================================
-- DEFAULT SETTINGS
-- ==========================================

INSERT INTO settings
(
    company_name,
    company_email,
    company_phone,
    company_address,
    company_website,
    company_logo,
    timezone
)
VALUES
(
    'My Company',
    '',
    '',
    '',
    '',
    '',
    'Asia/Kolkata'
);

-- ==========================================
-- FOREIGN KEYS
-- ==========================================

ALTER TABLE users
ADD CONSTRAINT fk_users_department
FOREIGN KEY (department_id)
REFERENCES departments(id)
ON DELETE SET NULL;

ALTER TABLE employees
ADD CONSTRAINT fk_employee_department
FOREIGN KEY (department_id)
REFERENCES departments(id)
ON DELETE SET NULL;

ALTER TABLE announcements
ADD CONSTRAINT fk_announcements_user
FOREIGN KEY (created_by)
REFERENCES users(id)
ON DELETE SET NULL;

ALTER TABLE documents
ADD CONSTRAINT fk_documents_user
FOREIGN KEY (uploaded_by)
REFERENCES users(id)
ON DELETE SET NULL;

ALTER TABLE uploads
ADD CONSTRAINT fk_uploads_user
FOREIGN KEY (uploaded_by)
REFERENCES users(id)
ON DELETE SET NULL;

ALTER TABLE activity_logs
ADD CONSTRAINT fk_logs_user
FOREIGN KEY (user_id)
REFERENCES users(id)
ON DELETE SET NULL;

SET FOREIGN_KEY_CHECKS = 1;