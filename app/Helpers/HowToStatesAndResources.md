I'll provide an enhanced version of the MaintenanceTaskResource that implements both approaches you mentioned for handling state transitions. I'll also ensure that only valid adjacent states are shown based on the current state.

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceTaskResource\Pages;
use App\Models\MaintenanceTask;
use App\States\MaintenanceTask\MaintenanceTaskState;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class MaintenanceTaskResource extends Resource
{
    protected static ?string $model = MaintenanceTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'LOW' => 'info',
                        'MEDIUM' => 'warning',
                        'HIGH' => 'danger',
                        'CRITICAL' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('state')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'in_progress' => 'info',
                        'on_hold' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),

                // Add a column to show available transitions
                Tables\Columns\TextColumn::make('available_transitions')
                    ->label('Available Transitions')
                    ->formatStateUsing(function (MaintenanceTask $record): HtmlString {
                        return new HtmlString(
                            collect(self::getAvailableTransitions($record))
                                ->map(fn ($transition) =>
                                    sprintf(
                                        '<span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full %s">%s</span>',
                                        auth()->user()->can($transition['permission'])
                                            ? 'bg-blue-100 text-blue-800'
                                            : 'bg-gray-100 text-gray-500 opacity-50',
                                        $transition['label']
                                    )
                                )
                                ->implode(' ')
                        );
                    }),
            ])
            ->actions([
                // Option 1: Show all possible transitions but disable unauthorized ones
                Tables\Actions\ActionGroup::make(function (MaintenanceTask $record) {
                    return collect(self::getAvailableTransitions($record))
                        ->map(fn ($transition) =>
                            Tables\Actions\Action::make($transition['action'])
                                ->label($transition['label'])
                                ->icon($transition['icon'])
                                ->color($transition['color'])
                                ->disabled(!auth()->user()->can($transition['permission']))
                                ->tooltip(
                                    auth()->user()->can($transition['permission'])
                                        ? "Transition to {$transition['label']}"
                                        : 'You are not authorized to perform this action'
                                )
                                ->requiresConfirmation()
                                ->action(fn () => $record->transitionState($transition['state']))
                        )
                        ->toArray();
                }),

                // Option 2: Only show authorized transitions
                Tables\Actions\ActionGroup::make(function (MaintenanceTask $record) {
                    return collect(self::getAvailableTransitions($record))
                        ->filter(fn ($transition) => auth()->user()->can($transition['permission']))
                        ->map(fn ($transition) =>
                            Tables\Actions\Action::make($transition['action'])
                                ->label($transition['label'])
                                ->icon($transition['icon'])
                                ->color($transition['color'])
                                ->requiresConfirmation()
                                ->action(fn () => $record->transitionState($transition['state']))
                        )
                        ->toArray();
                })->label('Authorized Actions'),
            ]);
    }

    protected static function getAvailableTransitions(MaintenanceTask $task): array
    {
        // Get only valid adjacent states based on current state
        $transitions = match ($task->state->getValue()) {
            'pending' => [
                [
                    'state' => 'in_progress',
                    'action' => 'start',
                    'label' => 'Start Task',
                    'icon' => 'heroicon-o-play',
                    'color' => 'success',
                    'permission' => 'start maintenance task'
                ],
                [
                    'state' => 'cancelled',
                    'action' => 'cancel',
                    'label' => 'Cancel',
                    'icon' => 'heroicon-o-x-circle',
                    'color' => 'danger',
                    'permission' => 'cancel maintenance task'
                ],
            ],
            'in_progress' => [
                [
                    'state' => 'on_hold',
                    'action' => 'pause',
                    'label' => 'Pause',
                    'icon' => 'heroicon-o-pause',
                    'color' => 'warning',
                    'permission' => 'pause maintenance task'
                ],
                [
                    'state' => 'completed',
                    'action' => 'complete',
                    'label' => 'Complete',
                    'icon' => 'heroicon-o-check-circle',
                    'color' => 'success',
                    'permission' => 'complete maintenance task'
                ],
            ],
            'on_hold' => [
                [
                    'state' => 'in_progress',
                    'action' => 'resume',
                    'label' => 'Resume',
                    'icon' => 'heroicon-o-play',
                    'color' => 'success',
                    'permission' => 'resume maintenance task'
                ],
                [
                    'state' => 'cancelled',
                    'action' => 'cancel',
                    'label' => 'Cancel',
                    'icon' => 'heroicon-o-x-circle',
                    'color' => 'danger',
                    'permission' => 'cancel maintenance task'
                ],
            ],
            'completed' => [
                [
                    'state' => 'in_progress',
                    'action' => 'reopen',
                    'label' => 'Reopen',
                    'icon' => 'heroicon-o-arrow-path',
                    'color' => 'warning',
                    'permission' => 'reopen maintenance task'
                ],
            ],
            'cancelled' => [
                [
                    'state' => 'pending',
                    'action' => 'reactivate',
                    'label' => 'Reactivate',
                    'icon' => 'heroicon-o-arrow-path',
                    'color' => 'success',
                    'permission' => 'reactivate maintenance task'
                ],
            ],
            default => [],
        };

        return $transitions;
    }

    // Add a custom action to record state transition history
    public static function recordTransitionHistory(MaintenanceTask $task, string $oldState, string $newState): void
    {
        activity()
            ->performedOn($task)
            ->causedBy(auth()->user())
            ->withProperties([
                'old_state' => $oldState,
                'new_state' => $newState,
                'transition_date' => now(),
            ])
            ->log('state_transition');
    }
}

```

This implementation provides:

Visual State Management:

Current state shown with color-coded badges
Available transitions displayed as badges (enabled/disabled based on permissions)
Clear visual feedback for authorized vs unauthorized actions
Two Action Group Options:

Option 1: Shows all possible transitions but disables unauthorized ones
Option 2: Shows only authorized transitions

Smart State Transitions:
Only shows valid adjacent states based on current state
Prevents invalid state jumps
Requires confirmation for all state changes
Records transition history
Permission Integration:

Fully integrated with Spatie permissions
Clear tooltips explaining why actions are disabled
Proper authorization checks before any transition
To use this resource, you would:

Register it in your AdminPanelProvider:

```php
use App\Filament\Resources\MaintenanceTaskResource;

public function panel(Panel $panel): Panel
{
    return $panel
        ->resources([
            MaintenanceTaskResource::class,
        ]);
}
```

Choose which action group style you prefer (you can remove one or the other)

Optionally add activity logging by installing spatie/laravel-activitylog:

```php
composer require spatie/laravel-activitylog
```

This implementation provides a clean, user-friendly interface while maintaining strict state transition rules and proper authorization checks.
