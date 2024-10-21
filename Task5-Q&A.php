Task 5: Q&A
-Answer the following questions

A) Explain this code:
Schedule::command('app:example-command')
    ->withoutOverlapping()
    ->hourly()
    ->onOneServer()
    ->runInBackground();
 
- Schedule::command('app:example-command'): This is use for scheduled command.
- withoutOverlapping(): This is use for  prevents overlapping executions if the previous run is still in progress.
- hourly(): This is use for runs the command every hour.
- onOneServer(): This is use for ensures the command runs only on one server in a multi-server setup.
- runInBackground(): This is use for executes the command in the background, allowing other tasks to proceed.  

B) What is the difference between the Context and Cache Facades? Provide examples to illustrate your explanation.

Context Facade:
This is use to Controls dependency injection and scopes for classes/interfaces.

Cache Facade:
This is use to Stores, retrieves, deletes cached items and improves performance; 

C) What's the difference between $query->update(), $model->update(), and $model->updateQuietly() in Laravel, and when would you use each?

$query->update():
- This is use for Updates records directly in the database without retrieving models.
- This is use for for bulk updates without needing model instances.

$model->update():
- This is use to Updates the model’s attributes and saves it.
- This is use to perform additional logic before/after the update.

$model->updateQuietly():
- This is use to Updates the model’s attributes without triggering events.
- This is use to updating a model without needing event hooks.

