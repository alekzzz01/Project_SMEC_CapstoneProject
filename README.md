AN MIS Website for Basic Education School
Overview
The AN MIS (Management Information System) website is designed to provide a comprehensive solution for managing various aspects of a basic education school. This web application will facilitate enrollment management, admission tracking, academic performance monitoring, and more, ensuring efficient administration and enhanced communication among all stakeholders.
Features
1. Enrollment Management
Functionality: Manage student assignments to classes, approve enrollment applications, and oversee the registration process by the registrar's office.
Benefits: Streamlines the enrollment process and ensures accurate class allocations.
2. Admission Management
Functionality: Online registration system that tracks and manages new student applications.
Benefits: Simplifies the admission process and provides real-time updates on application statuses.
3. Academic Performance Tracking
Functionality: Allows teachers to input grades and generate report cards for students.
Benefits: Provides a clear view of student performance over time, aiding in identifying areas for improvement.
4. Student Portal
Functionality: Centralized management of student-related data including personal details, grades, subjects, and schedules.
Benefits: Enables students to access their academic information easily, fostering engagement and responsibility.
5. Schedule and Course Allocation
Functionality: Organizes class schedules, assigns students to respective courses, and allocates teachers to subjects based on grade levels.
Benefits: Optimizes resource allocation and enhances the learning experience.
6. Report Generation
Functionality: Generates reports for enrollment numbers and academic performance metrics.
Benefits: Provides valuable insights for decision-making and strategic planning.
7. Role-based Access Control
Functionality: Implements access levels based on user roles such as administrators, teachers, and students.
Benefits: Ensures data security and appropriate access to sensitive information based on user responsibilities.
Technologies Used
This project leverages a variety of technologies to deliver its features effectively:
Frontend Technologies:
HTML
CSS
JavaScript
Libraries: jQuery, DaisyUI, TailwindCSS
Backend Technologies:
PHP
MySQL
Local Development Environment:
XAMPP (for running PHP and MySQL locally)
Installation Instructions
To set up the AN MIS website on your local machine:
Clone the repository:
bash
git clone https://github.com/yourusername/an-mis-website.git
cd an-mis-website

Set up XAMPP:
Download and install XAMPP.
Start the Apache and MySQL modules from the XAMPP control panel.
Create a database:
Open phpMyAdmin by navigating to http://localhost/phpmyadmin.
Create a new database (e.g., an_mis_db).
Import database schema:
Import the SQL file located in the database folder into your newly created database.
Configure database connection:
Open config.php in the root directory of your project.
Update with your database credentials.
Run the application:
Place the project folder in the htdocs directory of XAMPP (e.g., C:\xampp\htdocs\an-mis-website).
Access it via http://localhost/an-mis-website.
Usage
After setting up the application:
Register as a new user (Admin/Teacher/Student).
Log in to your account.
Navigate through sections like Enrollment Management, Admission Management, Academic Performance Tracking, etc.
Utilize the Student Portal for accessing personal academic data.
Contributing
Contributions are welcome! To contribute:
Fork the repository.
Create a new branch (git checkout -b feature/YourFeature).
Make changes and commit (git commit -m 'Add some feature').
Push to your branch (git push origin feature/YourFeature).
Open a pull request.
