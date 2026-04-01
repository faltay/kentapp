# QR Menu SaaS - Kapsaml? Kodlama Standartlar?

## 1. Proje Mimarisi ve Genel Yap?

### 1.1 Proje ?zellikleri
- **Multi-tenant olmayan** yap? (tek veritaban?, restaurant_id ile ayr?m)
- **?!ok dilli (i18n)** destek (LaravelLocalization paketi)
- **Role-based** yetkilendirme (Spatie Permission)
- **SaaS abonelik** modeli (Stripe + ?yzico)
- **QR kod tabanl?** men? sistemi

### 1.2 MVC Katman Sorumluluklar?
- **Controllers**: HTTP iste?xi/yan?t? y?netimi, Service layer'a i?x mant??x?n? devretme
- **Models**: Veri ili?xkileri, accessor/mutator, basit query scope'lar?
- **Services**: Karma?x?k i?x mant??x? ve business logic
- **Repositories**: Opsiyonel, karma?x?k sorgular i?in veri eri?xim katman?

### 1.3 Klas?r Yap?s?
```
app/
?S???? Http/
?   ?S???? Controllers/
?   ?   ?S???? Admin/           # Super admin paneli
?   ?   ?S???? Restaurant/      # Restaurant owner/staff paneli  
?   ?   ?S???? Api/            # API endpoints
?   ?   ????? Public/         # Public men? g?r?nt?leme
?   ?S???? Requests/
?   ?   ?S???? Restaurant/
?   ?   ?S???? Branch/
?   ?   ????? MenuItem/
?   ????? Middleware/
?S???? Models/
?S???? Services/               # ??x mant??x? katman?
?   ?S???? Menu/
?   ?S???? QR/
?   ?S???? Payment/
?   ????? AI/
?S???? Repositories/          # Veri eri?xim katman? (opsiyonel)
????? Policies/             # Yetkilendirme kurallar?
```

## 2. ?simlendirme Kurallar?

### 2.1 Controller ?simlendirme
```php
// Resource Controllers - PascalCase
MenuItemController, BranchController, RestaurantController

// Single Action Controllers - ??xlev odakl?
GenerateQRCodeController, ProcessMenuUploadController

// Mod?l bazl? grupland?rma
Admin/UserController, Admin/RestaurantController
Restaurant/MenuController, Restaurant/TableController
```

### 2.2 Model ?simlendirme
```php
// Singular form - PascalCase
User, Restaurant, MenuItem, MenuCategory

// Relationship metodlar?
public function menuItems(): HasMany      // ?o?xul
public function restaurant(): BelongsTo   // tekil
public function qrCodes(): MorphMany      // ?o?xul + a??klay?c?
```

### 2.3 Route ?simlendirme
```php
// Resource routes - kebab-case
Route::resource('menu-items', MenuItemController::class);

// Named routes - dot notation
Route::get('/dashboard')->name('restaurant.dashboard');
Route::get('/menu/{slug}')->name('menu.restaurant.show');

// API routes - versioned
Route::prefix('api/v1')->name('api.v1.')->group(function () {
    Route::get('/menu', [ApiMenuController::class, 'index'])->name('menu.index');
});
```

### 2.4 Database ?simlendirme
```php
// Tables - snake_case, plural
restaurants, menu_items, qr_codes, user_subscriptions

// Columns - snake_case
created_at, restaurant_id, is_active, localized_name

// Foreign Keys - {model}_id
restaurant_id, user_id, menu_category_id

// Pivot Tables - alphabetical order
menu_item_tag, role_user
```

### 2.5 View ?simlendirme
```
resources/views/
?S???? layouts/
?   ?S???? admin.blade.php
?   ?S???? restaurant.blade.php
?   ????? menu.blade.php
?S???? admin/
?   ?S???? dashboard.blade.php
?   ????? users/
?       ?S???? index.blade.php
?       ?S???? create.blade.php
?       ????? edit.blade.php
?S???? restaurant/
?   ?S???? dashboard.blade.php
?   ?S???? menu/
?   ?   ?S???? categories/
?   ?   ????? items/
????? public/
    ????? menu/
```

## 3. Model Standartlar?

### 3.1 Model Dosya Yap?s?
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MenuItem extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;
    
    // 1. Constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_OUT_OF_STOCK = 'out_of_stock';
    
    // 2. Properties
    protected $fillable = [
        'restaurant_id', 'menu_category_id', 'name', 'description',
        'price', 'slug', 'is_active', 'is_featured', 'sort_order'
    ];
    
    protected $casts = [
        'name' => 'array',           // {"en": "Pizza", "tr": "Pizza"}
        'description' => 'array',    // {"en": "Delicious", "tr": "Lezzetli"}
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
    ];
    
    // 3. Static Methods
    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
            self::STATUS_OUT_OF_STOCK,
        ];
    }
    
    // 4. Relationships
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }
    
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
    
    // 5. Query Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
    
    public function scopePopular(Builder $query): Builder
    {
        return $query->orderByDesc('view_count');
    }
    
    public function scopeByRestaurant(Builder $query, int $restaurantId): Builder
    {
        return $query->where('restaurant_id', $restaurantId);
    }
    
    // 6. Accessors
    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->name[$locale] ?? $this->name['en'] ?? 'Default Name';
    }
    
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' ??';
    }
    
    // 7. Mutators
    public function setSlugAttribute($value): void
    {
        $this->attributes['slug'] = \Str::slug($value);
    }
    
    // 8. Business Logic Methods
    public function isAvailable(): bool
    {
        return $this->is_active && $this->status !== self::STATUS_OUT_OF_STOCK;
    }
    
    public function applyDiscount(float $percentage): void
    {
        $this->price = $this->price * (1 - $percentage / 100);
        $this->save();
    }
    
    // 9. Media Collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();
    }
    
    // 10. Activity Logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'price', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    
    // 11. Route Key Name
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
```

### 3.2 ?!ok Dilli Field Standartlar?
```php
// Migration'da JSON field
$table->json('name');           // {"en": "English", "tr": "T?rk?e"}
$table->json('description');    // {"en": "Description", "tr": "A??klama"}

// Model'de cast
protected $casts = [
    'name' => 'array',
    'description' => 'array',
];

// Accessor ile lokalizasyon
public function getLocalizedNameAttribute(): string
{
    $locale = app()->getLocale();
    return $this->name[$locale] ?? $this->name['en'] ?? '';
}
```

## 4. Controller Standartlar?

### 4.1 Base Controller Yap?s?
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    protected function getCurrentRestaurant()
    {
        return auth()->user()->restaurant;
    }

    protected function checkRestaurantPermission(int $restaurantId): bool
    {
        return auth()->user()->restaurant_id === $restaurantId 
            || auth()->user()->hasRole('super_admin');
    }

    protected function getValidatedLocale(): string
    {
        $supportedLocales = ['en', 'tr'];
        $locale = request()->header('Accept-Language', 'en');
        
        return in_array($locale, $supportedLocales) ? $locale : 'en';
    }
}
```

### 4.2 Resource Controller Yap?s?

#### 4.2.1 Genel Yakla?x?m (ZORUNLU)

> **STANDART:** T?m form i?xlemleri i?in **AJAX/JSON response** kullan?l?r (hem Admin hem Restaurant panel).

**Neden AJAX zorunlu:**
- Modern SaaS UX (sayfa yenilenmeden i?xlem)
- File upload (resim, avatar vb.)
- Loading state g?sterme
- Field-level validation feedback
- Inline edit ve bulk actions

#### 4.2.2 Panel Controller Standard?

**?zellikler:**
- JSON response zorunlu
- Try-catch error handling
- Service layer kullan?m?
- File upload ?zel i?xleme
- HTTP status codes (201, 200, 500)

