<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Event Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .about-container {
            padding: 40px 15px;
            background-color: #f4f4f4;
        }
        .about-header {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .about-sub-header {
            font-size: 1.8rem;
            color: #333;
            margin-top: 30px;
        }
        .about-content {
            font-size: 1.1rem;
            color: #555;
        }
        .about-content p {
            margin-bottom: 20px;
        }
        .about-button {
            margin-top: 30px;
        }
        .faq-section {
            background-color: #e9ecef;
            padding: 40px;
            margin-top: 40px;
            border-radius: 8px;
        }
        .faq-question {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }
        .faq-answer {
            font-size: 1rem;
            color: #555;
            margin-top: 10px;
        }
        .feature-list {
            list-style-type: none;
            padding: 0;
        }
        .feature-item {
            margin-bottom: 15px;
            font-size: 1.1rem;
            color: #444;
        }
    </style>
</head>
<body>

<div class="container about-container">
    <div class="text-center">
        <h1 class="about-header">Welcome to Our Event Management System</h1>
        <p class="about-content">
            Our Event Management System is a powerful platform designed to make event creation and management easier than ever. Whether you're an event organizer, a company, or an individual looking to host an event, our system provides all the tools you need to manage everything from start to finish.
        </p>

        <h2 class="about-sub-header">What is Our Event Management System?</h2>
        <p class="about-content">
            Our platform allows registered users to create, manage, and maintain events with ease. The system provides a seamless experience for both organizers and attendees. After registering on the platform, users can:
        </p>
        <ul class="feature-list">
            <li class="feature-item">Create events with detailed information including name, description, date, time, and location.</li>
            <li class="feature-item">Upload images to enhance event promotion.</li>
            <li class="feature-item">Set a capacity for the event and track how many people have registered.</li>
            <li class="feature-item">Edit or delete events whenever necessary.</li>
            <li class="feature-item">Monitor attendee registrations and manage them effectively.</li>
        </ul>

        <h2 class="about-sub-header">How to Get Started</h2>
        <p class="about-content">
            Follow these simple steps to start creating and managing your events:
        </p>
        <ol class="about-content">
            <li><strong>Register:</strong> Create an account on our platform. Once you're registered, you’ll be able to log in and start managing events.</li>
            <li><strong>Create an Event:</strong> Log in and go to the event creation page. Fill in the event details like name, description, date, and capacity.</li>
            <li><strong>Manage Your Event:</strong> After creating the event, you can update the event details, upload additional images, and track the number of attendees.</li>
            <li><strong>Monitor Registrations:</strong> The system provides real-time updates on the number of people who have registered for your event. If your event reaches full capacity, you can close registrations.</li>
            <li><strong>Promote Your Event:</strong> Share the event with friends, family, and potential attendees to attract more people to your event.</li>
        </ol>

        <h2 class="about-sub-header">Key Features</h2>
        <p class="about-content">
            Our Event Management System comes packed with a variety of features to help you organize and manage your events effortlessly:
        </p>
        <ul class="feature-list">
            <li class="feature-item"><strong>Customizable Event Pages:</strong> Each event has its own customizable page where you can display important details such as name, description, location, and images.</li>
            <li class="feature-item"><strong>Real-Time Attendee Count:</strong> Track how many people have signed up for your event and manage the attendee list.</li>
            <li class="feature-item"><strong>Event Photos:</strong> Upload and showcase images related to your event to make it more appealing to potential attendees.</li>
            <li class="feature-item"><strong>Easy Registration:</strong> Allow attendees to sign up with a simple and secure registration process.</li>
            <li class="feature-item"><strong>Event Management Dashboard:</strong> Access a user-friendly dashboard to manage and monitor all your events in one place.</li>
            <li class="feature-item"><strong>Secure User Accounts:</strong> Create and manage events only if you are logged in. Your account is protected with secure login and registration features.</li>
        </ul>

        <h2 class="about-sub-header">Why Use Our Event Management System?</h2>
        <p class="about-content">
            With the increasing demand for virtual and in-person events, organizing an event can be stressful. Our platform removes the complexities of event management by providing a centralized space for everything you need to manage your event effectively. Here’s why you should choose our platform:
        </p>
        <ul class="feature-list">
            <li class="feature-item"><strong>Save Time:</strong> No need to use multiple tools. Everything you need to manage your event is in one place.</li>
            <li class="feature-item"><strong>Gain Insights:</strong> Our platform provides detailed reports on the number of attendees and the registration process, helping you make better decisions.</li>
            <li class="feature-item"><strong>Increase Visibility:</strong> Publicize your event and reach more people with easy sharing options.</li>
            <li class="feature-item"><strong>Easy to Use:</strong> Even if you’re new to event management, our platform is designed with simplicity in mind.</li>
            <li class="feature-item"><strong>Secure and Reliable:</strong> We take security seriously. Your data is protected with the latest encryption standards.</li>
        </ul>

        <div class="about-button text-center">
        <a href="#" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#registerModal">Register Now and Start Creating Events! And then login for create a event</a>
            
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="faq-section">
        <h3 class="about-header text-center">Frequently Asked Questions</h3>
        <div class="faq-question">1. How do I create an event?</div>
        <div class="faq-answer">
            To create an event, simply log in to your account, go to the dashboard, and click on the "Create Event" button. Fill in the details such as event name, description, date, location, and upload any images. Once done, click "Submit" to create your event.
        </div>

        <div class="faq-question">2. Can I edit my event after creating it?</div>
        <div class="faq-answer">
            Yes, you can easily edit any event details after it’s created. Simply visit your event page, click on the "Edit" button, and modify the necessary information. You can change event name, description, location, date, and images.
        </div>

        <div class="faq-question">3. Can I delete an event?</div>
        <div class="faq-answer">
            Yes, you can delete an event. Just go to your event management dashboard, select the event you want to delete, and click the "Delete" button. Please note that this action is permanent.
        </div>

        <div class="faq-question">4. How can I track event registrations?</div>
        <div class="faq-answer">
            Your event dashboard will display the number of registered attendees in real-time. If your event has reached its capacity, the registration option will be closed automatically.
        </div>

        <div class="faq-question">5. What happens if I reach the event capacity?</div>
        <div class="faq-answer">
            If your event reaches the specified capacity, no more registrations will be accepted. You can set the event to "Full" and prevent further attendees from signing up.
        </div>
    </div>

</div>

<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Register</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="registerForm" method="POST" action="register.php">
                    <div class="mb-3">
                        <label for="registerName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="registerName" name="registerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerEmail" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="registerEmail" name="registerEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="registerPassword" name="registerPassword" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <button type="submit" class="btn btn-success">Register</button>
                </form>
                <div id="errorMessage" class="text-danger mt-2"></div>
                <div id="successMessage" class="text-success mt-2"></div>
            </div>
        </div>
    </div>
</div>


<?php include('footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
