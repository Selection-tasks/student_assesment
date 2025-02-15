Student Assessment System

Project Overview

This web application provides a two-stage student assessment system:

Information Gathering: A form collects student details and stores them in a database.

Skills Evaluation Quiz: A dynamically generated quiz assesses students' knowledge and records their scores.

Admin Dashboard: An interface for administrators to review student information and quiz results.

Technologies Used

Frontend: HTML, CSS, JavaScript

Backend: PHP

Database: MySQL

Installation & Setup Guide

1. Prerequisites

Ensure you have the following installed on your system:

XAMPP (for Apache and MySQL) or any PHP-supported web server

MySQL database

2. Setting Up the Database

Open phpMyAdmin or any MySQL management tool.

Create a new database named student_assessment.

Import the create_tables.sql file to create the necessary tables.

3. Configuring the Project

Place the project folder in the htdocs directory (if using XAMPP).

Update the database credentials in PHP files (admin_auth.php, fetch_students.php, etc.) if necessary.

4. Running the Project

Start Apache and MySQL services (if using XAMPP).

Open a browser and navigate to:

http://localhost/student-assessment/index.html for the student form.

http://localhost/student-assessment/admin_login.html for the admin panel.

5. Admin Credentials (Default)

Username: admin

Password: admin123 (hashed in database, change as needed)

6. Features

Student Registration & Quiz: Securely collects student data and evaluates them.

Admin Panel: Displays student details and quiz results.

Authentication: Secure login/logout system for administrators.

7. Security Considerations

Passwords are stored securely using hashing.

SQL queries use prepared statements to prevent SQL injection.

8. Future Enhancements

Add email notifications for quiz completion.

Implement a more advanced quiz question system.

Contact

For any issues or improvements, feel free to reach out!

Developed by Vikas H J

