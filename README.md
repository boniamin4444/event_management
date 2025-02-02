Event Management System
This is an Event Management System built with PHP, Bootstrap, and MySQL. The project allows users to create, manage, and attend events. Admin users can manage event details, user lists, and handle requests for event attendance. The project runs on XAMPP with PHP 8.2.

Features
User Features:

Post new events.
Edit and delete their own events.
View the number of people attending or requesting to attend their events.
Download event request list in CSV format.
Manage all actions (posting, editing, deleting events) directly from the user dashboard.
Admin Features:

Admin can perform all user actions (post, edit, delete events).
Admin can maintain a user list.
Admin can view and approve/reject requests to attend events.
Event Capacity Management:

Each event has a defined capacity.
Once the capacity is reached and confirmed, no more requests can be sent.
Requirements


PHP 8.2 or higher.
XAMPP or a similar local server setup.
MySQL database.
Setup Instructions
Clone the Repository:

bash
Copy
Edit
git clone https://github.com/boniamin4444/event-management.git
cd event-management
Create the Database:

Use XAMPP to create a new database.
Name the database event_management.
Import the SQL tables from localhost:event_management/sql.
Set Up the Configuration:

Update the .env file for database configuration:
env
Copy
Edit
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_management
DB_USERNAME=root
DB_PASSWORD=
Run the Application:

No artisan commands are needed.
Simply run XAMPP, start Apache and MySQL, and access the application from your browser at http://localhost.
Access the Application:

Open your browser and go to http://localhost.
Admin Login
Email: boniamin44444@gmail.com
Password: 123456789

User Login:
Email: rayhan@gmail.com
Password: 123456789

CSV Download
Users can download the list of attendees or requests for each event in a CSV format.
Features in Progress
Event approval and management can do by Users & Admin.

