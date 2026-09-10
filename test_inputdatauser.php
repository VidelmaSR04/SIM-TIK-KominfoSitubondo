<?php
// Bootstrap Laravel
require __DIR__.'/bootstrap/app.php';
$app = Illuminate\Foundation\Application::create(__DIR__);
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);
$app->boot();

// Test starts here
use App\Models\User;
use App\Models\Server;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

// Step 1: Get test users
$user = User::whereEmail('testuser@example.com')->first();
$admin = User::whereEmail('testadmin@example.com')->first();

if (!$user || !$admin) {
    echo "Test users not found. Please create them first." . PHP_EOL;
    exit(1);
}

echo "Regular user ID: {$user->id}" . PHP_EOL;
echo "Admin user ID: {$admin->id}" . PHP_EOL;

// Step 2: Act as regular user and submit form
// Start a session to get CSRF token
Session::start();
$token = Session::token();

// Dummy data
$data = [
    'nama_pengirim' => 'Test Pengirim',
    'opd' => 'Dinas Pendidikan dan Kebudayaan',
    'nama_penerima' => 'Test Penerima',
    'jenis_perangkat' => 'router',
    'merk_perangkat' => 'MIKROTIK',
    'tanggal_input' => date('Y-m-d'),
    '_token' => $token,
];

// Login the user
Auth::login($user);

// Create a POST request to the route
$request = Request::create('/inputdatauser', 'POST', $data);

// Handle the request
/** @var \Illuminate\Contracts\Http\Kernel $kernel */
$kernel = app(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request);

// Get the response content
$content = $response->getContent();
$status = $response->getStatusCode();
$headers = $response->headers;

// Check for redirect
if ($status == 302 || $status == 303) {
    $redirectTo = $headers->get('Location');
    echo "Redirect to: {$redirectTo}" . PHP_EOL;
    // Follow the redirect to get the success message
    // We can make a new request to the redirect URL
    $redirectRequest = Request::create($redirectTo, 'GET');
    $redirectResponse = $kernel->handle($redirectRequest);
    $redirectContent = $redirectResponse->getContent();
    // Check for success message in the session or in the content
    // We can check the session flash data
    // But we need to get the session from the request
    // Alternatively, we can check if the content contains the success string
    if (strpos($redirectContent, 'Perangkat berhasil didaftarkan') !== false) {
        echo "Success message found in redirect page." . PHP_EOL;
    } else {
        echo "Success message NOT found in redirect page." . PHP_EOL;
        // Let's save the content for inspection
        file_put_contents('redirect_content.html', $redirectContent);
        echo "Redirect content saved to redirect_content.html" . PHP_EOL;
    }
} else {
    echo "Unexpected status code: {$status}" . PHP_EOL;
    echo "Content: {$content}" . PHP_EOL;
}

// Step 3: Check the database for the inserted server
// We'll get the latest server created by this user
$server = Server::where('user_id', $user->id)->latest()->first();
if ($server) {
    echo "Server found. ID: {$server->id}" . PHP_EOL;
    echo "Status kelengkapan: {$server->status_kelengkapan}" . PHP_EOL;
    echo "Nama pengirim: {$server->nama_pengirim}" . PHP_EOL;
    echo "OPD: {$server->pemilik_perangkat}" . PHP_EOL;
    echo "Nama penerima: {$server->nama_penerima}" . PHP_EOL;
    echo "Jenis perangkat: {$server->jenis_perangkat}" . PHP_EOL;
    echo "Merk perangkat: {$server->merk_perangkat}" . PHP_EOL;
    echo "Tanggal input: {$server->tanggal_input}" . PHP_EOL;
    if ($server->status_kelengkapan === 'pending') {
        echo "Status kelengkapan is 'pending' as expected." . PHP_EOL;
    } else {
        echo "Status kelengkapan is NOT 'pending' (got: {$server->status_kelengkapan})" . PHP_EOL;
    }
} else {
    echo "No server found for the user." . PHP_EOL;
}

// Step 4: Act as admin and check the admin server index
Auth::login($admin);
$adminRequest = Request::create('/admin/manajemen-server', 'GET');
$adminResponse = $kernel->handle($adminRequest);
$adminContent = $adminResponse->getContent();
$adminStatus = $adminResponse->getStatusCode();

if ($adminStatus === 200) {
    // Check if the server data appears in the content
    // We'll look for the nama_pengirim or other fields
    if (strpos($adminContent, 'Test Pengirim') !== false) {
        echo "Server data found in admin manajemen-server page." . PHP_EOL;
    } else {
        echo "Server data NOT found in admin manajemen-server page." . PHP_EOL;
        // Save content for inspection
        file_put_contents('admin_content.html', $adminContent);
        echo "Admin content saved to admin_content.html" . PHP_EOL;
    }
} else {
    echo "Failed to access admin manajemen-server page. Status: {$adminStatus}" . PHP_EOL;
}

// Step 5: Check the admin lengkapi page for the server
if ($server) {
    $lengkapiUrl = "/server/{$server->id}/lengkapi";
    $lengkapiRequest = Request::create($lengkapiUrl, 'GET');
    $lengkapiResponse = $kernel->handle($lengkapiRequest);
    $lengkapiContent = $lengkapiResponse->getContent();
    $lengkapiStatus = $lengkapiResponse->getStatusCode();

    if ($lengkapiStatus === 200) {
        // Check if the form is pre-filled with the dummy data
        // We can check for the values in the input fields
        // For simplicity, we'll check if the nama_pengirim value appears in the content
        if (strpos($lengkapiContent, 'value="Test Pengirim"') !== false) {
            echo "Nama pengirim pre-filled correctly in lengkapi page." . PHP_EOL;
        } else {
            echo "Nama pengirim NOT pre-filled correctly in lengkapi page." . PHP_EOL;
        }
        // Similarly for other fields, but we'll just check one for brevity
        // We'll also check that the status is pending (should be disabled or readonly? Not sure)
        // We'll just note that we can see the data.
    } else {
        echo "Failed to access lengkapi page. Status: {$lengkapiStatus}" . PHP_EOL;
    }
}

// Step 6: Cleanup (optional)
// We'll delete the test server and test users
// Uncomment if you want to clean up
/*
$server->delete();
$user->delete();
$admin->delete();
echo "Test data cleaned up." . PHP_EOL;
*/

echo "Test completed." . PHP_EOL;