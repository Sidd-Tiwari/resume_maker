-- Database: resume_maker
CREATE DATABASE IF NOT EXISTS resume_maker;
USE resume_maker;

-- Users table (for login & signup)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Resumes table (storing user resume data)
CREATE TABLE IF NOT EXISTS resumes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    summary TEXT,
    education TEXT,
    experience TEXT,
    skills TEXT,
    selected_template VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
