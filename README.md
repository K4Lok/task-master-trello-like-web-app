# About Task Master

Task Master is a Trello like task management web application built with vanilla HTML, CSS, JavaScript, and PHP. It allows the user to create new projects and different sections within each project. The most important feature of the application is the ability to create task cards that can be easily moved across different sections using drag and drop.

## Try it out!
> So far, I'm hosting the application on the free tier AWS which benefits from the GitHub student pack. Therefore, you got a chance to try it out!

Live website: http://taskmaster.samkalok.com

Public testing account:
- Account: tester@gmail.com
- Password: 123

## Installation

To run the Task Master application, you will need to have PHP installed. Here are the steps to set up the application:

1. Clone the repository to your local machine.
2. Import the `task_master.sql` file into your MySQL database.
3. Update the environment variable in `/backend/config.php` with your MySQL credentials.

## Run the Application

The html files can be accessed directly from the web server as long as the path is correct. For the backend, you can run the local server to test it out:

```bash
# To run the local server (for development)
php -S <domain_name:port>

# In our default config it should be like this
php -S localhost:5050
```

> Noted that the php built-in server is used for development only, it's not for production.

## Usage

After all the setup, you can access it by opening your web browser and navigating to the URL where you copied the contents of the Task Master folder.

To create a new project, click the "New" button in the Task Board page and enter a name and description for the project.

To add a new section to a project, click the "New" button within the project and enter the needed details for the section.

To create a new task card, click the "New Task" button within the new section form and enter the details for the task.

To move a task card across sections, simply drag and drop the card to a new section.

## Todo

- About Us page
- Tutorial Page
- Create page
- Visual feedback after CRUD operations from the client-side
- Check the task ownerships from backend before CRUD

## Contributing

If you would like to contribute to the Task Master project, please fork the repository and submit a pull request. All contributions are welcome!

## Credits

Task Master was built by [K4Lok](https://github.com/K4Lok).
