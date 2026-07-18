# NSBT-STUDENT-INFORMATION-AND-RESULTS-PORTAL

## Project Overview

The Student Information and Results Portal is a front-end prototype developed as part of the System Analysis and Design course.

The system allows students to:

- Log in
- View personal information
- View academic results

It also provides interfaces for lecturers and administrators to manage student information and results.

## Features

- Responsive user interface
- Student Dashboard
- Lecturer Dashboard
- Admin Dashboard
- Student Profile
- Results Page
- Login Page

## Technologies Used

- HTML5
- CSS3
- JavaScript

## Project Structure

```
NSBT-STUDENT-INFORMATION-AND-RESULTS-PORTAL
├───app
│   ├───controllers
│   │       AuthController.php
│   │       LecturerController.php
│   │       StudentController.php
│   │       
│   ├───models
│   │       Course.php
│   │       Document.php
│   │       Lecturer.php
│   │       Result.php
│   │       Student.php
│   │       Timetable.php
│   │       User.php
│   │       
│   └───views
│       ├───admin
│       ├───auth
│       │       login.php
│       │       register.php
│       │       
│       ├───lecturer
│       │   │   dashboard.php
│       │   │   results.php
│       │   │   
│       │   └───partials
│       │           sidebar.php
│       │           
│       └───student
│           │   announcements.php
│           │   courses.php
│           │   dashboard.php
│           │   downloads.php
│           │   notifications.php
│           │   profile.php
│           │   results.php
│           │   timetable.php
│           │   
│           └───partials
│                   sidebar.php
│                   
├───assets
│   ├───css
│   │       style.css
│   │       
│   ├───downloads
│   │       Programming with Java book.pdf
│   │       
│   ├───images
│   └───js
├───config
│       app.php
│       database.php
│       
├───includes
│       footer.php
│       header.php
│       
├───public
│   │   login.php
│   │   logout.php
│   │   register.php
│   │   
│   ├───lecturer
│   │       dashboard.php
│   │       results.php
│   │       
│   └───student
│           announcements.php
│           courses.php
│           dashboard.php
│           downloads.php
│           notifications.php
│           profile.php
│           results.php
│           timetable.php
│           
└───tools
        create_lecturer.php

```

## Team Members

- KESTER SALLAH-QUAYE
- FRANKLIN TORNYIE
- PRINCESS AYITEY
- RICHLOVE NHYIRA MENSAH

## Course

System Design and Analysis

## Institution

Nduom School of Business & Technology

## Lecturer

Mr. Paul Offei
