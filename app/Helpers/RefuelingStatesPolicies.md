```php
<?php
namespace App\Models\States\RefuelingOrder;

use Spatie\ModelStates\State;
use Spatie\ModelStates\Transition;

class RefuelingOrderState extends State
{
    public const DRAFT = 'draft';
    public const PENDING_APPROVAL = 'pending_approval';
    public const APPROVED = 'approved';
    public const PRINTED = 'printed';
    public const CANCELED = 'canceled';

    public static function config(): array
    {
        return [
            self::DRAFT => [
                self::PENDING_APPROVAL,
                self::CANCELED
            ],
            self::PENDING_APPROVAL => [
                self::APPROVED,
                self::DRAFT,
                self::CANCELED
            ],
            self::APPROVED => [
                self::PRINTED,
                self::DRAFT
            ],
            self::PRINTED => [
                self::DRAFT
            ]
        ];
    }
}
```

2. Refueling Order Policy

```php
<antArtifact identifier="refueling-order-policy" type="application/vnd.ant.code" language="php" title="Refueling Order Policy">
<?php
namespace App\Policies;

use App\Models\User;
use App\Models\RefuelingOrder;
use App\Models\States\RefuelingOrder\RefuelingOrderState;

class RefuelingOrderPolicy
{
    public function create(User $user)
    {
        return $user->hasRole(['operator', 'secretary']);
    }

    public function update(User $user, RefuelingOrder $order)
    {
        // Only allow updates in draft or if manager resets printed state
        return ($order->state === RefuelingOrderState::DRAFT) ||
               ($user->hasRole('manager') && $order->state === RefuelingOrderState::PRINTED);
    }

    public function approve(User $user, RefuelingOrder $order)
    {
        return $user->hasRole('manager') &&
               $order->state === RefuelingOrderState::PENDING_APPROVAL;
    }

    public function print(User $user, RefuelingOrder $order)
    {
        return $user->hasRole('manager') &&
               $order->state === RefuelingOrderState::APPROVED;
    }
}
```

3. Notification Event

```php
<antArtifact identifier="refueling-order-event" type="application/vnd.ant.code" language="php" title="Refueling Order Creation Event">
<?php
namespace App\Events;

use App\Models\RefuelingOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefuelingOrderCreated
{
    use Dispatchable, SerializesModels;

    public $refuelingOrder;

    public function __construct(RefuelingOrder $refuelingOrder)
    {
        $this->refuelingOrder = $refuelingOrder;
    }
}

// Corresponding Listener
namespace App\Listeners;

use App\Events\RefuelingOrderCreated;
use Illuminate\Support\Facades\Notification;

class NotifyManagerOfNewRefuelingOrder
{
    public function handle(RefuelingOrderCreated $event)
    {
        // Find managers and send notification
        $managers = User::role('manager')->get();
        Notification::send($managers, new NewRefuelingOrderNotification($event->refuelingOrder));
    }
}
```

4. Filament Resource Configuration

```php
<antArtifact identifier="refueling-order-resource" type="application/vnd.ant.code" language="php" title="Refueling Order Filament Resource">
<?php
namespace App\Filament\Resources;

use App\Models\RefuelingOrder;
use App\Models\States\RefuelingOrder\RefuelingOrderState;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;

class RefuelingOrderResource extends Resource
{
    protected static ?string $model = RefuelingOrder::class;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            // Only allow state changes based on user role and current state
            Forms\Components\Select::make('state')
                ->options(RefuelingOrderState::getOptions())
                ->disabled(fn($record) => !auth()->user()->hasRole('manager')),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('state')
                    ->badge()
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->visible(fn($record) =>
                        auth()->user()->hasRole('manager') &&
                        $record->state === RefuelingOrderState::PENDING_APPROVAL
                    ),
                Tables\Actions\Action::make('print')
                    ->visible(fn($record) =>
                        auth()->user()->hasRole('manager') &&
                        $record->state === RefuelingOrderState::APPROVED
                    )
            ]);
    }
}
```

Key Implementation Details:

-   State management with strict transition rules
-   Role-based access control
-   Event for notifying managers on creation
-   Filament resource with role-specific actions
-   Prevents editing after printing without manager intervention

Recommended Setup:

-   Register policy in `AuthServiceProvider`
-   Configure event listener in `EventServiceProvider`
-   Create migration for state column

Would you like me to elaborate on any specific aspect?
