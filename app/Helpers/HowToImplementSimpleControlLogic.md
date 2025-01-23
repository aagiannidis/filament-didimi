### How to intercept invalid operations (like delete)

If you want to implement organisational rules, see an example of a simple department deletion check.
The department cannot be deleted if it is a parent department to some other one. I think the best way for more complex checks is via a service.

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
