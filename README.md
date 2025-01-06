# COMP 353 Project - Youth Soccer Club

## Setup & Installation

### SQLTools
To connect to MySQL and execute queries directly from VSCODE, you can use this extension:



**NOTE:** The XAMPP connection is already configured for our workspace, but here are the instructions just in case:

To configure the connection, press CTRL+SHIFT+P to open the command pallet: `SQLTools: Connect`, then press the `+` icon to create a new connection.

Open the admin screen for MySQL from XAMPP. On the left, press the `New` button to create a new database called "warmup". Return to VSCode and enter the connection information. Username is "root", for password use the drop down to select "Empty Password", and for database enter "warmup".

You can now execute SQL directly from VSCode. To try it, open one of the `migrations/0_create_persons.sql` file and press CTRL+SHIFT+P, then select `SQLTools Connection: Run this File`

### Plant-UML VSCODE Extension
We are using this extension to render diagrams.


Install it, and follow the instructions for setting up PlantUML on your OS. Once installed, make sure to open the VSCode extension settings to set the path to the JAR.

## E/R Diagram
Below is the E/R which describes the domain of the application.

![er diagram](COMP353-Project/docs/diagrams/er_diagram.drawio.png)

## Changes Made During Development

### PHP Code for Query Handling
We have implemented PHP code to handle queries dynamically by reading SQL queries from files and displaying the results. The queries are mapped to files in the `queries` directory, and results are displayed in a professional manner with options to modify records in place.

### Modifying Records
- Added functionality to modify records directly from the displayed table.
- Added JavaScript to handle in-place modifications, making the interface more user-friendly.
- Ensured that modifications are reflected in the database by dynamically generating SQL queries based on modified attributes.

### CSS Improvements
- Improved CSS for a professional look and feel.
- Ensured that the table layout remains consistent and centered even when modification fields are displayed.
- Added styles for form fields to enhance the user experience.

### Error Handling
- Improved error handling in PHP to provide meaningful messages and prevent silent failures.
- Added checks to ensure the database connection is established before performing operations.

### Views and Triggers
- Implemented views to simplify complex queries and ensure data consistency.
- Added triggers to handle updates and ensure integrity constraints are maintained in the database.

### Database Connection
- Centralized database connection handling to ensure it is available across different scripts.
- Improved connection setup to handle different environments and configurations.

### Code Clean-up
- Refactored code for better readability and maintainability.
- Ensured that all scripts follow a consistent coding style and best practices.

## Hosting Information
The project is hosted on the `encs.concordia.ca` server, providing a reliable and accessible platform for the application's deployment and testing.

By following these steps and implementing the changes, the project is now more robust, user-friendly, and easier to maintain.
