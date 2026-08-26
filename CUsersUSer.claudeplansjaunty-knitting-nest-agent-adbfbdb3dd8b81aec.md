# Role-Based Access Control Implementation Plan

## Overview
This plan outlines the implementation of role-based access control (RBAC) with admin/user roles for the SIM-TIK-KominfoSitubondo application. The implementation will include:

1. Adding a role field to the users table
2. Separating authentication flows for admin and user
3. Restricting server access so users can only see/edit their own servers
4. Maintaining admin access to all servers
5. Updating middleware, controllers, routes, and views accordingly

## Current State Analysis

Based on codebase exploration:

### Database Structure
- Users table migration (`0001_01_01_000000_create_users_table.php`) currently has: id, name, email, email_verified_at, password, remember_token, timestamps
- Servers table (`2026_08_06_105217_create_servers_table.php`) includes a user_id foreign key
- Server model confirms relationship: `public function user() { return $this->belongsTo(User::class); }`

### Authentication System
- Laravel Breeze authentication is implemented
- Routes defined in `routes/auth.php` and `routes/web.php`
- Controllers in `app/Http/Controllers/Auth/`
- Registration handled by `RegisteredUserController`

### Access Control Gaps
- No role field exists on users table
- ServerController index method shows all servers without filtering by user
- No middleware for role-based access control
- Registration doesn't capture role information

## Implementation Steps

### Step 1: Database Migration - Add Role Field to Users Table

Create a new migration to add a role column to the users table with default value 'user' and possible values 'admin' or 'user'.

### Step 2: Update User Model

Add role to fillable array and create helper methods for role checking.

### Step 3: Update Registration Flow

Modify registration to accept role selection (with admin creation restricted) and set appropriate role.

### Step 4: Create Role Middleware

Create middleware to verify user roles for route protection.

### Step 5: Update ServerController

Modify index, show, edit, update, destroy methods to filter servers by user_id for regular users while allowing admins to see all.

### Step 6: Update Routes

Apply middleware to protect admin routes and implement role-based route groups.

### Step 7: Update Views

Modify Blade templates to show/hide UI elements based on user role.

### Step 8: Seeding/Admin Creation

Create initial admin user via seeder or tinker command.

## Detailed File Changes

### 1. Database Migration
File: `database/migrations/[timestamp]_add_role_to_users_table.php`

### 2. User Model Updates
File: `app/Models/User.php`

### 3. Registration Controller Updates
File: `app/Http/Controllers/Auth/RegisteredUserController.php`

### 4. Middleware Creation
File: `app/Http/Middleware/CheckRole.php`

### 5. ServerController Updates
File: `app/Http/Controllers/ServerController.php`

### 6. Route Updates
Files: `routes/web.php`, `routes/auth.php`

### 7. View Updates
Files: Various Blade files in `resources/views/`

### 8. Database Seeder
File: `database/seeders/DatabaseSeeder.php` or create new seeder

## Implementation Details

### Step 1: Add Role Field Migration
```php
Schema::table('users', function (Blueprint $table) {
    $table->enum('role', ['admin', 'user'])->default('user')->after('email');
});
```

### Step 2: Update User Model
```php
protected $fillable = [
    'name',
    'email',
    'role',
    'password',
];

public function isAdmin()
{
    return $this->role === 'admin';
}

public function isUser()
{
    return $this->role === 'user';
}
```

### Step 3: Update Registration
- Add role select to registration form (hidden for regular registration, admin-only creation separate)
- Validate role input
- Set role during user creation

### Step 4: Create CheckRole Middleware
```php
public function handle($request, Closure $next, ...$roles)
{
    if (!auth()->check() || !in_array(auth()->user()->role, $roles)) {
        abort(403);
    }
    return $next($request);
}
```

### Step 5: Update ServerController Methods
```php
public function index(Request $request)
{
    $query = Server::when($request->get('search'), function ($query, $search) {
        return $query->where('nama_perangkat', 'like', "%{$search}%")
            ->orWhere('id', 'like', "%{$search}%")
            ->orWhere('ip_server', 'like', "%{$search}%");
    });
    
    // Filter by user_id for non-admin users
    if (!auth()->user()->isAdmin()) {
        $query->where('user_id', auth()->id());
    }
    
    $servers = $query->paginate($request->get('perPage', 10));
    return view('server', compact('servers'));
}

// Similar filtering for show, edit, update, destroy methods
```

### Step 6: Route Protection
```php
// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin-only routes
});

// User routes  
Route::middleware(['auth', 'role:user'])->group(function () {
    // User-only routes
});

// Shared routes
Route::middleware(['auth'])->group(function () {
    // Routes accessible to both roles
});
```

### Step 7: View Updates
- Show admin-only navigation items conditionally
- Hide edit/delete buttons for servers not owned by user
- Add role indicator in user profile/dashboard

### Step 8: Admin Seeder
```php
User::factory()->create([
    'name' => 'Administrator',
    'email' => 'admin@example.com',
    'role' => 'admin',
    'password' => Hash::make('secure_password'),
]);
```

## Security Considerations
- Prevent privilege escalation by validating role inputs
- Ensure admin routes are properly protected
- Log access attempts for audit trail
- Validate ownership before allowing server modifications
- Use Laravel's built-in authorization features where appropriate

## Testing Plan
1. Test user registration assigns correct role
2. Test admin can access admin-only routes
3. Test user cannot access admin-only routes
4. Test users see only their own servers
5. Test admins see all servers
6. Test server CRUD operations respect ownership
7. Test registration validation
8. Test middleware redirects properly

## Dependencies
- Laravel authentication system
- Existing database relationships
- Current controller and view structure

## Estimated Effort
- Database migration: 1 hour
- Model updates: 30 minutes
- Registration updates: 2 hours
- Middleware creation: 1 hour
- Controller updates: 3 hours
- Route updates: 1 hour
- View updates: 2 hours
- Seeding/admin creation: 30 minutes
- Testing: 2 hours
Total: Approximately 12-14 hours

## Risks and Mitigation
- Risk: Breaking existing user functionality
  Mitigation: Backward compatible changes, thorough testing
- Risk: Incorrect role assignment
  Mitigation: Validation, default to 'user' role
- Risk: Performance impact from additional WHERE clauses
  Mitigation: Proper indexing on role and user_id columns
