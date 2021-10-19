# The COSC 360 Project

**Due Dates:** See Milestone Dates

# Overview:
The project is designed to help develop your skills for full stack development.  With this project, you will build an online application that will allow users to register, create and participate in different features of your site. **The project is a group project of 2 to 3 people**.   Please note that the grading rubric for different aspects of the project.  

# Hardware and Software: 

You will develop the project using Linux, MySQL, Apache and PHP on cosc360.ok.ubc.ca,in addition to CSS, HTML5 and JavaScript on the client-side.  **The backend must be completed with PHP**.  Your project will be stored in the class provided repository and will deployed to the cosc360 server.  The project must be accessible on the UBC network and all source code must be available for review.  You are required to keep your code under version control with GIT.

# MyDiscussionForum Website:

The **MyDiscussionForum** website will allow registered users to engage in online discussions and unregistered users to view discussions similar to forums such as Reddit (https://www.reddit.com/) and Hacker News (v).  The goal is to produce a similar type services that allows users to register, post stories and make comments on items.   Additionally, unregistered users must be able to view the content but will not be able to edit or comment on posts. 

A first goal is to create the layout for the site.  The layout is to be a 2 or 3-column layout with navigation links along the top.  **No styling frameworks are permitted other than Bootstrap as you are to develop the styling for the page using CSS (you can use flexgrid as this is a component of CSS)**.  The page requires a masthead as well as a footer.  The navigation links need to be available regardless of the where a user is viewing the page.   

Pages will need to be created for user registration that allows for the entry of user information along with user image.  The form will need to be validated before being submitted using the appropriate technology.

**Project Statement:** To build a web-based discussion forum that allows users engage in discussion on different posted threads.  Registered users should be able to track the activity of comments on different threads, post comments, create new threads and browse current threads. The system must also support internal use by administrators who can track discussions and moderate discussions, resolve user problems (such as forgotten passwords), and generate usage reports.

**Project Objectives:** The objectives are divided into two categories. The first category is the minimal requirements for the project to get a passing grade (C). The other requirements are some of the additional options that can be added to create an improved project. These objectives are further divided based on the implementation of different functional components utilizing different technologies.

# Baseline Objectives:

**Website user’s objectives:**
* Browse discussions without registering
* Search for posts by keyword without registering
* Search for items/posts by keyword without registering
* Register at the site by providing their name, e-mail and image
* Allow user login by providing user id and password
* Create and comment on discussions when logged into the site
* Users are required to be able to view/edit their profile
* User password recovery (via email) 

**Website administrator’s objectives:**
* Search for user by name, email or post
* Enable/disable users
* Edit/remove items and comments
 
 As this project is about demonstrating and applying different web technologies, you will utilize different technologies in the construction of the site.   These

# Minimum Functional Requirements:

* Hand-styled layout with contextual menus (i.e. when user has logged on to site, menus reflect change). Layout frameworks are not permitted other than Bootstrap (see above).
2 or 3 column layout using appropriate design principles (i.e. highlighting nav links when hovered over, etc) responsive design
* Form validation with JavaScript
* Server-side scripting with PHP
* Data storage in MySQL
* Appropriate security for data
* Site must maintain state (user state being logged on, etc)
* Responsive design philosophy (minimum requirements for different non-mobile display sizes)
* AJAX (or similar) utilization for asynchronous updates (meaning that if a discussion thread is updated, another user who is viewing the same thread will not have to refresh the page to see the update)
* User images (thumbnail) and profile stored in database
* Simple discussion (topics) grouping and display
* Navigation breadcrumb strategy (i.e. user can determine where they are in threads)
* Error handling (bad navigation)

# Additional Requirements:

* Search and analysis for items
* Hot threads/hot item tracking
* Visual display of updates, etc (site usage charts, etc)
* Activity by date
* Tracking (including utilizing tracking API or your own with visualization tools)
* Collapsible items/treads without page reloading
* Alerts on page changes
* Admin view reports on usage (with filtering)
* Styling flourishes
* Responsive layout for mobile
* Tracking comment history from a user’s perspective
* Accessibility
* Your choice (this is your opportunity to add additional flourish’s to your site but will need to be documented in the final report)

# Additional Allowable Technologies:

In addition to the core CSS3, PHP, HTML5 and JavaScript technologies, Bootstrap and JQuery are permitted to be used. 

# Deliverables:

This project should demonstrate your knowledge in full stack web design and programming. Your final submission will be submitted both electronically in the provided repository in addition to the site being available on cosc360.ok.ubc.ca. All your code and related files will be stored into the class project repository.

# Milestones:

**I. Monday, November 1st, 2021**

1. Project Proposal - 5%
 * Team member selections - list 
 * Project description and details:
 * Provide a description of the project you are going to undertake
 * Requirements list of what (at a minimum) your site will do (you will need to explore existing sites to understand their functional offerings). You should be able to understand from reading this document exactly what a user/administrator will be able to do on the site.  You will receive feedback on this so that you can direct the efforts of your project accordingly.
 * This will form a list of minimum end deliverables on the project and should be a comprehensive document (but you can expand on it).
 * You can submit this as a readme type document in your project repo.
 * Early review is encouraged for feedback.

**II. Sunday, November 14th, 2021**

2. Client Side Experience/Report (25%) - This is the frontend layout of your site
 * Layout document (Planned layout of your page in hardcopy/electronic copy showing elements, sizes, placement – this is the plan for what your site will look like and would recommend doing this BEFORE you actually build your site)
 * Organization of pages (How are pages linked? – site map)
 * Logic process (How does a user engage with site?): This needs to include all processes for how the user/admin will engage site.
 * Discussion regarding the design and styles of all pages
 * Client-side validation
 * Client-side security
 * Static design and styles of pages
 * Examples of each page type in your proposed site

**III. Tuesday, December 7th, 2021**

3. Deployment and Final Documentation (70%)
 * Pages styled as per your design
 * Pages live on server
 * Client-side validation
 * Client-side security
 * Pages live on server
 * Server-side implementation complete
 * Posted on cosc360.ok.ubc.ca
 * Server-side security
 * User account information stored in database
 * Items and associated details stored in database
 * Chats/threads maintained in database
 * Asynchronous updates for comments (as one user types, it updates without reloading)
 * Database functionality complete
 * Core functional components operational (see baseline objectives)
 * A summary document, indicating implemented functionality and features (see below)
 * Final delivery of site with additional functionality 
 * In addition to your initial proposal and design for the site:
   * A 2-3 page walkthrough document that can be used to test the site by performing the walkthrough you describe.  It is to your advantage to include sufficient detail to highlight the best features of your website. This should also include things like required login ids and passwords, how to test your site as well as identifying any unique features.  This document will be used as a guide to test what you did. This document should be written as a user guide. 
   * A 2-3 page detailed description of your implementation from a system or developer's perspective including: What features did you implement? Include a description of the PHP and JavaScript files of your web site. How does your web site work at a high-level? Identify known limitations of the site? 
 * 10 marks is reserved for deployment, version control, client and server side unit testing (if you do not test or deploy the maximum you can get out of this section is 60/70).  It is expected the team members will all contribute to the project (ie everyone is committing to the repo with work) AND that commits/efforts on the project will take place over the next several months.   Additionally, you (the team) will need to think about and investigate how to test different aspects of your site. 

**Comments:**

You are welcome to submit before the deadlines.  The milestones are in place to indicate what will be evaluated at these points (ie. Proposal, Layout, client-side validation, and security will be evaluated against the basic functional requirements) but you can improve with additional functionality which will be evaluated at final submission.

This project is not intended to be a complex project (in terms of the content and number of pages) but is intended to provide the opportunity to develop and showcase your full-stack skills.  In the development of the project, focus on key functional objectives and work through them in a planned fashion.  A simple, functional and well organized side is acceptable as long as it contains the required functionality utilizing the appropriate technology as well as features from the 'additional list'.   Please review the functional requirements to ensure your design satisfies the requirements.

