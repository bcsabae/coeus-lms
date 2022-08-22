# Coeus Learning Management System

Coeus LMS is an e-learning platform fulfilling all needs a small-to-medium-sized business would need for its learning management system. Its main features are the presentation of courses, user and subscription management. It also includes a blog, and a full subscription and renewing payment handling subsystem. In more detail:

- courses with parts
- tracking the progress and logic for finished courses
- possibility for course dependance, i.e. one course can't be taken until an other is finished
- categorization, rating and filtering of courses
- user management with all general features (password reset, 2FA, remember me, etc.)
- notification system for end users and admin
- blog with posts

Aside from the learning system, a complex subscription and payment subsystem is included, with the name of **Repay**. This is discussed in detail a bit later.

This web-app was written for a project, which because of changed business needs unfortunately never went live. This also means that it is not complete and not fully tested. The emphasis was on the back-end, some basic front-end is included. On the bright side: it can be public for everyone who's interested! The code was cleaned and sensitive information was removed, so all previous commits and branches are merged into one to erase history.

**Disclaimer:** this web-app is not fully finished and may not fully operate as desired. I don't take any responsibility for the code, make sure to do your research and review the code before using it in production.


## Architecture:

This web application was developed using [Laravel 8](https://laravel.com/docs/8.x), a PHP framework based on Symfony components, following the MVC architecture. The application is a monolithic one, using server-side rendered Blade views and Livewire for more smooth user interaction.

![Coeus architecture](/doc/coeus_architecture.png)

### Courses and learning

The main component of the application are courses. Each course contains parts, completing a course means completing all parts of it. At the end of a course (that is, the last part), there is an option to have a quiz that needs to be passed in order to finish the course. Learning contents are in these parts, they can be viewed as lessons. Content can be text, video, pictures, or any HTML page.
Courses are ordered into categories. One course can have more categories. The lenght of the course and the number of subscriptions by users for that course are displayed, and can be used as filters when browsing courses. There is also a possibility to define dependencies for courses: one course cannot be taken until an other one is finished.

### Blog

There is a simple blog embedded to the webapp. Blog posts have a title and content, as well as categories.

### Controllers:
The following controllers are implemented, each handling different things. Location of the controllers is `app/Http/Controllers`.

- `Auth` - Authentication logic (register, login, forgot password, two-factor authentication, etc)
- `CoursesController` - courses CRUD, as well as subscription to a course
- `PaymentController` - controller for site payments. This is a wrapper for different payment services.
- `PostController` - blog post CRUD
- `ProfileController` - controller for account management.
- `SubscriptionsController` - this class is to handle subscriptions of an individual user

### Models and database:

The models use Laravel's built-in [Eloquent ORM](https://laravel.com/docs/8.x/eloquent) system, a very powerful Object-Relational Mapping system. Most of the models are for the app itself, but there are also some tables for basic infrastructure like session handling.

#### Models:

##### User management and infrastructure
`User` - user with email, password, 2FA, remember me functionality
Other tables without model object: `password_resets`, `failed_jobs`, `personal_access_tokens`, `sessions`

##### Learning Management System

- `Course` - course with title, slug, description, rating, lenght
- `Content` - part of a course with title, slug, content and place in course
- `FinishedContent` - storing finished contents, and thus tracking progress in a course
- `CourseDependency` - dependencies between courses, one dependency and one dependant
- `CourseTake` - storing taked courses, contains value to signal if the course is finished by the given user
- `AccessRight` - access rights for courses
- `Category` - category for both courses and blog posts
- `CourseCategory` - bridging many-to-many between courses and categories

##### Blog system
- `BlogPost` - blog post with user, title, slug and content
- `BlogPostCategory` - bridging many-to-many between blog posts and categories

##### Repay subscription and payment management system

- `Subscription` - stores a subscription with start, end and trial end dates, status and a limit when the subscription has to be renewed
- `SubscriptionType` - types of subscription
- `SubscriptionTypeToAccessRight` - bridging many-to-many between subscriptinos and access rights
- `Payment` - store payment with vendor, ID, price, subscription, status and payment intent for this payment
- `PaymentIntent` - initiated transaction for a subscription, following first, last and next attempt dates as well as remaining attempts
- `PaymentMethod` - payment method that can be used for third party payment services

![Database diagram](/doc/database.png)


## Event bus

The webapp uses Laravel's event bus for time-sensitive system components. The architecture model is event-listener based, with listeners triggering asynchronous jobs and notifications. Jobs are added to a queue, which is periodically worked to finish jobs.
The system impersonates a dummy 'Test Agend', and sends emails from addresses like 'garry@test.test'.

### Events

Events:
- `HasTakenCourse` - triggered when an user has taken a course.

Listeners:
- `EmailUserAboutCourseTake` - triggers job `EmailUserAboutCourseTake` and sends email to user. Listens for `HasTakenCourse`
- `RegistrationListener` - triggers job `NotifyAdminOfNewRegistration` and sends email to admin.

### Jobs
- `EmailUserAboutCourseTake` - sends an email to the user about the new course that was taken
- `NotifyAdminOfNewRegistration` - sends an email to the admin about the new registration


## Repay subscription management and payment system

Repay is a subsystem specifically made to be part of this LMS project, but developed with the idea in mind that this could someday be a separate PHP package. The configuration can be found in `config/repay.php`

### Architecture

A subscription has a type, which determines what content access level the subscription grants to its user. When subscribing, a trial is started. After trial has expired, the subscription enters its recurring state. Periodically the user is charged with the fee associated with the subscription type. After a determined time, the subscription needs to be renewed or cancelled by the user (this is needed because payment services usually operate with timeouts).

When a payment is made, a payment intent is created internally. This describes that the user wants to complete a transaction. The payment will be attempted through a payment service. If succeeded, the payment intent and thus the payment is finished. If failed, a retry mechanism is starting: the system will retry the transaction up until a given number of attempts. If all attempts fail, the payment intent and thus the payment has failed. Retries happen periodically.

Payment services are abstracted, the subsystem contains an interface that makes it easy make it compatible with any payment service. A dummy payment API is also added for testig.

![Repay architecture diagram](/doc/repay_architecture.png)

### Event bus

Repay handles time-sensitive operations. The sensitivity is handled with an event based architecture. When a payment fails, succeeds, or when a subscription renewal succeeds or fails, different events are triggered. These are used to notify the user about automatic operations, but also trigger automatic bookkeeping methods like deleting expired and abandoned subscriptions.

When the business logic determines that an action needs to be done, it dispatches a job to the worker queue. A periodic worker makes sure that these jobs are executed. These jobs also do the bookkeeping for subscription expiration, retrying failed payments, trial expiration, etc.

More about the event bus and how it works can be found directly in the code under `app/Repay/Events`, `app/Repay/Listeners`, `app/Repay/Jobs`, `app/Repay/Notifications`.

### Included classes

Repay provides a few traits that add compatibility to an existing project:
- `CanPay` - extends a general user model and adds logic to determine if a user is able to make payments
- `CanSubscribe` - extends a general user model and adds logic to handle subscriptions (make, delete, trial, etc)
- `HandlesSubscription` - subscription management module (for existing subscriptions - renew, end trial, etc)

The rest of the logic is implemented in the following classes:
- `PaymentHandler` - handles payments with configureable interface. Handles failed payments, retries, etc.
- `SubscriptionCreator` - singleton class to create subscriptions. Parameters like trial and billing period are configurable
- `PaymentApi` - interface to unify payment APIs' interaction with the app
- `SubscriptionHandler` - class to manage subscription related jobs
- `SubscriptionScheduler` - scheduler to periodically handle time-sensitive subscription operations
- `TestPaymentApi` - dummy test payment interface

## Contribution:
All contributions are welcome. At the moment, commits are disabled, so if you'd like to take part in developing this project, please let me know. Things that are still needed:
- /profile/billing misses implementation
- after subscribing to a course, page update needed to take effect
- admin user needs to be handled in a more meaningful way (right now it is indexex as user 0)
- deeper and more thoughtful feature tests

### How to run

Configure your server in `config/app.php` and `config/database.php`, as well as a local `.env` file. Then install dependencies and run the server with the following commands:

```
npm install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
./work/work.sh
```