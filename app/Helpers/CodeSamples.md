### Creating and attaching a custom attribute inside a model

```php
    protected function formattedLength(): Attribute
    {
        return Attribute::make(
            get: function ($value, array $attributes) {
                if (! isset($this->episodes_sum_length_in_minutes)) {
                    $this->loadSum('episodes', 'length_in_minutes');
                }
                $totalMinutes = $this->episodes_sum_length_in_minutes;
                $hours = floor($totalMinutes/60);
                $hoursString = $hours > 0 ? $hours . ' ' . Str::plural('hr', $hours) . ' ' : '';
                $reminderMinutes = $totalMinutes % 60;
                $minutesString = $reminderMinutes . ' ' . Str::plural('min', $reminderMinutes);

                return $hoursString . $minutesString;
            }
        );
    }
```    

### Injecting hmtl components into Filament Panel or whatever using Rendering Hooks

Here is an example of how to add a footer to the panel. Visit Filament Docs to find all the possibilities for injections practically everywhere. For example on the login form inject some social media login alternatives.

* https://www.youtube.com/watch?v=Lji5oCPCBYg

### Database Notifications

Database notifications are basically notifications that are queued to be sent to any set of users when a particular db event happens such as a record created event. A model needs the directive that it is observedby an observer, the observer then decides who to notify and the notifications are saved into a notifications-table. Assuming that we have either a cron job or a queue-walking artisan, the notifications will be sent to the users and appear in a sidebar (unlike notifications that show and expire after a few seconds).

* https://www.youtube.com/watch?v=PMUp1B50jcA

```php
> php artisan make:notifications-table
> php artisan migrate
> php artisan make:observer MyModelClassObserver --model=MyModelClass
// Then go to the model and add before the class definition
#[ObservedBy([UserObserver::class])]

``` 

The add the ->databaseNotifications() to the Filaments provider inside the function Panel().

Now come back to the created of the observer
```php
$recipient = Auth::user();
Notification::make()->title('title')->body('soms')->sendToDatabase($recipient)
```

```php
> php artisan queue:work
```

### Summaries of lists/tables 

For example have a subtotals and totals..

* Table Builder Summaries with Grouping https://www.youtube.com/live/qPvebJEe9QE

