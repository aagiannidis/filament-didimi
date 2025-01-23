**Short Answer:**  
Filament does not expose a traditional controller like Laravel’s resource controllers, so you cannot simply type-hint a service in a controller method and let route-model binding handle it. Instead, Filament uses Livewire components under the hood—particularly **resource pages** (e.g., `CreateRecord`, `EditRecord`, etc.)—which Filament automatically sets up with routes. The typical approach is to **inject your service** (and any other dependencies) inside those Livewire components or resource classes, and call your business logic from there.

Below is a more detailed explanation.

---

## 1. How Filament Differs from Traditional Controllers

In a normal Laravel setup, you might do:

```php
public function store(Request $request, MyService $service)
{
    // business logic ...
}
```

Laravel will automatically resolve and inject `MyService`. However, Filament **abstracts away** that controller layer via its own resource classes and Livewire components. So you don’t get direct control over the underlying controller method signatures.

---

## 2. Where to Put Your Business Logic in Filament

To maintain **single responsibility**:

1. **Create a Service Class** where your heavier business logic goes.
2. **Call that Service** from the relevant Filament resource page (e.g., the `EditRecord` Livewire component).

Filament typically gives you one “page” class per major action:

-   `Create{Model}` page (extends `CreateRecord`)
-   `Edit{Model}` page (extends `EditRecord`)
-   `List{Model}` page (extends `ListRecords`)
-   `View{Model}` page (extends `ViewRecord`)

_(Exact class names can vary depending on your Filament version.)_

These classes _are_ your entry points for CRUD operations in Filament. Each page is basically a specialized Livewire component.

---

## 3. Injecting a Service Into a Filament Page

### **A) Via the `mount()` Method**

Filament’s resource pages use Livewire’s lifecycle. You can override the `mount()` method and let **Laravel’s service container** inject dependencies:

```php
namespace App\Filament\Resources\PostResource\Pages;

use App\Services\PostService;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PostResource;
use App\Models\Post;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    public PostService $postService;

    // By default, Filament will pass the $record slug or ID into mount($record).
    // We can add other parameters for DI:
    public function mount($record, PostService $postService)
    {
        parent::mount($record); // This sets $this->record, etc.

        // Now we can store the service for later use
        $this->postService = $postService;
    }

    public function save(bool $shouldRedirect = true)
    {
        // Here you might delegate your business logic to the service.
        $this->postService->updatePost($this->record, $this->form->getState());

        // Or call the parent logic, then do something extra:
        return parent::save($shouldRedirect);
    }
}
```

**How does it work?**

-   Filament calls `EditPost@mount($record)` automatically when navigating to `/admin/posts/{record}/edit`.
-   Because this is still Laravel under the hood, the service container sees the `PostService $postService` argument and injects an instance for you.

### **B) Resolving from the Container Manually**

Instead of type-hinting, you could also resolve the service directly:

```php
public function mount($record)
{
    parent::mount($record);

    $this->postService = app(PostService::class);
}
```

But typically, constructor/method injection is cleaner and more testable.

---

## 4. Overriding Filament Resource Page Methods

Filament’s `CreateRecord`, `EditRecord`, etc. classes come with some built-in methods you can override:

-   `handleRecordCreation(array $data): Model`
-   `handleRecordUpdate(array $data): void`
-   `mutateFormDataBeforeSave(array $data): array`
-   `beforeFill()`, `afterFill()`, etc.

By overriding these, you can inject your **business logic** at exactly the right point in the CRUD lifecycle. For example, in an `EditRecord` page, you might do:

```php
protected function handleRecordUpdate(array $data): void
{
    // Use your service to perform the update logic
    $this->postService->updatePost($this->record, $data);
}
```

---

## 5. Where Does “Route-Model Binding” Happen?

Filament sets up routes for you behind the scenes (like `/{record}/edit`) and automatically resolves the model into `$record`. That’s how `parent::mount($record)` knows which model you’re editing. You do _not_ need to define those routes manually; Filament does it via the resource definitions.

---

## 6. Putting It All Together (Example)

1. **Your Service Class** – e.g. `App\Services\PostService`:

    ```php
    namespace App\Services;

    use App\Models\Post;

    class PostService
    {
        public function updatePost(Post $post, array $data): Post
        {
            // Complex business rules here...
            $post->update($data);

            // Possibly do something else, e.g. logging, notifications, etc.
            return $post;
        }
    }
    ```

2. **Your Filament Resource** – e.g. `App\Filament\Resources\PostResource`:

    ```php
    namespace App\Filament\Resources;

    use App\Filament\Resources\PostResource\Pages;
    use App\Models\Post;
    use Filament\Forms;
    use Filament\Resources\Form;
    use Filament\Resources\Resource;

    class PostResource extends Resource
    {
        protected static ?string $model = Post::class;

        public static function form(Form $form): Form
        {
            return $form
                ->schema([
                    Forms\Components\TextInput::make('title')->required(),
                    Forms\Components\Textarea::make('content')->required(),
                    // etc.
                ]);
        }

        public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
        {
            // ...
        }

        public static function getRelations(): array
        {
            return [];
        }

        public static function getPages(): array
        {
            return [
                'index' => Pages\ListPosts::route('/'),
                'create' => Pages\CreatePost::route('/create'),
                'edit' => Pages\EditPost::route('/{record}/edit'),
            ];
        }
    }
    ```

3. **Your Edit Page** – e.g. `App\Filament\Resources\PostResource\Pages\EditPost`:

    ```php
    namespace App\Filament\Resources\PostResource\Pages;

    use App\Filament\Resources\PostResource;
    use Filament\Resources\Pages\EditRecord;
    use App\Services\PostService;

    class EditPost extends EditRecord
    {
        protected static string $resource = PostResource::class;

        public PostService $postService;

        public function mount($record, PostService $postService)
        {
            $this->postService = $postService;

            parent::mount($record);
        }

        // Override the method Filament calls when saving
        protected function handleRecordUpdate(array $data): void
        {
            // Pass everything to the service
            $this->postService->updatePost($this->record, $data);
        }
    }
    ```

    Filament will take care of route-model binding (the `$record` ID in the route) and container resolution (injecting `PostService`).

---

## 7. Summary

-   **No Traditional Controller:** Filament manages routes and controllers for you.
-   **Leverage Livewire Components/Pages:** Each Filament Resource has “pages” (e.g., `Create{Model}`, `Edit{Model}`) that serve as your entry point.
-   **Inject Services in `mount()` or Overridden Methods:** Type-hint the service in your page’s `mount()` method (or constructor if you prefer) to inject business logic.
-   **Keep Business Logic in a Dedicated Service:** This follows the **single responsibility principle**—your Filament pages handle the form & UI, while your service contains the real business rules.

**That’s the Filament way** to replicate the “controller + service” approach you might use in plain Laravel, while still reaping the benefits of Filament’s out-of-the-box CRUD scaffolding.
