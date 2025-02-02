# Event Management System

## Overview
This is a web-based Event Management System built with PHP, Bootstrap, and MySQL. The system allows users to create, manage, and register for events. Admin users have full control over event management, user administration, and attendance requests. The project is built using PHP 8.2 and is designed to run locally with XAMPP or a similar local server setup.

## Features

### User Features:
- **User Authentication**: Register, login, and manage user sessions.
- **Event Management**: Post, edit, and delete personal events.
- **View Event Attendance**: View the number of attendees or requests for their events.
- **CSV Export**: Download a list of attendees or requests in CSV format for each event.
- **Dashboard**: Manage all events and actions (post, edit, delete) from the user dashboard.
- **Responsive Design**: The interface is mobile-friendly using Bootstrap.

### Admin Features:
- **Full Event Control**: Admin can post, edit, and delete any events.
- **User Management**: View and manage the user list.
- **Attendance Management**: View and approve/reject requests to attend events.
- **Admin Dashboard**: Admin has access to all system functionalities and event analytics.

### Event Capacity Management:
- **Capacity Limit**: Each event has a set maximum capacity. Once the event reaches this capacity, no further registration requests can be accepted.
  
### Security:
- **Secure Passwords**: User passwords are securely hashed and stored.
- **Prepared Statements**: All database queries are performed using prepared statements to protect against SQL injection attacks.

## Requirements:
- PHP 8.2 or higher.
- XAMPP (or similar local server setup).
- MySQL Database.

## Setup Instructions

1. **Clone the Repository**:
    ```bash
    git clone https://github.com/boniamin4444/event-management.git
    cd event-management
    ```

2. **Create the Database**:
    - Use XAMPP to create a new MySQL database named `event_management`.
    - Import the SQL tables from the `localhost:event_management/sql` folder.

3. **Configure the Environment**:
    - Update the `.env` file with your database credentials:
      ```env
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=event_management
      DB_USERNAME=root
      DB_PASSWORD=
      ```

4. **Run the Application**:
    - No artisan commands needed.
    - Start Apache and MySQL in XAMPP.
    - Access the application in your browser at `http://localhost`.

## Access the Application:

- **Admin Login**:  
  Email: `boniamin44444@gmail.com`  
  Password: `123456789`

- **User Login**:  
  Email: `rayhan@gmail.com`  
  Password: `123456789`

## Features in Progress:
- Event approval and management by both users and admins.
- Email notifications (planned for future versions).
- Improved reporting features for event analytics.

## Future Enhancements:
- Real-time notifications for new registrations or changes in event details.
- Integration with external calendar services like Google Calendar for event reminders.

## Contact:
For further questions, you can reach me at [Insert Contact Info].

