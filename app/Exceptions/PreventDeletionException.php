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
