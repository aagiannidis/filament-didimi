To implement access restrictions for records in final states (like 'completed'), here's how we can modify the Laravel implementation:

First, let's enhance the Policy to check for final states:

```php
<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipmentPolicy
{
    use HandlesAuthorization;

    // Define final states as a constant
    private const FINAL_STATES = ['RETIRED', 'COMPLETED', 'CLOSED'];

    public function update(User $user, Equipment $equipment): bool
    {
        // First check if the record is in a final state
        if (in_array($equipment->status, self::FINAL_STATES)) {
            // Only admins can modify records in final states
            return $user->hasRole('admin');
        }

        // Normal authorization check for non-final states
        return $user->hasAnyRole(['admin', 'it_admin']);
    }

    // Add a specific method for state transitions
    public function updateStatus(User $user, Equipment $equipment, string $newStatus): bool
    {
        // If current status is final, only admin can change it
        if (in_array($equipment->status, self::FINAL_STATES)) {
            return $user->hasRole('admin');
        }

        // If transitioning to a final state, require higher permissions
        if (in_array($newStatus, self::FINAL_STATES)) {
            return $user->hasAnyRole(['admin', 'it_admin']);
        }

        // Normal state transitions can be done by regular authorized users
        return $user->hasAnyRole(['admin', 'it_admin', 'it_user']);
    }
}

```

In the Filament Resource, implement these restrictions:

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class EquipmentResource extends Resource
{
    // ... other resource configuration ...

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ... existing columns ...
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (Model $record) => auth()->user()->can('update', $record)),
                Tables\Actions\Action::make('changeStatus')
                    ->visible(function (Model $record) {
                        // Hide status change button for non-authorized users
                        return auth()->user()->can('updateStatus', [$record, 'COMPLETED']);
                    })
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options([
                                'ACTIVE' => 'Active',
                                'MAINTENANCE' => 'Maintenance',
                                'RETIRED' => 'Retired',
                            ])
                            ->required()
                    ])
                    ->action(function (Model $record, array $data): void {
                        // Check permission for the specific status transition
                        if (!auth()->user()->can('updateStatus', [$record, $data['status']])) {
                            throw new \Exception('Unauthorized status transition');
                        }
                        $record->update(['status' => $data['status']]);
                    }),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'MAINTENANCE' => 'Maintenance',
                        'RETIRED' => 'Retired',
                    ])
                    ->disabled(function (Model $record = null) {
                        if (!$record) return false;

                        // Disable status field if user can't update status
                        return !auth()->user()->can('updateStatus', [$record, $record->status]);
                    })
                    ->required(),
                // ... other form fields ...
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                !auth()->user()->hasRole('admin'),
                fn (Builder $query) => $query->where(function ($query) {
                    // For non-admins, hide records in final states unless they created them
                    $query->whereNotIn('status', ['COMPLETED', 'RETIRED', 'CLOSED'])
                        ->orWhere('created_by', auth()->id());
                })
            );
    }
}

```

Add a trait to handle final states in the model:

```php
<?php

namespace App\Traits;

trait HasFinalStates
{
    public function isFinalState(): bool
    {
        return in_array($this->status, static::FINAL_STATES);
    }

    public function canBeModified(): bool
    {
        return !$this->isFinalState() || auth()->user()->hasRole('admin');
    }

    protected static function bootHasFinalStates()
    {
        static::updating(function ($model) {
            if ($model->isFinalState() && !auth()->user()->hasRole('admin')) {
                throw new \Exception('This record cannot be modified as it is in a final state.');
            }
        });
    }
}
```

Use the trait in the Equipment model:

```php
<?php

namespace App\Models;

use App\Traits\HasFinalStates;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFinalStates;

    protected const FINAL_STATES = ['RETIRED', 'COMPLETED', 'CLOSED'];

    // ... rest of the model implementation ...
}

```

This implementation:

Defines clear final states in the model
Prevents modifications to records in final states except by admins
Provides granular control over state transitions
Automatically hides final state records from non-admin users
Disables form fields appropriately based on record state
Includes proper authorization checks at multiple levels
Provides a reusable trait for other models that need similar functionality
The system will now:

Prevent unauthorized users from modifying completed records
Hide edit buttons for unauthorized users
Disable form fields when appropriate
Enforce permissions at the database level
Allow admins to override restrictions when necessary
Provide clear feedback when actions are not allowed

```

```
