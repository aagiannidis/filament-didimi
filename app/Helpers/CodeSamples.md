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

-   https://www.youtube.com/watch?v=Lji5oCPCBYg

### Database Notifications

Database notifications are basically notifications that are queued to be sent to any set of users when a particular db event happens such as a record created event. A model needs the directive that it is observedby an observer, the observer then decides who to notify and the notifications are saved into a notifications-table. Assuming that we have either a cron job or a queue-walking artisan, the notifications will be sent to the users and appear in a sidebar (unlike notifications that show and expire after a few seconds).

-   https://www.youtube.com/watch?v=PMUp1B50jcA

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

-   Table Builder Summaries with Grouping https://www.youtube.com/live/qPvebJEe9QE

### Downloading files from private folder

-   5:21 https://www.youtube.com/watch?v=xN-CF7dzeyM

### How to intercept invalid operations (like delete)

If you want to implement organisational rules, see an example of a simple department deletion check.
The department cannot be deleted if it is a parent department to some other one.

```php
//...in the model

  public static function boot()
    {
        parent::boot();

        static::deleting(function ($department) {
            if ($department->childDepartments()->exists()) {
                // either create a custom exception
                throw new PreventDeletionException('Backend:This department cannot be deleted because it has child departments.');
                // or simply return false to cancel
                return false;
            }
        });
    }
```

You can also intercept it in the frontend in the filament resource

```php
//...in the filament resource

    ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make()
            ->before(function (Tables\Actions\DeleteAction $action, $record) {
                if (!$record->canBeDeleted()) {
                    // send a front end notification to the user
                    Notification::make()
                        ->title('Action Denied')
                        ->body('Filament: This department cannot be deleted because it has child departments.')
                        ->danger()
                        ->send();
                    // cancel the action
                    $action->cancel();
                }
            }),
    ])

```

A custom exception here.

```php
<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Filament\Notifications\Notification;

class PreventDeletionException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void
    {

    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response
    {
        Notification::make()
        ->title('Action Denied')
        ->body($this->getMessage())
        ->danger()
        ->send();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
            ], 403);
        }

        return response()->view('errors.prevent-deletion', ['message' => $this->getMessage()], 403);
    }
}

```
