# ☀️ SunnyDay - Task Manager Application

![Homepage](Homepage.png)

## 1. Website Overview

This project is a robust Task Management System built with **Laravel**. It allows users to organize their workflow by creating, updating, deleting and make category for tasks. The application ensures data security through user authentication and authorization, ensuring users can only manage their own tasks.

### Key Features

-   **User Authentication**: Secure registration and login (Laravel Auth).
-   **Task Manager (CRUD)**:
    -   Create tasks with Title, Description (optional), Status, Priority, and Due Date (optional).
    -   View a list of tasks with status indicators (Pending/Completed).
    -   Edit tasks details and update progress.
    -   Delete tasks.
-   **Categorization**: Each tasks can be assigned to a specific category (Ex: Work, School) or left uncategorized.
-   **Authorization**: Strict policy enforcement prevents users from accessing or modifying other user's data.
-   **Swappable View**: The tasks can be displayed with either list or grid views.

## 2. Technology Stack

### Backend

-   **Laravel**: Open-source web framework for building web application.
-   **PHP**: Open-source, server side, HTML embedded scripting language used to create dynamic web page.
-   **Scramble**: OpenAPI (Swagger) documentation generator for Laravel
-   **Pulse**: Free, open-source, first-party package that provides a dashboard for real-time performance monitoring and insights into your Laravel application's usage and bottlenecks.
-   **Sanctum**: A lightweight authentication system designed for Single Page Applications (SPAs), mobile applications, and simple token-based APIs.
-   **Slack Notification**: Provides support for sending notifications across a variety of delivery channels, including email, SMS (via Vonage, formerly known as Nexmo), and Slack.
-   **Telescope**: An elegant debug assistant for the Laravel framework that provides comprehensive, real-time insights into the operations of your application during local development.
-   **Tinker**: A powerful, interactive command-line tool known as a REPL (Read-Eval-Print Loop) that allows you to interact with your entire Laravel application directly from the terminal
-   **Sentry**: Provides comprehensive error and performance monitoring for Laravel applications

### Frontend

-   **Tailwind CSS/Vite**: Is the most seamless way to integrate it with frameworks like Laravel.
-   **Autoprefixer**: A PostCSS plugin that automatically adds necessary vendor prefixes and older display properties to your CSS, ensuring your modern code works across different browsers without you manually writing them.
-   **Axios**: A popular Promise-based HTTP client library for JavaScript.
-   **Concurrently**: An npm package used to run multiple npm scripts or general commands simultaneously.
-   **Laravel Vite plugin**: Providing a seamless integration between the Laravel backend and the Vite frontend build tool.
-   **PostCSS**: A powerful tool that transforms CSS styles using JavaScript plugins.
-   **Tailwind CSS**: A utility-first CSS framework packed with classes.
-   **Vite**: Vite is a blazing fast frontend build tool powering the next generation of web applications.

### Testing

-   **Framework**: PHPUnit (integrated with Laravel).
-   **Factories**: Faker library used to generate realistic test data.

### Database

-   **sqlite**: a C-language library that implements a small, fast, self-contained, high-reliability, full-featured, SQL database engine.
-   **Eloquent ORM**: Laravel's built-in tool that provides an elegant, Active Record implementation for interacting with a database using a simple, expressive PHP syntax instead of writing raw SQL queries.
-   **Database Indexing**: Optimized and faster query operations.

## 3. Database Schema

The application uses a relational database design with three core tables.
<mark>users</mark>

-   <mark>id</mark> (Primary Key)
-   <mark>name</mark>,<mark>email</mark>,<mark>password</mark>
-   <mark>timestamps</mark>

<mark>categories</mark>

-   <mark>id</mark> (Primary Key)
-   <mark>name</mark> (String)
-   <mark>user_id</mark> (Foregin Key -> users)
-   <mark>timestamps</mark>

<mark>tasks</mark>

-   <mark>id</mark> (Primary Key)
-   <mark>title</mark> (String)
-   <mark>description</mark> (Text, Nullable)
-   <mark>status</mark> (Enum: 'pending', 'completed')
-   <mark>priority</mark> (Enum: 'low', 'medium', 'high)
-   <mark>due_date</mark> (Date, Nullable, Cast to Carbon Instance)
-   <mark>user_id</mark> (Foregin Key -> users)
-   <mark>category_id</mark> (Foregin Key -> categories, Nullable)
-   <mark>timestamps</mark>

## 4. Testing Report

I have implemented a comprehensive automated testing for unit tests and feature tests to ensure reliability and security without failed.

**Unit Tests** (<mark>tests/Unit/</mark>)
Focused on Model logic and Database interactions.
![Unit test report](Unit_test.png)

**Feature Tests** (<mark>tests/Feature/</mark>)
Focused on User Actions, Controllers and Security.
![Feature test report](Feature_test.png)

**Total tests**: 29 passed ✅
**Duration**: 8.38s ⏳
