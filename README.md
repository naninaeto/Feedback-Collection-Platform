# Feedback Collection Platform

A secure web application for collecting and managing user feedback.

## Features

### User Features
- ✅ Register and login system
- 📝 Submit feedback (500 character limit)
- 🖥️ User dashboard

### Admin Features
- 👨‍💻 Admin panel
- 🔍 Filter feedback by user/date/status
- ✔️ Mark feedback as reviewed

## Tech Stack
- **Backend**: PHP, MySQL
- **Frontend**: Tailwind CSS, JavaScript
- **Security**: BCRYPT hashing, PDO prepared statements

## Installation

1. **Requirements**
   - PHP 7.4+
   - MySQL 5.7+
   - Web server (Apache/Nginx)

2. **Database Setup**
   ```sql
   CREATE DATABASE feedback_system;
   USE feedback_system;
   
   -- Create users table
   CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(50) NOT NULL UNIQUE,
       email VARCHAR(100) NOT NULL UNIQUE,
       password VARCHAR(255) NOT NULL,
       role ENUM('user', 'admin') DEFAULT 'user',
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   
   -- Create feedback table
   CREATE TABLE feedback (
       id INT AUTO_INCREMENT PRIMARY KEY,
       user_id INT NOT NULL,
       feedback TEXT NOT NULL,
       status ENUM('pending', 'reviewed') DEFAULT 'pending',
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
   );