```php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Models\Restaurant;
use App\Services\UserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends BaseController
{
    public function __construct(
        private UserService $userService
    ) {
        $this->middleware('auth');
        $this->middleware('role:super_admin');
        $this->middleware('can:viewAny,App\Models\User')->only(['index']);
        $this->middleware('can:view,user')->only(['show']);
        $this->middleware('can:create,App\Models\User')->only(['create', 'store']);
        $this->middleware('can:update,user')->only(['edit', 'update']);
        $this->middleware('can:delete,user')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getUsersDataTable();
        }

        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
        ];

        return view('admin.users.index', compact('stats'));
    }

    public function create()
    {
        $restaurants = Restaurant::active()->get();
        $roles = Role::whereNotIn('name', ['super_admin'])->get();

        return view('admin.users.create', compact('restaurants', 'roles'));
    }

    /**
     * Store - JSON response (201 Created)
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('avatar')) {
                $data['avatar'] = $request->file('avatar');
            }

            $user = $this->userService->createUser($data);

            $message = __('admin.users.created_successfully');
           

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'user_id' => $user->id,
                    'avatar_uploaded' => $user->avatarUploaded ?? false,
                    'redirect_url' => route('admin.users.show', $user)
                ]
            ], 201);
            
        } catch (\Exception $e) {
            \Log::error('User creation failed: ' . $e->getMessage(), [
                'request_data' => $request->validated()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('admin.users.creation_failed'),
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show(User $user)
    {
        $user->load(['restaurant', 'roles']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $restaurants = Restaurant::active()->get();
        $roles = Role::whereNotIn('name', ['super_admin'])->get();
        $user->load('roles');

        return view('admin.users.edit', compact('user', 'restaurants', 'roles'));
    }

    /**
     * Update - JSON response (200 OK)
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('avatar')) {
                $data['avatar'] = $request->file('avatar');
            }

            $updatedUser = $this->userService->updateUser($user, $data);

            return response()->json([
                'success' => true,
                'message' => __('admin.users.updated_successfully'),
                'data' => [
                    'user_id' => $updatedUser->id,
                    'redirect_url' => route('admin.users.show', $updatedUser)
                ]
            ], 200);
            
        } catch (\Exception $e) {
            \Log::error('User update failed: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => false,
                'message' => __('admin.users.update_failed')
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.users.errors.cannot_delete_self')
                ], 403);
            }

            $this->userService->deleteUser($user);

            return response()->json([
                'success' => true,
                'message' => __('admin.users.deleted_successfully')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.users.deletion_failed')
            ], 500);
        }
    }
}
```
### 4.3 Response Standartlar?

```php
// AJAX Response (ZORUNLU)
return response()->json([
    'success' => true,
    'message' => __('module.created_successfully'),
    'data' => [
        'id' => $item->id,
        'redirect_url' => route('module.show', $item)
    ]
], 201);

return response()->json([
    'success' => false,
    'message' => __('module.creation_failed'),
    'error' => config('app.debug') ? $e->getMessage() : null
], 500);

// HTTP Status Codes
// 200 OK - Successful GET, PUT, DELETE
// 201 Created - Successful POST
// 422 Unprocessable Entity - Validation errors (automatic)
// 500 Internal Server Error - Server errors
// 403 Forbidden - Permission denied
```

## 5. Request Validation Standartlar?

### 5.1 Form Request Yap?s?
```php
<?php

namespace App\Http\Requests\MenuItem;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\MenuItem;

class StoreMenuItemRequest extends FormRequest
{
    // 1. Authorization
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('create', MenuItem::class);
    }

    // 2. Validation rules
    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.*' => 'nullable|string|max:255',
            
            'description' => 'nullable|array',
            'description.*' => 'nullable|string|max:1000',
            
            'price' => 'required|numeric|min:0|max:999999.99',
            'menu_category_id' => 'required|exists:menu_categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    // 3. Custom messages
    public function messages(): array
    {
        return [
            'name.required' => __('Name is required.'),
            'name.en.required' => __('English name is required.'),
            'price.required' => __('Price is required.'),
            'price.numeric' => __('Price must be a number.'),
        ];
    }

    // 4. Custom attributes
    public function attributes(): array
    {
        return [
            'name.en' => __('English Name'),
            'name.tr' => __('Turkish Name'),
            'menu_category_id' => __('Menu Category'),
        ];
    }

    // 5. Data preparation
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
            'is_featured' => $this->boolean('is_featured', false),
            'slug' => \Str::slug($this->input('name.en', '')),
        ]);
    }

    // 6. After validation hook
    protected function passedValidation(): void
    {
        // Additional processing after validation passes
    }
}
```

### 5.2 ?!ok Dilli Validation Kurallar?
```php
// ?!ok dilli field'ler i?in standart pattern
'name' => 'required|array',
'name.en' => 'required|string|max:255',      // ?ngilizce zorunlu
'name.tr' => 'nullable|string|max:255',      // T?rk?e opsiyonel
'name.*' => 'nullable|string|max:255',       // Di?xer diller opsiyonel

// Image validation
'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',    // 2MB
'images' => 'nullable|array|max:5',                              // Max 5 images
'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
```

## 6. Route Standartlar?

### 6.1 Route Grupland?rma
```php
<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Dil deste?xi ile wrapped
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function() {
    
    // Public routes - QR kod tarama sonras? men? g?r?nt?leme
    Route::prefix('menu')->name('menu.')->group(function () {
        Route::get('/{restaurant:slug}', [PublicMenuController::class, 'show'])
            ->name('restaurant');
        Route::get('/{restaurant:slug}/category/{category:slug}', [PublicMenuController::class, 'category'])
            ->name('category');
    });
    
    // Authentication routes
    Auth::routes(['verify' => true]);
    
    // Protected routes
    Route::middleware(['auth', 'verified'])->group(function () {
        
        // Admin panel - Super admin only
        Route::prefix('admin')->name('admin.')->middleware(['role:super_admin'])->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::resource('users', AdminUserController::class);
            Route::resource('restaurants', AdminRestaurantController::class);
            Route::resource('subscription-plans', AdminSubscriptionPlanController::class);
        });
        
        // Restaurant panel - Restaurant owners and staff
        Route::prefix('restaurant')->name('restaurant.')->middleware([
            'restaurant_access', 
            'check_subscription'
        ])->group(function () {
            Route::get('/dashboard', [RestaurantDashboardController::class, 'index'])->name('dashboard');
            
            // Menu management
            Route::resource('menu-items', MenuItemController::class);
            Route::resource('menu-categories', MenuCategoryController::class);
            
            // QR Code management
            Route::post('/qr-codes/{table}/generate', [QRCodeController::class, 'generate'])
                ->name('qr-codes.generate');
            
            // Settings
            Route::get('/settings', [RestaurantSettingsController::class, 'edit'])->name('settings.edit');
            Route::put('/settings', [RestaurantSettingsController::class, 'update'])->name('settings.update');
        });
        
        // Payment and subscription routes
        Route::prefix('billing')->name('billing.')->group(function () {
            Route::get('/plans', [BillingController::class, 'plans'])->name('plans');
            Route::post('/subscribe', [BillingController::class, 'subscribe'])->name('subscribe');
            Route::post('/cancel', [BillingController::class, 'cancel'])->name('cancel');
        });
    });
});

// API Routes - Stateless, JSON responses
Route::prefix('api/v1')->name('api.v1.')->middleware(['api'])->group(function () {
    // Public API endpoints
    Route::get('/menu/{restaurant:slug}', [ApiMenuController::class, 'show'])->name('menu.show');
    
    // Protected API endpoints
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('restaurants', ApiRestaurantController::class);
        Route::apiResource('menu-items', ApiMenuItemController::class);
    });
});
```

### 6.2 Route Naming Conventions
```php
// Pattern: {module}.{resource}.{action}
'admin.users.index'                    // Admin kullan?c? listesi
'admin.restaurants.create'             // Admin restaurant olu?xturma
'restaurant.menu.items.edit'           // Restaurant men? ??xesi d?zenleme
'restaurant.dashboard'                 // Restaurant ana sayfa
'menu.restaurant'                      // Public men? g?r?nt?leme
'api.v1.menu.show'                     // API men? detay?
```

## 7. Database ve Migration Standartlar?

### 7.1 Migration Dosya Adland?rma
```php
// Format: YYYY_MM_DD_HHMMSS_action_table_name.php
2025_07_12_073014_create_languages_table.php
2025_07_12_073027_create_subscription_plans_table.php
2025_07_12_073109_add_restaurant_fields_to_users_table.php
2025_07_12_073150_create_menu_items_table.php
```

