# E-Learning CMS Platform

## Overview
This is a custom-built Content Management System (CMS) based E-learning platform developed using PHP and MySQL. The system supports both administrative functionalities (for managing instructors, courses, and lessons) and a modern front-end interface (for users to browse and enroll in courses).

---

## 👩‍💻 Admin Features
- Secure login and session-based dashboard
- Manage **Courses**: Create, edit, delete courses with titles, descriptions, and image uploads
- Manage **Instructors**: Create, edit, delete instructors with profile photos and bios
- Manage **Lessons**: Linked to each course, each lesson includes a title, video URL, content, duration, and sort order
- Upload/delete images directly into a centralized `images/` folder
- View stats like course count, instructor count, lesson count

---

## 🌐 Front-End Features
- Browse all available courses
- View detailed course and instructor pages
- Watch video lessons
- Enroll in courses (with login in future)
- Responsive layout with a modern UI

---

## 🗃️ Database Structure
### Tables
- `courses`: Holds all course information
- `instructors`: Instructor details
- `lessons`: Lessons linked to a course
- `users`: Holds admin information (user information in future with Role)

---

## 🛠️ Tech Stack
### Frontend:
- HTML5  
- CSS3  
- Bootstrap 5  
- Font Awesome  

### Backend:
- PHP 7+  
- MySQL  

---

# 🚀 Project Setup Instructions

1. **Clone the Repository**:  
```
git clone https://github.com/dignapatel0/E-Learning-Platform.git
```

2. **Move Files to Server Directory**:  
Copy the cloned files into your server's root directory (e.g., `htdocs` for XAMPP).

3. **Create the Database**:
- Open phpMyAdmin or your preferred database tool.
- Create a new database (e.g., `e_learning`).
- Import the SQL file (`e_learning.sql`) provided in the database folder to set up tables.

4. **Configure Database Connection**:
- Create `.env`.
- Add your database credentials:
  ```
  $db_host = 'localhost';
  $db_user = 'your username';
  $db_pass = 'your password';
  $db_name = 'e_learning';
  ```

5. **Run the Project**:
- Start your server (e.g., XAMPP).
- Access the project via `http://localhost/E-Learning-Platform`.

6. **Admin Login Credentials**:
- Username: `digna@example.com`
- Password: `password`

---