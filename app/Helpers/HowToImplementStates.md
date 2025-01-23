I'll help you implement the state machine for MaintenanceTask using Spatie's State package. Here's the complete implementation:

First, let's create the state classes:

```php
<?php

namespace App\States\MaintenanceTask;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class MaintenanceTaskState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, InProgress::class)
            ->allowTransition(Pending::class, Cancelled::class)
            ->allowTransition(InProgress::class, OnHold::class)
            ->allowTransition(InProgress::class, Completed::class)
            ->allowTransition(InProgress::class, Cancelled::class)
            ->allowTransition(OnHold::class, InProgress::class)
            ->allowTransition(OnHold::class, Cancelled::class)
            ->allowTransition(Completed::class, InProgress::class) // Allow reopening if needed
            ->allowTransition(Cancelled::class, Pending::class);   // Allow reactivation
    }
}
```

Create individual state classes:

```php
<?php

namespace App\States\MaintenanceTask;

class Pending extends MaintenanceTaskState
{
    public static $name = 'pending';
}

class InProgress extends MaintenanceTaskState
{
    public static $name = 'in_progress';
}

class OnHold extends MaintenanceTaskState
{
    public static $name = 'on_hold';
}

class Completed extends MaintenanceTaskState
{
    public static $name = 'completed';
}

class Cancelled extends MaintenanceTaskState
{
    public static $name = 'cancelled';
}

```

Create the MaintenanceTask model:

```php
<?php

namespace App\Models;

use App\States\MaintenanceTask\MaintenanceTaskState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStates\HasStates;

class MaintenanceTask extends Model
{
    use HasFactory, HasStates, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'fault_id',
        'schedule_id',
        'assigned_to',
        'priority',
        'start_date',
        'completion_date',
        'notes',
        'cost',
        'building_id',
    ];

    protected $casts = [
        'state' => MaintenanceTaskState::class,
        'start_date' => 'datetime',
        'completion_date' => 'datetime',
        'cost' => 'decimal:2',
    ];

    // Relationships
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function faultReport(): BelongsTo
    {
        return $this->belongsTo(FaultReport::class, 'fault_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSchedule::class, 'schedule_id');
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->whereState('state', Pending::class);
    }

    public function scopeInProgress($query)
    {
        return $query->whereState('state', InProgress::class);
    }

    public function scopeCompleted($query)
    {
        return $query->whereState('state', Completed::class);
    }

    // Helper methods
    public function canTransitionTo(string $state): bool
    {
        return $this->state->canTransitionTo($state);
    }
}
```

For the Filament Resource, create a custom form component for the state:

```php
<?php

namespace App\Filament\Forms\Components;

use App\States\MaintenanceTask\MaintenanceTaskState;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTaskStateSelect extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->options(function (Model $record = null) {
            if (!$record) {
                return MaintenanceTaskState::all();
            }

            return $record->state->transitionableStates()->mapWithKeys(function ($state) {
                return [$state => $state::$name];
            });
        });

        $this->reactive()
            ->afterStateUpdated(function ($state, $record) {
                if ($record && $state !== $record->state::$name) {
                    $record->state->transitionTo($state);
                }
            });
    }
}
```

Update your Filament Resource:

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\MaintenanceTaskStateSelect;
use App\Filament\Resources\MaintenanceTaskResource\Pages;
use App\Models\MaintenanceTask;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class MaintenanceTaskResource extends Resource
{
    protected static ?string $model = MaintenanceTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->required(),
                        MaintenanceTaskStateSelect::make('state')
                            ->required(),
                        Forms\Components\Select::make('priority')
                            ->options([
                                'LOW' => 'Low',
                                'MEDIUM' => 'Medium',
                                'HIGH' => 'High',
                                'CRITICAL' => 'Critical',
                            ])
                            ->required(),
                        Forms\Components\Select::make('assigned_to')
                            ->relationship('assignedTo', 'name')
                            ->searchable()
                            ->required(),
                        Forms\Components\DateTimePicker::make('start_date'),
                        Forms\Components\DateTimePicker::make('completion_date'),
                        Forms\Components\Textarea::make('notes'),
                        Forms\Components\TextInput::make('cost')
                            ->numeric()
                            ->prefix('$'),
                    ])
            ]);
    }

    // ... rest of the resource implementation
}
```

To integrate this:

Install required packages:

```php
composer require spatie/laravel-model-states
```

Run migrations:

```php
// In your migration file
Schema::create('maintenance_tasks', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description');
    $table->string('state'); // For the state machine
    $table->foreignId('fault_id')->nullable()->constrained()->onDelete('set null');
    $table->foreignId('schedule_id')->nullable()->constrained()->onDelete('set null');
    $table->foreignId('assigned_to')->constrained('users');
    $table->foreignId('building_id')->constrained();
    $table->string('priority');
    $table->timestamp('start_date')->nullable();
    $table->timestamp('completion_date')->nullable();
    $table->text('notes')->nullable();
    $table->decimal('cost', 10, 2)->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

This implementation provides:

-   Clear state transitions with validation
-   Integration with Filament forms
-   Proper state management
-   Type safety
-   Easy state querying

The states follow a logical progression:

PENDING (initial state)
IN_PROGRESS (when work begins)
ON_HOLD (if work is temporarily suspended)
COMPLETED (when work is done)
CANCELLED (if task is abandoned)

Each state transition is explicitly defined and can be controlled through permissions in your policies.

```

```
