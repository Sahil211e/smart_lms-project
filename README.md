# Smart LMS - E-Learning Management System

An advanced E-Learning Management System developed using PHP and MySQL that provides a complete platform for online learning with student and admin functionalities.

## Project Overview

Smart LMS is a web-based learning management system designed to make online education easier. It allows students to access courses, manage their learning activities, and provides administrators with tools to manage users, courses, and content.

## Features

### Admin Panel
- Admin login system
- Manage students
- Add, update, and delete courses
- Manage learning content
- Monitor system activities

### Student Module
- Student registration and login
- View available courses
- Access learning materials
- Manage profile
- Track learning progress

## Technologies Used

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- Bootstrap

## Installation & Setup

1. Download or clone this repository

2. Move the project folder into XAMPP:

3. 
3. Start Apache and MySQL from XAMPP

4. Import the database file into phpMyAdmin

5. Configure database connection in PHP files

6. Open in browser:

http://localhost/smart_lms-project


## Project Structure

Smart-LMS/
│
├── admin/ # Admin panel files
│ ├── dashboard.php
│ ├── manage_users.php
│ ├── manage_courses.php
│ └── manage_content.php
│
├── student/ # Student module
│ ├── dashboard.php
│ ├── courses.php
│ ├── profile.php
│ └── progress.php
│
├── instructor/ # Instructor module (if available)
│ ├── dashboard.php
│ ├── add_course.php
│ └── upload_material.php
│
├── assets/ # Frontend resources
│ ├── css/
│ ├── js/
│ ├── images/
│ └── uploads/
│
├── database/ # Database files
│ └── smart_lms.sql
│
├── config/ # Configuration files
│ └── db_connection.php
│
├── includes/ # Common reusable files
│ ├── header.php
│ ├── footer.php
│ └── navbar.php
│
├── authentication/ # Login & registration
│ ├── login.php
│ ├── register.php
│ └── logout.php
│
├── courses/ # Course related pages
│ ├── course_details.php
│ └── enroll.php
│
├── index.php # Home page
├── README.md # Project documentation
└── .gitignore # Ignored files


## Purpose

This project was developed to provide an online learning environment where students and administrators can easily manage educational activities.

## Author

Sahil21le






