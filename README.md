First, I would like to thank you for the good communication and also for this task ..

## let's talk about task

 ### To run task
1- git clone https://github.com/saedelbarshly/skyloov.git

2- cp .env.example

3- run migrate

4- run db:seed will add many tasks with for test

6- api collection will be in attached with mail also with repo

### feature

Validation Rules
- title: Required, string, max length of 255 characters.
- description: Optional, string.
- status: Must be one of the following: pending, in_progress, or completed.
- due_date: Required, must be a valid date in the future.
  
Query Parameters for Filtering
You can filter tasks by:
- status: Filters tasks by their status (pending, in_progress, completed).
- due_date: Filters tasks by a specific due date.
- title: Search tasks by title.
If no query parameters are provided, all tasks will be returned, paginated by default

Error Handling
Api Resource
Unit and Integration Tests

##
If there is any part that needs clarification, do not hesitate to call me, I will gladly do so.
Best Regards 😉