### 7.2 Migration Yap?s? Standartlar?
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys first - with proper constraints
            $table->foreignId('restaurant_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('menu_category_id')
                ->constrained()
                ->onDelete('cascade');
            
            // Required fields
            $table->json('name');                    // {"en": "Name", "tr": "?sim"}
            $table->string('slug');
            $table->decimal('price', 8, 2);
            
            // Optional fields
            $table->json('description')->nullable(); // {"en": "Desc", "tr": "A??klama"}
            $table->text('ingredients')->nullable();
            $table->string('allergens')->nullable();
            
            // Boolean fields with defaults
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_spicy')->default(false);
            $table->boolean('is_vegetarian')->default(false);
            
            // Numeric fields
            $table->integer('sort_order')->default(0);
            $table->integer('preparation_time')->nullable(); // minutes
            $table->integer('view_count')->default(0);
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['restaurant_id', 'is_active']);
            $table->index(['menu_category_id', 'sort_order']);
            $table->unique(['restaurant_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
```

### 7.3 Index ve Foreign Key Standartlar?
```php
// Single column index - otomatik
$table->index('restaurant_id');

// Composite index - performance i?in
$table->index(['restaurant_id', 'is_active'], 'idx_restaurant_active');
$table->index(['menu_category_id', 'sort_order'], 'idx_category_sort');

// Unique constraints
$table->unique(['restaurant_id', 'slug'], 'unique_slug_per_restaurant');

// Foreign keys with cascade
$table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
$table->foreignId('user_id')->constrained()->onDelete('set null');
```

## 8. View ve Blade Standartlar?

### 8.1 Layout Inheritance
```blade
{{-- layouts/restaurant.blade.php --}}
<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', __('Dashboard')) - {{ config('app.name') }}</title>
    
    <!-- CSS files -->
    <link href="{{ asset('css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    @stack('styles')
</head>
<body>
    <div class="page">
        <!-- Header -->
        @include('layouts.partials.restaurant.header')
        
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">@yield('page-title', __('Dashboard'))</h2>
                            @hasSection('breadcrumb')
                                @yield('breadcrumb')
                            @endif
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            @yield('page-actions')
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    @if(session('success'))
                        <x-alert type="success" :message="session('success')" />
                    @endif
                    
                    @if(session('error'))
                        <x-alert type="danger" :message="session('error')" />
                    @endif
                    
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <!-- JS files -->
    <script src="{{ asset('js/tabler.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
```

### 8.2 Sayfa Yap?s?
```blade
{{-- restaurant/menu/items/index.blade.php --}}
@extends('layouts.restaurant')

@section('title', __('Menu Items'))
@section('page-title', __('Menu Items'))

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Menu Items') }}</li>
        </ol>
    </nav>
@endsection

@section('page-actions')
    <div class="btn-list">
        <a href="{{ route('restaurant.menu.items.create') }}" class="btn btn-primary">
            <svg class="icon">...</svg>
            {{ __('Add Menu Item') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Content here --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('css/menu-items.css') }}" rel="stylesheet"/>
@endpush

@push('scripts')
    <script>
        // Sayfa ?zel JavaScript
    </script>
@endpush
```

### 8.3 Component Kullan?m?
```blade
{{-- Form components --}}
<x-form.input 
    name="name[en]" 
    :label="__('English Name')" 
    :value="old('name.en', $menuItem->name['en'] ?? '')" 
    required 
/>

<x-form.textarea 
    name="description[tr]" 
    :label="__('Turkish Description')" 
    :value="old('description.tr', $menuItem->description['tr'] ?? '')" 
/>

<x-form.select 
    name="menu_category_id" 
    :label="__('Category')" 
    :options="$categories->pluck('localized_name', 'id')" 
    :value="old('menu_category_id', $menuItem->menu_category_id ?? '')" 
    required 
/>

{{-- Alert component --}}
<x-alert type="success" :message="__('Operation completed successfully')" />
<x-alert type="warning" :message="__('Please check your input')" dismissible />
```

### 8.4 Dil Deste?xi
```blade
{{-- Temel ?eviri --}}
{{ __('Dashboard') }}
{{ __('Create Restaurant') }}

{{-- Parametreli ?eviri --}}
{{ __(':count items found', ['count' => $items->count()]) }}
{{ __('Welcome back, :name', ['name' => auth()->user()->name]) }}

{{-- ?!o?xul form ?evirisi --}}
{{ trans_choice('menu.item_count', $count, ['count' => $count]) }}

{{-- Conditional translation --}}
{{ $restaurant->is_active ? __('Active') : __('Inactive') }}

{{-- Form validasyonunda dil deste?xi --}}
@error('name.en')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
```

## 9. Service Layer Standartlar?

### 9.1 Service S?n?f? Yap?s?
```php
<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuService
{
    public function createMenuItem(Restaurant $restaurant, array $data): MenuItem
    {
        try {
            DB::beginTransaction();
            
            // Data validation ve preparation
            $this->validateMenuItemData($data);
            $data = $this->prepareMenuItemData($data);
            
            // Ana kay?t olu?xturma
            $menuItem = $restaurant->menuItems()->create($data);
            
            // ?li?xkili i?xlemler
            $this->processMenuItemImage($menuItem, $data['image'] ?? null);
            $this->updateRestaurantStats($restaurant);
            $this->logMenuItemActivity($menuItem, 'created');
            
            DB::commit();
            
            return $menuItem;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Menu item creation failed', [
                'restaurant_id' => $restaurant->id,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function updateMenuItem(MenuItem $menuItem, array $data): MenuItem
    {
        try {
            DB::beginTransaction();
            
            $oldData = $menuItem->toArray();
            
            // Update i?xlemi
            $data = $this->prepareMenuItemData($data);
            $menuItem->update($data);
            
            // Image g?ncelleme
            if (isset($data['image'])) {
                $this->processMenuItemImage($menuItem, $data['image']);
            }
            
            // Activity log
            $this->logMenuItemActivity($menuItem, 'updated', $oldData);
            
            DB::commit();
            
            return $menuItem->refresh();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Menu item update failed', [
                'menu_item_id' => $menuItem->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function duplicateMenuItem(MenuItem $original): MenuItem
    {
        $data = $original->toArray();
        
        // Duplicate i?in unique field'lar? g?ncelle
        unset($data['id'], $data['created_at'], $data['updated_at']);
        $data['slug'] = $data['slug'] . '-copy-' . time();
        $data['name']['en'] = $data['name']['en'] . ' (Copy)';
        
        return $this->createMenuItem($original->restaurant, $data);
    }

    private function validateMenuItemData(array $data): void
    {
        // Business validation rules
        if (empty($data['name']['en'])) {
            throw new \InvalidArgumentException('English name is required');
        }
        
        if ($data['price'] < 0) {
            throw new \InvalidArgumentException('Price cannot be negative');
        }
    }

    private function prepareMenuItemData(array $data): array
    {
        // Slug generation
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['name']['en']);
        }
        
        // Default values
        $data['is_active'] = $data['is_active'] ?? true;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        
        return $data;
    }

    private function processMenuItemImage(MenuItem $menuItem, $image): void
    {
        if ($image) {
            $menuItem->clearMediaCollection('images');
            $menuItem->addMediaFromRequest('image')
                ->toMediaCollection('images');
        }
    }

    private function updateRestaurantStats(Restaurant $restaurant): void
    {
        $restaurant->update([
            'menu_items_count' => $restaurant->menuItems()->count()
        ]);
    }

    private function logMenuItemActivity(MenuItem $menuItem, string $action, array $oldData = []): void
    {
        activity($action)
            ->performedOn($menuItem)
            ->causedBy(auth()->user())
            ->withProperties(['old' => $oldData])
            ->log("Menu item {$action}");
    }
}
```

### 9.2 QR Code Service ?rne?xi
```php
<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\Table;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    public function generateTableQRCode(Table $table): string
    {
        $restaurant = $table->restaurant;
        
        // QR kod i?in URL olu?xtur
        $url = route('menu.restaurant', [
            'restaurant' => $restaurant->slug,
            'table' => $table->number
        ]);
        
        // QR kod generate et
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($url);
        
        // Dosya ad? ve path
        $fileName = "qr-codes/{$restaurant->slug}/table-{$table->number}.png";
        
        // Storage'a kaydet
        Storage::put($fileName, $qrCode);
        
        // Table modelini g?ncelle
        $table->update([
            'qr_code_path' => $fileName,
            'qr_code_url' => $url,
            'last_qr_generated_at' => now()
        ]);
        
        return Storage::url($fileName);
    }

    public function generateRestaurantMenuQR(Restaurant $restaurant): string
    {
        $url = route('menu.restaurant', $restaurant->slug);
        
        $qrCode = QrCode::format('svg')
            ->size(400)
            ->margin(3)
            ->generate($url);
        
        $fileName = "qr-codes/{$restaurant->slug}/menu.svg";
        Storage::put($fileName, $qrCode);
        
        return Storage::url($fileName);
    }

    public function bulkGenerateQRCodes(Restaurant $restaurant): array
    {
        $results = [];
        
        foreach ($restaurant->tables as $table) {
            try {
                $qrPath = $this->generateTableQRCode($table);
                $results['success'][] = $table->number;
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'table' => $table->number,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $results;
    }
}
```

## 10. Middleware Standartlar?

### 10.1 Restaurant Access Middleware
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestaurantAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Super admin her ?xeye eri?xebilir
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }
        
        // Kullan?c?n?n restaurant'? olmal?
        if (!$user->restaurant) {
            return redirect()->route('restaurant.setup')
                ->with('error', __('Please complete your restaurant setup first.'));
        }
        
        // Restaurant aktif olmal?
        if (!$user->restaurant->is_active) {
            return redirect()->route('restaurant.suspended')
                ->with('error', __('Your restaurant account is suspended.'));
        }
        
        return $next($request);
    }
}
```

### 10.2 Subscription Check Middleware
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Super admin exempt
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }
        
        $restaurant = $user->restaurant;
        
        if (!$restaurant) {
            return redirect()->route('restaurant.setup');
        }
        
        // Subscription kontrol?
        if (!$restaurant->hasActiveSubscription()) {
            return redirect()->route('billing.plans')
                ->with('warning', __('Please choose a subscription plan to continue.'));
        }
        
        // Trial s?resi kontrol?
        if ($restaurant->isTrialExpired()) {
            return redirect()->route('billing.plans')
                ->with('warning', __('Your trial period has expired. Please upgrade your plan.'));
        }
        
        return $next($request);
    }
}
```

### 10.3 Usage Limits Middleware
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUsageLimits
{
    public function handle(Request $request, Closure $next, string $feature)
    {
        $user = auth()->user();
        $restaurant = $user->restaurant;
        
        if (!$restaurant) {
            return redirect()->route('restaurant.setup');
        }
        
        $subscription = $restaurant->activeSubscription();
        
        if (!$subscription) {
            return back()->with('error', __('No active subscription found.'));
        }
        
        // Feature limits kontrol?
        switch ($feature) {
            case 'menu_item':
                $current = $restaurant->menuItems()->count();
                $limit = $subscription->plan->menu_items_limit;
                break;
                
            case 'table':
                $current = $restaurant->tables()->count();
                $limit = $subscription->plan->tables_limit;
                break;
                
            case 'branch':
                $current = $restaurant->branches()->count();
                $limit = $subscription->plan->branches_limit;
                break;
                
            default:
                return $next($request);
        }
        
        if ($current >= $limit) {
            return back()->with('error', 
                __('You have reached the limit for :feature. Please upgrade your plan.', [
                    'feature' => __($feature)
                ])
            );
        }
        
        return $next($request);
    }
}
```

## 11. API Standartlar?

### 11.1 API Controller Yap?s?
```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuItemResource;
use App\Models\Restaurant;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'category' => 'nullable|exists:menu_categories,id',
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        $query = $restaurant->menuItems()
            ->with(['category', 'media'])
            ->active();

        // Filtreleme
        if ($request->category) {
            $query->where('menu_category_id', $request->category);
        }

        if ($request->search) {
            $query->whereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$request->search}%"])
                  ->orWhereRaw("JSON_EXTRACT(name, '$.tr') LIKE ?", ["%{$request->search}%"]);
        }

        $menuItems = $query->paginate($request->per_page ?? 15);

        return MenuItemResource::collection($menuItems)
            ->additional([
                'meta' => [
                    'restaurant' => [
                        'name' => $restaurant->localized_name,
                        'description' => $restaurant->localized_description,
                        'currency' => $restaurant->currency ?? 'TRY'
                    ]
                ]
            ]);
    }

    public function show(Restaurant $restaurant, MenuItem $menuItem)
    {
        // Restaurant ownership kontrol?
        if ($menuItem->restaurant_id !== $restaurant->id) {
            return response()->json([
                'error' => 'Menu item not found in this restaurant'
            ], 404);
        }

        // G?r?nt?lenme say?s?n? art?r
        $menuItem->increment('view_count');

        return new MenuItemResource($menuItem->load(['category', 'media', 'tags']));
    }
}
```

### 11.2 API Resource Yap?s?
```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'localized_name' => $this->localized_name,
            'description' => $this->description,
            'localized_description' => $this->localized_description,
            'slug' => $this->slug,
            'price' => [
                'amount' => $this->price,
                'formatted' => $this->formatted_price,
                'currency' => $this->restaurant->currency ?? 'TRY'
            ],
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->localized_name,
                'slug' => $this->category->slug
            ],
            'features' => [
                'is_featured' => $this->is_featured,
                'is_vegetarian' => $this->is_vegetarian,
                'is_spicy' => $this->is_spicy,
                'is_available' => $this->isAvailable()
            ],
            'media' => [
                'image_url' => $this->getFirstMediaUrl('images'),
                'thumb_url' => $this->getFirstMediaUrl('images', 'thumb')
            ],
            'tags' => $this->whenLoaded('tags', function () {
                return $this->tags->pluck('name');
            }),
            'meta' => [
                'preparation_time' => $this->preparation_time,
                'view_count' => $this->view_count,
                'created_at' => $this->created_at->toISOString(),
                'updated_at' => $this->updated_at->toISOString()
            ]
        ];
    }
}
```

### 11.3 API Response Formatlar?
```php
// Ba?xar?l? response
{
    "data": [...],
    "links": {
        "first": "http://api.example.com/menu-items?page=1",
        "last": "http://api.example.com/menu-items?page=10",
        "prev": null,
        "next": "http://api.example.com/menu-items?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 10,
        "per_page": 15,
        "to": 15,
        "total": 150,
        "restaurant": {
            "name": "Restaurant Name",
            "currency": "TRY"
        }
    }
}

// Hata response
{
    "error": "Resource not found",
    "message": "The requested menu item could not be found.",
    "code": 404
}

// Validation error response
{
    "error": "Validation failed",
    "message": "The given data was invalid.",
    "errors": {
        "name.en": ["The English name field is required."],
        "price": ["The price must be a number."]
    }
}
```

## 12. Testing Standartlar?

### 12.1 Feature Test Yap?s?
```php
<?php

namespace Tests\Feature\Restaurant;

use Tests\TestCase;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\MenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MenuItemTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Restaurant $restaurant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->restaurant = Restaurant::factory()->create([
            'user_id' => $this->user->id
        ]);
        
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_can_view_menu_items_list()
    {
        MenuItem::factory(5)->create(['restaurant_id' => $this->restaurant->id]);

        $response = $this->get(route('restaurant.menu.items.index'));

        $response->assertStatus(200);
        $response->assertViewIs('restaurant.menu.items.index');
        $response->assertViewHas('menuItems');
    }

    /** @test */
    public function user_can_create_menu_item()
    {
        Storage::fake('public');

        $menuItemData = [
            'name' => [
                'en' => 'Test Pizza',
                'tr' => 'Test Pizza'
            ],
            'description' => [
                'en' => 'Delicious test pizza',
                'tr' => 'Lezzetli test pizza'
            ],
            'price' => 25.99,
            'menu_category_id' => $this->restaurant->menuCategories()->first()->id,
            'is_active' => true,
            'image' => UploadedFile::fake()->image('pizza.jpg')
        ];

        $response = $this->post(route('restaurant.menu.items.store'), $menuItemData);

        $response->assertRedirect(route('restaurant.menu.items.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('menu_items', [
            'restaurant_id' => $this->restaurant->id,
            'price' => 25.99
        ]);

        // Image upload kontrol?
        $menuItem = MenuItem::latest()->first();
        $this->assertTrue($menuItem->hasMedia('images'));
    }

    /** @test */
    public function user_cannot_create_menu_item_without_english_name()
    {
        $menuItemData = [
            'name' => ['tr' => 'Test Pizza'],
            'price' => 25.99
        ];

        $response = $this->post(route('restaurant.menu.items.store'), $menuItemData);

        $response->assertSessionHasErrors(['name.en']);
        $this->assertDatabaseCount('menu_items', 0);
    }

    /** @test */
    public function user_cannot_access_other_restaurant_menu_items()
    {
        $otherRestaurant = Restaurant::factory()->create();
        $otherMenuItem = MenuItem::factory()->create([
            'restaurant_id' => $otherRestaurant->id
        ]);

        $response = $this->get(route('restaurant.menu.items.edit', $otherMenuItem));

        $response->assertStatus(403);
    }

    /** @test */
    public function menu_item_can_be_duplicated()
    {
        $originalItem = MenuItem::factory()->create([
            'restaurant_id' => $this->restaurant->id,
            'name' => ['en' => 'Original Pizza', 'tr' => 'Orijinal Pizza']
        ]);

        $response = $this->post(route('restaurant.menu.items.duplicate', $originalItem));

        $response->assertRedirect();
        $this->assertDatabaseCount('menu_items', 2);

        $duplicatedItem = MenuItem::where('id', '!=', $originalItem->id)->first();
        $this->assertStringContains('Copy', $duplicatedItem->name['en']);
        $this->assertNotEquals($originalItem->slug, $duplicatedItem->slug);
    }
}
```

### 12.2 Unit Test Yap?s?
```php
<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_localized_name_for_current_locale()
    {
        $menuItem = MenuItem::factory()->create([
            'name' => [
                'en' => 'Pizza',
                'tr' => 'Pizza'
            ]
        ]);

        app()->setLocale('tr');
        $this->assertEquals('Pizza', $menuItem->localized_name);

        app()->setLocale('en');
        $this->assertEquals('Pizza', $menuItem->localized_name);
    }

    /** @test */
    public function it_falls_back_to_english_when_locale_not_available()
    {
        $menuItem = MenuItem::factory()->create([
            'name' => ['en' => 'Pizza']
        ]);

        app()->setLocale('tr');
        $this->assertEquals('Pizza', $menuItem->localized_name);
    }

    /** @test */
    public function it_can_check_availability()
    {
        $availableItem = MenuItem::factory()->create([
            'is_active' => true,
            'status' => MenuItem::STATUS_ACTIVE
        ]);

        $unavailableItem = MenuItem::factory()->create([
            'is_active' => false
        ]);

        $this->assertTrue($availableItem->isAvailable());
        $this->assertFalse($unavailableItem->isAvailable());
    }

    /** @test */
    public function it_formats_price_correctly()
    {
        $menuItem = MenuItem::factory()->create(['price' => 25.99]);

        $this->assertEquals('25,99 ??', $menuItem->formatted_price);
    }
}
```

## 13. Security ve Performance Standartlar?

### 13.1 Authorization (Policy) Standartlar?
```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MenuItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->restaurant !== null || $user->hasRole('super_admin');
    }

    public function view(User $user, MenuItem $menuItem): bool
    {
        return $user->hasRole('super_admin') 
            || $user->restaurant_id === $menuItem->restaurant_id;
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        if (!$user->restaurant) {
            return false;
        }

        // Subscription limit kontrol?
        $subscription = $user->restaurant->activeSubscription();
        if (!$subscription) {
            return false;
        }

        $currentCount = $user->restaurant->menuItems()->count();
        return $currentCount < $subscription->plan->menu_items_limit;
    }

    public function update(User $user, MenuItem $menuItem): bool
    {
        return $user->hasRole('super_admin') 
            || $user->restaurant_id === $menuItem->restaurant_id;
    }

    public function delete(User $user, MenuItem $menuItem): bool
    {
        return $user->hasRole('super_admin') 
            || $user->restaurant_id === $menuItem->restaurant_id;
    }
}
```

### 13.2 Query Optimization
```php
// Eager loading kullan?m?
$menuItems = MenuItem::with(['restaurant', 'category', 'media', 'tags'])
    ->active()
    ->paginate(15);

// Specific columns selection
$items = MenuItem::select(['id', 'name', 'price', 'is_active'])
    ->where('restaurant_id', $restaurantId)
    ->get();

// Index kullan?m?n? optimize et
$popularItems = MenuItem::select(['id', 'name', 'price', 'view_count'])
    ->where('restaurant_id', $restaurantId)
    ->where('is_active', true)  // Index: restaurant_id, is_active
    ->orderByDesc('view_count')
    ->limit(10)
    ->get();
```

### 13.3 Cache Stratejileri
```php
// Repository pattern ile cache
class MenuRepository
{
    public function getPopularItems(Restaurant $restaurant, int $limit = 10)
    {
        $cacheKey = "popular_items_{$restaurant->id}_{$limit}";
        
        return Cache::remember($cacheKey, 3600, function () use ($restaurant, $limit) {
            return $restaurant->menuItems()
                ->active()
                ->orderByDesc('view_count')
                ->limit($limit)
                ->get();
        });
    }

    public function getMenuByCategory(Restaurant $restaurant)
    {
        $cacheKey = "menu_by_category_{$restaurant->id}_" . app()->getLocale();
        
        return Cache::remember($cacheKey, 1800, function () use ($restaurant) {
            return $restaurant->menuCategories()
                ->with(['menuItems' => function ($query) {
                    $query->active()->orderBy('sort_order');
                }])
                ->orderBy('sort_order')
                ->get();
        });
    }
}

// Cache invalidation
class MenuService
{
    public function createMenuItem(Restaurant $restaurant, array $data): MenuItem
    {
        $menuItem = $restaurant->menuItems()->create($data);
        
        // Cache'i temizle
        $this->clearMenuCache($restaurant);
        
        return $menuItem;
    }

    private function clearMenuCache(Restaurant $restaurant): void
    {
        Cache::forget("popular_items_{$restaurant->id}_10");
        Cache::forget("menu_by_category_{$restaurant->id}_en");
        Cache::forget("menu_by_category_{$restaurant->id}_tr");
    }
}
```

### 13.4 Rate Limiting
```php
// API rate limiting
Route::middleware(['throttle:60,1'])->group(function () {
    // Public API endpoints - 60 requests per minute
});

Route::middleware(['throttle:1000,1'])->group(function () {
    // Authenticated API endpoints - 1000 requests per minute
});

// Custom rate limiting
class CustomRateLimit
{
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            throw new ThrottleRequestsException();
        }
        
        RateLimiter::hit($key, $decayMinutes * 60);
        
        return $next($request);
    }
}
```

## 14. Error Handling ve Logging

### 14.1 Global Exception Handler
```php
<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Custom logging
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }

    public function render($request, Throwable $e)
    {
        // API requests i?in JSON response
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        // Model not found exception
        if ($e instanceof ModelNotFoundException) {
            return response()->view('errors.404', [], 404);
        }

        // Restaurant access exception
        if ($e instanceof RestaurantAccessException) {
            return redirect()->route('restaurant.setup')
                ->with('error', $e->getMessage());
        }

        return parent::render($request, $e);
    }

    private function handleApiException($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ], 422);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'Resource not found',
                'message' => 'The requested resource could not be found.'
            ], 404);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'error' => 'Endpoint not found',
                'message' => 'The requested endpoint does not exist.'
            ], 404);
        }

        // Production'da detayl? hata bilgisi verme
        $message = app()->environment('production') 
            ? 'Something went wrong' 
            : $e->getMessage();

        return response()->json([
            'error' => 'Server error',
            'message' => $message
        ], 500);
    }
}
```

### 14.2 Custom Exception Classes
```php
<?php

namespace App\Exceptions;

use Exception;

class RestaurantAccessException extends Exception
{
    public static function unauthorized(): self
    {
        return new self('You do not have access to this restaurant.');
    }

    public static function suspended(): self
    {
        return new self('Your restaurant account is suspended.');
    }

    public static function subscriptionRequired(): self
    {
        return new self('An active subscription is required to access this feature.');
    }
}

class UsageLimitException extends Exception
{
    public static function exceeded(string $feature, int $limit): self
    {
        return new self("You have exceeded the limit for {$feature} ({$limit}).");
    }
}
```

### 14.3 Structured Logging
```php
// Service layer'da structured logging
class MenuService
{
    public function createMenuItem(Restaurant $restaurant, array $data): MenuItem
    {
        Log::info('Creating menu item', [
            'user_id' => auth()->id(),
            'restaurant_id' => $restaurant->id,
            'item_name' => $data['name']['en']
        ]);

        try {
            $menuItem = $this->performCreation($restaurant, $data);
            
            Log::info('Menu item created successfully', [
                'menu_item_id' => $menuItem->id,
                'restaurant_id' => $restaurant->id
            ]);
            
            return $menuItem;
            
        } catch (\Exception $e) {
            Log::error('Menu item creation failed', [
                'user_id' => auth()->id(),
                'restaurant_id' => $restaurant->id,
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
}

// Activity logging
activity('menu_item_created')
    ->performedOn($menuItem)
    ->causedBy(auth()->user())
    ->withProperties([
        'restaurant_id' => $restaurant->id,
        'category_id' => $menuItem->menu_category_id
    ])
    ->log('Menu item created');
```

## 15. Deployment ve Environment Standartlar?

### 15.1 Environment Configuration
```bash
# .env.example
APP_NAME="QR Menu SaaS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_TIMEZONE=UTC

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qr_menu_saas
DB_USERNAME=
DB_PASSWORD=

# Cache & Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# File Storage
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
AWS_URL=

# Payment Gateways
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
STRIPE_WEBHOOK_SECRET=

IYZICO_API_KEY=
IYZICO_SECRET_KEY=
IYZICO_BASE_URL=https://api.iyzipay.com

# AI Integration
OPENAI_API_KEY=sk-...
OPENAI_ORGANIZATION=

# Localization
SUPPORTED_LOCALES=en,tr
DEFAULT_LOCALE=en
FALLBACK_LOCALE=en

# Logging
LOG_CHANNEL=stack
LOG_STACK=single,daily
LOG_LEVEL=error

# Error Tracking
SENTRY_LARAVEL_DSN=
```

### 15.2 Production Optimizations
```php
// config/app.php - Production settings
return [
    'debug' => env('APP_DEBUG', false),
    'log_level' => env('LOG_LEVEL', 'error'),
    
    // Asset optimization
    'asset_url' => env('ASSET_URL', null),
    
    // Timezone
    'timezone' => env('APP_TIMEZONE', 'UTC'),
];

// config/cache.php - Production cache settings
'default' => env('CACHE_DRIVER', 'redis'),

'stores' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
    ],
],

// config/session.php - Production session settings
'driver' => env('SESSION_DRIVER', 'redis'),
'lifetime' => env('SESSION_LIFETIME', 120),
'expire_on_close' => false,
'encrypt' => true,
'files' => storage_path('framework/sessions'),
'connection' => 'session',
'table' => 'sessions',
'store' => null,
'lottery' => [2, 100],
'cookie' => env('SESSION_COOKIE', 'laravel_session'),
'path' => '/',
'domain' => env('SESSION_DOMAIN', null),
'secure' => env('SESSION_SECURE_COOKIE', true),
'http_only' => true,
'same_site' => 'lax',
```

### 15.3 Queue Configuration
```php
// config/queue.php
'default' => env('QUEUE_CONNECTION', 'redis'),

'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
        'after_commit' => false,
    ],
],

// Job s?n?f? ?rne?xi
<?php

namespace App\Jobs;

use App\Models\Restaurant;
use App\Services\QRCodeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateQRCodesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes
    public $tries = 3;

    public function __construct(
        private Restaurant $restaurant
    ) {}

    public function handle(QRCodeService $qrCodeService): void
    {
        $qrCodeService->bulkGenerateQRCodes($this->restaurant);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('QR code generation failed', [
            'restaurant_id' => $this->restaurant->id,
            'error' => $exception->getMessage()
        ]);
    }
}
```

## 16. Localization (?!ok Dil) Standartlar?

### 16.1 Language Files Organization
```
resources/lang/
?S???? en/
?   ?S???? admin/
?   ?   ?S???? users.php
?   ?   ????? restaurants.php
?   ?S???? restaurant/
?   ?   ?S???? menu.php
?   ?   ????? tables.php
?   ?S???? common.php
?   ????? auth.php
????? tr/
    ????? (ayn? yap?)
```

### 16.2 Translation Dosyas? ??eri?xi

**resources/lang/en/admin/users.php:**

```php
<?php

return [
    // CRUD Messages
    'created_successfully' => 'User created successfully',
    'updated_successfully' => 'User updated successfully',
    'deleted_successfully' => 'User deleted successfully',
    'creation_failed' => 'Failed to create user',
    'update_failed' => 'Failed to update user',
    
    // Page Titles
    'create_user' => 'Create New User',
    'edit_user' => 'Edit User',
    
    // Form Field Labels
    'form' => [
        'name' => 'Full Name',
        'email' => 'Email Address',
        'phone' => 'Phone Number',
        'password' => 'Password',
        'roles' => 'User Roles',
        'avatar' => 'Profile Picture',
    ],
    
    // Validation Messages
    'validation' => [
        'name_required' => 'Please enter user name',
        'email_required' => 'Email address is required',
        'email_unique' => 'This email is already registered',
        'password_required' => 'Password is required',
        'password_confirmed' => 'Password confirmation does not match',
        'avatar_max' => 'Avatar file size must not exceed 2MB',
    ],
    
    // Placeholders
    'placeholders' => [
        'name' => 'Enter full name',
        'email' => 'user@example.com',
        'search' => 'Search users...',
    ],
    
    // Buttons
    'buttons' => [
        'create' => 'Create User',
        'update' => 'Update User',
    ],
    
    // Errors
    'errors' => [
        'cannot_delete_self' => 'You cannot delete your own account',
        'avatar_upload_failed_warning' => 'However, avatar upload failed',
    ],
];
```

**resources/lang/en/restaurant/menu.php:**

```php
<?php

return [
    // CRUD Messages
    'item_created_successfully' => 'Menu item created successfully',
    'item_updated_successfully' => 'Menu item updated successfully',
    'items_updated_successfully' => ':count items updated successfully',
    'item_creation_failed' => 'Failed to create menu item',
    
    // Form Field Labels
    'form' => [
        'name_en' => 'Name (English)',
        'name_tr' => 'Name (Turkish)',
        'price' => 'Price',
        'category' => 'Category',
        'image' => 'Item Image',
    ],
    
    // Validation Messages
    'validation' => [
        'name_en_required' => 'English name is required',
        'price_required' => 'Price is required',
        'price_numeric' => 'Price must be a number',
        'category_required' => 'Please select a category',
        'image_max' => 'Image size must not exceed 2MB',
    ],
    
    // Placeholders
    'placeholders' => [
        'name_en' => 'e.g., Margherita Pizza',
        'price' => '0.00',
    ],
];
```

**resources/lang/en/common.php:**

```php
<?php

return [
    'save' => 'Save',
    'cancel' => 'Cancel',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'back' => 'Back',
    'saving' => 'Saving...',
    'loading' => 'Loading...',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'back_to_list' => 'Back to List',
];
```

### 16.3 Controller Kullan?m?

```php
return response()->json([
    'success' => true,
    'message' => __('admin.users.created_successfully'),
], 201);

return response()->json([
    'success' => false,
    'message' => __('admin.users.errors.cannot_delete_self')
], 403);

return response()->json([
    'success' => true,
    'message' => __('restaurant.menu.items_updated_successfully', ['count' => $count]),
], 200);
```

### 16.4 Form Request Kullan?m?

```php
// Admin/User/StoreUserRequest.php
public function messages(): array
{
    return [
        'name.required' => __('admin.users.validation.name_required'),
        'email.required' => __('admin.users.validation.email_required'),
        'email.unique' => __('admin.users.validation.email_unique'),
        'password.required' => __('admin.users.validation.password_required'),
        'password.confirmed' => __('admin.users.validation.password_confirmed'),
        'avatar.max' => __('admin.users.validation.avatar_max'),
    ];
}
```

```php
// Restaurant/MenuItem/StoreMenuItemRequest.php
public function messages(): array
{
    return [
        'name.en.required' => __('restaurant.menu.validation.name_en_required'),
        'price.required' => __('restaurant.menu.validation.price_required'),
        'price.numeric' => __('restaurant.menu.validation.price_numeric'),
        'menu_category_id.required' => __('restaurant.menu.validation.category_required'),
        'image.max' => __('restaurant.menu.validation.image_max'),
    ];
}
```

### 16.5 Blade Kullan?m?

```blade
{{-- Admin Users --}}
<h1>{{ __('admin.users.create_user') }}</h1>

<label for="name" class="form-label required">
    {{ __('admin.users.form.name') }}
</label>

<input type="text" 
       name="name"
       placeholder="{{ __('admin.users.placeholders.name') }}"
       class="form-control">

<button type="submit" 
        class="btn btn-primary"
        data-loading-text="{{ __('common.saving') }}">
    {{ __('admin.users.buttons.create') }}
</button>
```

```blade
{{-- Restaurant Menu --}}
<h1>{{ __('restaurant.menu.create_item') }}</h1>

<label for="name_en" class="form-label required">
    {{ __('restaurant.menu.form.name_en') }}
</label>

<input type="text" 
       name="name[en]"
       placeholder="{{ __('restaurant.menu.placeholders.name_en') }}"
       class="form-control">

<button type="submit" class="btn btn-primary">
    {{ __('common.save') }}
</button>
```

### 16.6 Naming Convention

```
Pattern: {module}.{category}.{key}

Examples:
admin.users.created_successfully
admin.users.form.email
admin.users.validation.email_unique
admin.users.buttons.create

restaurant.menu.item_created_successfully
restaurant.menu.form.price
restaurant.menu.validation.price_required

common.save
common.saving
```

### 16.7 Pluralization (Choice/Plural ?!eviri)

**Translation dosyas?nda:**

```php
// resources/lang/en/restaurant/menu.php
return [
    'item_count' => '{0} No items|{1} :count item|[2,*] :count items',
    'users_selected' => '{0} No users selected|{1} :count user selected|[2,*] :count users selected',
];

// resources/lang/tr/restaurant/menu.php
return [
    'item_count' => '{0} ??xe yok|{1} :count ??xe|[2,*] :count ??xe',
    'users_selected' => '{0} Kullan?c? se?ilmedi|{1} :count kullan?c? se?ildi|[2,*] :count kullan?c? se?ildi',
];
```

**Blade'de kullan?m:**

```blade
{{-- Pluralization --}}
<p>{{ trans_choice('restaurant.menu.item_count', $count) }}</p>
<p>{{ trans_choice('admin.users.users_selected', $selectedCount) }}</p>

{{-- Sonu? ?rnekleri --}}
{{-- $count = 0 ?  "No items" --}}
{{-- $count = 1 ?  "1 item" --}}
{{-- $count = 5 ?  "5 items" --}}
```

### 16.8 JavaScript'te Translation Kullan?m?

**JSON y?ntemi (?NER?LEN):**

```blade
{{-- Blade dosyas?nda --}}
<script>
    // Translation'lar? JSON olarak JavaScript'e aktar
    const translations = {
        confirm_delete: @json(__('admin.users.confirm_delete')),
        delete_success: @json(__('admin.users.deleted_successfully')),
        delete_failed: @json(__('admin.users.deletion_failed')),
        cannot_delete_self: @json(__('admin.users.errors.cannot_delete_self')),
        saving: @json(__('common.saving')),
        loading: @json(__('common.loading')),
    };
    
    // Kullan?m
    if (confirm(translations.confirm_delete)) {
        // Delete i?xlemi
        showNotification('success', translations.delete_success);
    }
</script>
```

**Mod?l bazl? JSON export:**

```blade
<script>
    // T?m mod?l translation'lar?n? export et
    const menuTranslations = @json(__('restaurant.menu'));
    
    // Kullan?m
    alert(menuTranslations.item_created_successfully);
    console.log(menuTranslations.validation.price_required);
</script>
```

**AJAX form ile kullan?m:**

```blade
<script>
$(document).ready(function() {
    $('.ajax-form').ajaxForm({
        beforeSubmit: function() {
            $submitButton.html(`
                <i class="fas fa-spinner fa-spin"></i>
                @json(__('common.saving'))
            `);
        },
        
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: @json(__('common.success')),
                    text: response.message,
                    timer: 2000
                });
            }
        },
        
        error: function(xhr) {
            if (xhr.status === 422) {
                alert(@json(__('validation.form_has_errors')));
            }
        }
    });
});
</script>
```

### 16.9 LaravelLocalization Configuration

**Route yap?land?rmas? i?in gerekli config:**

**config/laravellocalization.php:**

```php
<?php

return [
    // Desteklenen diller
    'supportedLocales' => [
        'en' => [
            'name' => 'English',
            'script' => 'Latn',
            'native' => 'English',
            'regional' => 'en_GB'
        ],
        'tr' => [
            'name' => 'Turkish',
            'script' => 'Latn',
            'native' => 'T?rk?e',
            'regional' => 'tr_TR'
        ],
    ],
    
    // Accept-Language header'? kullan
    'useAcceptLanguageHeader' => true,
    
    // URL'de default dili gizleme (false ?nerilir)
    'hideDefaultLocaleInURL' => false,
    
    // Dil s?ralamas? (fallback i?in)
    'localesOrder' => ['en', 'tr'],
    
    // Dil mapping (opsiyonel)
    'localesMapping' => [],
    
    // UTF-8 suffix
    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),
    
    // Ignore edilecek URL'ler
    'urlsIgnored' => ['/api/*', '/webhooks/*'],
];
```

**Route kullan?m? (routes/web.php):**

```php
<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// LaravelLocalization ile wrap et
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function() {
    
    // Public routes
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
    });
    
    // Restaurant routes
    Route::prefix('restaurant')->name('restaurant.')->group(function () {
        Route::resource('menu-items', MenuItemController::class);
    });
});

// Dil de?xi?xtirme route'u
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'tr'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('locale.switch');
```

## 17. Media Management Standartlar?

### 17.1 Spatie MediaLibrary Configuration
```php
// config/media-library.php
return [
    'disk_name' => env('MEDIA_DISK', 'public'),
    'max_file_size' => 1024 * 1024 * 10, // 10MB
    'queue_connection_name' => env('QUEUE_CONNECTION', 'sync'),
    'queue_name' => '',
    'queue_conversions_by_default' => env('QUEUE_CONVERSIONS_BY_DEFAULT', true),
    
    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,
    'remote' => [
        'extra_headers' => [
            'CacheControl' => 'max-age=604800',
        ],
    ],
    
    'responsive_images' => [
        'width_calculator' => Spatie\MediaLibrary\ResponsiveImages\WidthCalculator\FileSizeOptimizedWidthCalculator::class,
        'use_tiny_placeholders' => true,
        'tiny_placeholder_generator' => Spatie\MediaLibrary\ResponsiveImages\TinyPlaceholderGenerator\Blurred::class,
    ],
    
    'conversion_quality' => 90,
    'path_generator' => null,
    'url_generator' => null,
    'version_urls' => false,
    'image_optimizers' => [
        Spatie\ImageOptimizer\Optimizers\Jpegoptim::class => [
            '-m85',
            '--force',
            '--strip-all',
            '--all-progressive',
        ],
        Spatie\ImageOptimizer\Optimizers\Pngquant::class => [
            '--force',
        ],
    ],
];
```

### 17.2 Media Collections ve Conversions
```php
// Model'de media collections
class MenuItem extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(800)
            ->height(600)
            ->optimize()
            ->performOnCollections('images', 'gallery');

        $this->addMediaConversion('webp')
            ->format('webp')
            ->width(800)
            ->optimize()
            ->performOnCollections('images');
    }
}

// Restaurant model i?in logo ve banner
class Restaurant extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/svg+xml']);

        $this->addMediaCollection('banner')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('logo-small')
            ->width(150)
            ->height(150)
            ->performOnCollections('logo');

        $this->addMediaConversion('banner-mobile')
            ->width(768)
            ->height(400)
            ->performOnCollections('banner');
    }
}
```

### 17.3 File Upload Handling
```php
// Controller'da file upload
public function store(StoreMenuItemRequest $request)
{
    $menuItem = MenuItem::create($request->validated());

    // Single image upload
    if ($request->hasFile('image')) {
        $menuItem->addMediaFromRequest('image')
            ->toMediaCollection('images');
    }

    // Multiple images upload
    if ($request->hasFile('gallery')) {
        foreach ($request->file('gallery') as $file) {
            $menuItem->addMedia($file)->toMediaCollection('gallery');
        }
    }

    return redirect()->route('restaurant.menu.items.index')
        ->with('success', __('Menu item created successfully'));
}

// Service layer'da file handling
class MenuService
{
    public function updateMenuItem(MenuItem $menuItem, array $data): MenuItem
    {
        $menuItem->update($data);

        if (isset($data['image'])) {
            // Eski resmi sil
            $menuItem->clearMediaCollection('images');
            
            // Yeni resmi ekle
            $menuItem->addMedia($data['image'])
                ->toMediaCollection('images');
        }

        return $menuItem;
    }

    public function deleteMenuItem(MenuItem $menuItem): bool
    {
        // ?li?xkili medyalar? sil
        $menuItem->clearMediaCollection('images');
        $menuItem->clearMediaCollection('gallery');
        
        return $menuItem->delete();
    }
}
```

## 18. Performance Monitoring ve Analytics

### 18.1 Application Performance Monitoring
```php
// config/telescope.php - Development i?in
'enabled' => env('TELESCOPE_ENABLED', true),
'path' => env('TELESCOPE_PATH', 'telescope'),
'driver' => env('TELESCOPE_DRIVER', 'database'),

'watchers' => [
    Watchers\CacheWatcher::class => env('TELESCOPE_CACHE_WATCHER', true),
    Watchers\CommandWatcher::class => env('TELESCOPE_COMMAND_WATCHER', true),
    Watchers\DumpWatcher::class => env('TELESCOPE_DUMP_WATCHER', true),
    Watchers\EventWatcher::class => env('TELESCOPE_EVENT_WATCHER', true),
    Watchers\ExceptionWatcher::class => env('TELESCOPE_EXCEPTION_WATCHER', true),
    Watchers\JobWatcher::class => env('TELESCOPE_JOB_WATCHER', true),
    Watchers\LogWatcher::class => env('TELESCOPE_LOG_WATCHER', true),
    Watchers\MailWatcher::class => env('TELESCOPE_MAIL_WATCHER', true),
    Watchers\ModelWatcher::class => [
        'enabled' => env('TELESCOPE_MODEL_WATCHER', true),
        'hydrations' => true,
    ],
    Watchers\QueryWatcher::class => [
        'enabled' => env('TELESCOPE_QUERY_WATCHER', true),
        'slow' => 100, // milliseconds
    ],
];

// Custom performance tracking middleware
class PerformanceTracker
{
    public function handle($request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $executionTime = ($endTime - $startTime) * 1000; // milliseconds
        $memoryUsage = $endMemory - $startMemory;

        // Log performance metrics
        if ($executionTime > 1000) { // Log if over 1 second
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time' => $executionTime,
                'memory_usage' => $memoryUsage,
                'user_id' => auth()->id()
            ]);
        }

        return $response;
    }
}
```

### 18.2 Database Query Monitoring
```php
// AppServiceProvider.php
public function boot()
{
    if (app()->environment('local')) {
        DB::listen(function ($query) {
            if ($query->time > 100) { // 100ms'den fazla
                Log::warning('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time
                ]);
            }
        });
    }
}

// N+1 query detection
class DetectNPlusOneQueries
{
    public function handle($request, Closure $next)
    {
        if (app()->environment('local')) {
            $queryCount = 0;
            
            DB::listen(function () use (&$queryCount) {
                $queryCount++;
            });

            $response = $next($request);

            if ($queryCount > 10) { // Threshold
                Log::warning('Potential N+1 query detected', [
                    'url' => $request->fullUrl(),
                    'query_count' => $queryCount
                ]);
            }

            return $response;
        }

        return $next($request);
    }
}
```

## 19. Code Quality ve Standards

### 19.1 PHP CS Fixer Configuration
```php
// .php-cs-fixer.php
<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        '@PHP82Migration' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => [
            'statements' => ['return', 'try', 'throw', 'if', 'switch', 'foreach', 'for', 'while'],
        ],
        'cast_spaces' => ['space' => 'none'],
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
                'property' => 'one',
                'trait_import' => 'none',
                'case' => 'none',
            ],
        ],
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => true,
        'function_typehint_space' => true,
        'include' => true,
        'increment_style' => ['style' => 'post'],
        'lowercase_cast' => true,
        'magic_constant_casing' => true,
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'native_function_casing' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra',
                'throw',
                'use',
            ],
        ],
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_mixed_echo_print' => ['use' => 'echo'],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_short_bool_cast' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_spaces_around_offset' => true,
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_unneeded_control_parentheses' => true,
        'no_unused_imports' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'normalize_index_brace' => true,
        'object_operator_without_whitespace' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'phpdoc_align' => true,
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_indent' => true,
        'phpdoc_inline_tag_normalizer' => true,
        'phpdoc_no_access' => true,
        'phpdoc_no_alias_tag' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_no_package' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_return_self_reference' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_summary' => true,
        'phpdoc_to_comment' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_var_without_name' => true,
        'return_type_declaration' => true,
        'semicolon_after_instruction' => true,
        'single_blank_line_before_namespace' => true,
        'single_class_element_per_statement' => true,
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'single_quote' => true,
        'space_after_semicolon' => ['remove_in_empty_for_expressions' => true],
        'standardize_not_equals' => true,
        'ternary_operator_spaces' => true,
        'trailing_comma_in_multiline' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'visibility_required' => true,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->name('*.php')
            ->notName('*.blade.php')
            ->exclude(['bootstrap', 'storage', 'vendor'])
            ->notPath('_ide_helper.php')
            ->notPath('_ide_helper_models.php')
            ->notPath('.phpstorm.meta.php')
    );
```

### 19.2 PHPStan Configuration
```neon
# phpstan.neon
parameters:
    level: 6
    paths:
        - app
        - config
        - database
        - routes
        - tests
    
    excludePaths:
        - app/Console/Kernel.php
        - app/Exceptions/Handler.php
        - app/Http/Kernel.php
        - bootstrap/*
        - storage/*
        - vendor/*
    
    ignoreErrors:
        - '#Call to an undefined method Illuminate\\Database\\Eloquent\\Builder#'
        - '#Cannot call method.*on mixed#'
    
    checkMissingIterableValueType: false
    checkGenericClassInNonGenericObjectType: false
    
    bootstrapFiles:
        - bootstrap/app.php
    
    treatPhpDocTypesAsCertain: false
```

### 19.3 Code Review Checklist
```markdown
## Code Review Checklist

### General
- [ ] Code follows PSR-12 coding standards
- [ ] All methods have appropriate return types
- [ ] No unused imports or variables
- [ ] Proper error handling with try-catch blocks
- [ ] All user inputs are validated
- [ ] Security best practices are followed

### Models
- [ ] Uses appropriate relationships (belongsTo, hasMany, etc.)
- [ ] Fillable fields are defined
- [ ] Proper casts for JSON fields
- [ ] Activity logging is configured if needed
- [ ] Route model binding uses appropriate column

### Controllers
- [ ] Extends BaseController
- [ ] Uses proper authorization (policies)
- [ ] Form Request classes for validation
- [ ] Business logic delegated to Service layer
- [ ] Proper HTTP status codes in responses
- [ ] Error handling with appropriate messages

### Services
- [ ] Single responsibility principle
- [ ] Database transactions for multiple operations
- [ ] Proper error logging
- [ ] Cache invalidation where needed
- [ ] Queue jobs for long-running operations

### Views
- [ ] Proper layout inheritance
- [ ] All text is translatable with __() helper
- [ ] CSRF tokens included in forms
- [ ] XSS protection (escaped output)
- [ ] Responsive design considerations

### Database
- [ ] Migration follows naming conventions
- [ ] Foreign keys with proper constraints
- [ ] Indexes for performance
- [ ] JSON fields for multi-language content
- [ ] Down method properly implemented

### Tests
- [ ] Feature tests for all endpoints
- [ ] Unit tests for business logic
- [ ] Database refresh in test classes
- [ ] Proper test data factories
- [ ] Edge cases covered

### Performance
- [ ] Eager loading to prevent N+1 queries
- [ ] Proper pagination
- [ ] Cache implementation for expensive operations
- [ ] Optimized database queries
- [ ] Image optimization and resizing

### Security
- [ ] Input validation and sanitization
- [ ] Authorization checks
- [ ] HTTPS enforced in production
- [ ] Rate limiting implemented
- [ ] SQL injection prevention
```

## 20. Git ve Version Control Standartlar?

### 20.1 Branch Strategy
```bash
# Main branches
main            # Production ready code
develop         # Integration branch for features
staging         # Staging environment testing

# Feature branches
feature/menu-management
feature/qr-code-generation  
feature/payment-integration
feature/multi-language-support

# Hotfix branches
hotfix/critical-bug-fix
hotfix/security-patch

# Release branches
release/v1.0.0
release/v1.1.0
```

### 20.2 Commit Message Standards
```bash
# Format: <type>(<scope>): <description>
# 
# <body>
# 
# <footer>

# Types:
feat: new feature
fix: bug fix
docs: documentation
style: formatting, missing semicolons, etc.
refactor: code restructuring
test: adding tests
chore: build process or auxiliary tool changes

# Examples:
feat(menu): add menu item image upload functionality
fix(auth): resolve login redirect issue for restaurant owners
docs(api): update API documentation for menu endpoints
style(controllers): fix code formatting according to PSR-12
refactor(services): extract QR code generation to separate service
test(menu): add unit tests for menu item validation
chore(deps): update Laravel to 10.x

# With body and footer:
feat(payment): integrate Stripe payment processing

Add Stripe payment gateway integration for subscription payments.
Includes webhook handling for payment status updates and
automatic subscription activation/deactivation.

Closes #123
BREAKING CHANGE: Payment structure has changed
```

### 20.3 Git Hooks
```bash
#!/bin/sh
# .git/hooks/pre-commit

echo "Running pre-commit checks..."

# Check PHP syntax
find . -name "*.php" -not -path "./vendor/*" -not -path "./node_modules/*" | xargs -I {} php -l {}

if [ $? -ne 0 ]; then
    echo "?R PHP syntax errors found"
    exit 1
fi

# Run PHP CS Fixer
./vendor/bin/php-cs-fixer fix --dry-run --diff

if [ $? -ne 0 ]; then
    echo "?R Code style issues found. Run 'composer fix-style' to fix them."
    exit 1
fi

# Run PHPStan
./vendor/bin/phpstan analyse

if [ $? -ne 0 ]; then
    echo "?R PHPStan analysis failed"
    exit 1
fi

# Run tests
php artisan test

if [ $? -ne 0 ]; then
    echo "?R Tests failed"
    exit 1
fi

echo "?S& All pre-commit checks passed"
```

Bu kapsaml? kodlama standartlar? belgesi, QR Menu SaaS projesi i?in tutarl? ve kaliteli kod yaz?m?n? sa?xlayacakt?r. T?m geli?xtirme s?recinde bu standartlara uyulmas?, projenin bak?m?n? ve geli?xtirilmesini kolayla?xt?racakt?r.