Feedback Collection Platform
A secure web application for collecting and managing user feedback, built with PHP, MySQL, Tailwind CSS, and JavaScript. Features include user registration/login, feedback submission, and an admin panel for viewing and managing feedback.
Features

User Authentication:
Session-based authentication with BCRYPT password hashing.
User registration and login with client-side validation.
Role-based access (user/admin).


Feedback Submission:
Logged-in users can submit feedback (max 500 characters).
Client-side and server-side validation for input.


Admin Panel:
View and filter feedback by username, date, or status (pending/reviewed).
Update feedback status to "reviewed" with a single click.


Security:
Passwords hashed with BCRYPT.
PDO prepared statements to prevent SQL injection.
HTML escaping to prevent XSS.


Responsive Design:
Styled with Tailwind CSS (via CDN) for a mobile-friendly UI.



Project Structure
├── config.php          # Database connection
├── auth.php           # Authentication functions
├── index.php          # Login page
├── register.php       # Registration page
├── dashboard.php      # User dashboard
├── feedback.php       # Feedback submission form
├── admin.php          # Admin panel for feedback management
├── update_status.php  # Endpoint for updating feedback status
├── logout.php         # Logout script

Prerequisites

Web Server: Apache or similar (e.g., XAMPP, WAMP).
PHP: Version 7.4 or higher with PDO extension.
MySQL: Version 5.7 or higher.
Browser: Modern browser for Tailwind CSS compatibility.

Setup Instructions

Clone or Download:
Copy the project files to your web server’s root directory (e.g., htdocs for XAMPP).


Database Setup:
Create a MySQL database named feedback_system.
Run the following SQL to create tables:CREATE DATABASE feedback_system;
USE feedback_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    feedback TEXT NOT NULL,
    status ENUM('pending', 'reviewed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);




Configure Database:
Open config.php and update the database credentials:define('DB_USER', 'your_username'); // e.g., 'root'
define('DB_PASS', 'your_password'); // e.g., ''




Create an Admin User:
Register a user via http://localhost/register.php.
Set their role to admin in the users table:UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';


Alternatively, insert an admin user directly:INSERT INTO users (username, email, password, role)
VALUES ('admin_user', 'admin@example.com', '$2y$10$YOUR_HASHED_PASSWORD', 'admin');

Generate a hashed password using:<?php echo password_hash('yourpassword', PASSWORD_BCRYPT); ?>




Start the Web Server:
Ensure your web server and MySQL are running.
Access the application at http://localhost/index.php.



Usage

User Actions:
Register at register.php or log in at index.php.
From the dashboard (dashboard.php), submit feedback via feedback.php.
Log out using the “Logout” button.


Admin Actions:
Log in as an admin user.
Access the admin panel from the dashboard (admin.php).
Filter feedback by username, date, or status.
Click “Mark as Reviewed” to update feedback status.


Feedback Management:
View all feedback in the admin panel.
Use filters to focus on specific feedback.
Manually act on feedback (e.g., contact users or implement suggestions).



Security Notes

Development Use: This system is designed for local/development environments. For production, add:
HTTPS for secure sessions.
CSRF tokens for form submissions.
Stronger password policies.


Session Management: Sessions are server-side and secure with proper configuration.
Input Validation: Client-side and server-side validation prevent invalid submissions.

Potential Enhancements

Add pagination to the admin panel for large feedback volumes.
Implement feedback deletion or reply functionality.
Enable email notifications for feedback submissions.
Add a user feedback history view on the dashboard.

Troubleshooting

Login Issues: Verify email/password and check users table for correct data.
Admin Panel Access: Ensure the user’s role is admin in the users table.
Database Errors: Confirm config.php credentials and database schema.
Status Not Updating: Check that update_status.php is accessible and the feedback table has a status column.

License
This project is for educational purposes and not licensed for production use.
