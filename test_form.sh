#!/bin/bash

# Base URL
BASE_URL="http://127.0.0.1:8000"

# Cookie jar
COOKIE_JAR="cookies.txt"

# Clean up cookie jar
rm -f $COOKIE_JAR

echo "=== Step 1: Get login page and CSRF token ==="
LOGIN_PAGE=$(curl -s -c $COOKIE_JAR "$BASE_URL/login")
if [ $? -ne 0 ]; then
  echo "Failed to get login page"
  exit 1
fi

# Extract token
TOKEN=$(echo "$LOGIN_PAGE" | grep -o '_token" value="[^"]*"' | head -1 | cut -d'"' -f3)
if [ -z "$TOKEN" ]; then
  echo "Failed to extract CSRF token from login page"
  # Save the page for debugging
  echo "$LOGIN_PAGE" > login_page_debug.html
  exit 1
fi
echo "CSRF token: $TOKEN"

echo ""
echo "=== Step 2: Login as test user ==="
LOGIN_DATA="_token=$TOKEN&email=testuser@example.com&password=password"
LOGIN_RESPONSE=$(curl -s -b $COOKIE_JAR -c $COOKIE_JAR -d "$LOGIN_DATA" -X POST "$BASE_URL/login")
if [ $? -ne 0 ]; then
  echo "Failed to login"
  exit 1
fi

# Check if login was successful by looking for redirect to dashboard or presence of user name
# We'll check the response for a redirect or we can follow the redirect and check the content
# Let's check the HTTP status code in the response headers (we didn't capture them)
# Instead, we can check if we got a redirect to /dashboarduser
# We'll make a request to the dashboard and see if we get a 200
# But first, let's see the login response
# We'll save it for debugging
echo "$LOGIN_RESPONSE" > login_response_debug.html

# Now, let's get the dashboard to see if we are logged in
DASHBOARD_RESPONSE=$(curl -s -b $COOKIE_JAR "$BASE_URL/dashboarduser")
if [ $? -ne 0 ]; then
  echo "Failed to get dashboard after login"
  exit 1
fi

# Check if the dashboard contains the user's name (we don't know the name, but we know it's "Test User")
if echo "$DASHBOARD_RESPONSE" | grep -q "Test User"; then
  echo "Login successful: Found 'Test User' in dashboard"
else
  echo "Login may have failed: 'Test User' not found in dashboard"
  # Save the dashboard for debugging
  echo "$DASHBOARD_RESPONSE" > dashboard_debug.html
fi

echo ""
echo "=== Step 3: Get the inputdatauser form to get CSRF token for form ==="
FORM_PAGE=$(curl -s -b $COOKIE_JAR "$BASE_URL/inputdatauser")
if [ $? -ne 0 ]; then
  echo "Failed to get inputdatauser form"
  exit 1
fi

FORM_TOKEN=$(echo "$FORM_PAGE" | grep -o '_token" value="[^"]*"' | head -1 | cut -d'"' -f3)
if [ -z "$FORM_TOKEN" ]; then
  echo "Failed to extract CSRF token from form"
  echo "$FORM_PAGE" > form_page_debug.html
  exit 1
fi
echo "Form CSRF token: $FORM_TOKEN"

echo ""
echo "=== Step 4: Submit the form with dummy data ==="
# We'll use the same OPD as in the test user's login? We'll use a static OPD from the list
SUBMIT_DATA="_token=$FORM_TOKEN&nama_pengirim=Test%20Pengirim&opd=Dinas%20Pendidikan%20dan%20Kebudayaan&nama_penerima=Test%20Penerima&jenis_perangkat=router&merk_perangkat=MIKROTIK&tanggal_input=$(date +%Y-%m-%d)"
SUBMIT_RESPONSE=$(curl -s -b $COOKIE_JAR -c $COOKIE_JAR -d "$SUBMIT_DATA" -X POST "$BASE_URL/inputdatauser")
if [ $? -ne 0 ]; then
  echo "Failed to submit form"
  exit 1
fi

# Check for redirect to dashboarduser (status 302 or 303) and then follow it
# We can check the response headers by using -i to include headers
# Let's do a separate request to see the redirect
# But we can also check the response body for a redirect script or we can follow the redirect
# We'll follow the redirect and see what we get
REDIRECT_URL=$(echo "$SUBMIT_RESPONSE" | grep -o 'Location: [^ ]*' | cut -d' ' -f2 | tr -d '\r')
if [ -z "$REDIRECT_URL" ]; then
  # Maybe the response is JSON or the redirect is in the body
  # Let's check the response body for a redirect message
  echo "$SUBMIT_RESPONSE" > submit_response_debug.html
  echo "No Location header found in response. Checking body for redirect..."
  # We'll just assume it redirected to dashboarduser and check that
  REDIRECT_URL="/dashboarduser"
fi

echo "Redirect URL: $REDIRECT_URL"
FOLLOW_RESPONSE=$(curl -s -b $COOKIE_JAR "$BASE_URL$REDIRECT_URL")
if [ $? -ne 0 ]; then
  echo "Failed to follow redirect"
  exit 1
fi

# Check for success message
if echo "$FOLLOW_RESPONSE" | grep -q "Perangkat berhasil didaftarkan"; then
  echo "Success message found in redirect page"
else
  echo "Success message NOT found in redirect page"
  # Save the follow response for debugging
  echo "$FOLLOW_RESPONSE" > follow_response_debug.html
fi

echo ""
echo "=== Step 5: Check the database for the inserted server (via tinker) ==="
# We'll use php artisan tinker to get the latest server for the test user
# We need to run this in the project directory
# We'll run a PHP one-liner
LATEST_SERVER_ID=$(cd /laragon/www/SIM-TIK-KominfoSitubondo && php -r "
require __DIR__.'/bootstrap/app.php';
\$app = Illuminate\Foundation\Application::create(__DIR__);
\$app->singleton(Illuminate\Contracts\Http\Kernel::class, App\Http\Kernel::class);
\$app->singleton(Illuminate\Contracts\Console\Kernel::class, App\Console\Kernel::class);
\$app->singleton(Illuminate\Contracts\Debug\ExceptionHandler::class, App\Exceptions\Handler::class);
\$app->boot();
use App\Models\User;
use App\Models\Server;
\$user = User::whereEmail('testuser@example.com')->first();
if (\$user) {
  \$server = Server::where('user_id', \$user->id)->latest()->first();
  if (\$server) {
    echo \$server->id;
  } else {
    echo '0';
  }
} else {
  echo '0';
}
")
if [ "$LATEST_SERVER_ID" -gt 0 ]; then
  echo "Server found in database with ID: $LATEST_SERVER_ID"
else
  echo "No server found in database for the test user"
fi

# Get the server details
if [ "$LATEST_SERVER_ID" -gt 0 ]; then
  SERVER_DETAILS=$(cd /laragon/www/SIM-TIK-KominfoSitubondo && php -r "
require __DIR__.'/bootstrap/app.php';
\$app = Illuminate\Foundation\Application::create(__DIR__);
\$app->singleton(Illuminate\Contracts\Http\Kernel::class, App\Http\Kernel::class);
\$app->singleton(Illuminate\Contracts\Console\Kernel::class, App\Console\Kernel::class);
\$app->singleton(Illuminate\Contracts\Debug\ExceptionHandler::class, App\Exceptions\Handler::class);
\$app->boot();
use App\Models\Server;
\$server = Server::find($LATEST_SERVER_ID);
if (\$server) {
  echo 'ID: ' . \$server->id . PHP_EOL;
  echo 'Nama pengirim: ' . \$server->nama_pengirim . PHP_EOL;
  echo 'OPD: ' . \$server->pemilik_perangkat . PHP_EOL;
  echo 'Nama penerima: ' . \$server->nama_penerima . PHP_EOL;
  echo 'Jenis perangkat: ' . \$server->jenis_perangkat . PHP_EOL;
  echo 'Merk perangkat: ' . \$server->merk_perangkat . PHP_EOL;
  echo 'Tanggal input: ' . \$server->tanggal_input . PHP_EOL;
  echo 'Status kelengkapan: ' . \$server->status_kelengkapan . PHP_EOL;
} else {
  echo 'Server not found';
}
")
  echo "Server details:"
  echo "$SERVER_DETAILS"
fi

echo ""
echo "=== Step 6: Login as admin and check admin manajemen-server page ==="
# First, logout from current session (by clearing cookies) and login as admin
rm -f $COOKIE_JAR
ADMIN_LOGIN_PAGE=$(curl -s -c $COOKIE_JAR "$BASE_URL/login")
ADMIN_TOKEN=$(echo "$ADMIN_LOGIN_PAGE" | grep -o '_token" value="[^"]*"' | head -1 | cut -d'"' -f3)
ADMIN_LOGIN_DATA="_token=$ADMIN_TOKEN&email=testadmin@example.com&password=password"
ADMIN_LOGIN_RESPONSE=$(curl -s -b $COOKIE_JAR -c $COOKIE_JAR -d "$ADMIN_LOGIN_DATA" -X POST "$BASE_URL/login")
# Now get the admin manajemen-server page
ADMIN_PAGE=$(curl -s -b $COOKIE_JAR "$BASE_URL/admin/manajemen-server")
if [ $? -ne 0 ]; then
  echo "Failed to get admin manajemen-server page"
else
  # Check if the server data appears in the admin page
  if [ "$LATEST_SERVER_ID" -gt 0 ]; then
    # We'll look for the nama_pengirim or the server ID in the page
    if echo "$ADMIN_PAGE" | grep -q "Test Pengirim"; then
      echo "Server data found in admin manajemen-server page"
    else
      echo "Server data NOT found in admin manajemen-server page"
      # Save the admin page for debugging
      echo "$ADMIN_PAGE" > admin_page_debug.html
    fi
  else
    echo "No server to check in admin page"
  fi
fi

echo ""
echo "=== Step 7: Check the admin lengkapi page for the server ==="
if [ "$LATEST_SERVER_ID" -gt 0 ]; then
  LENGKAPI_URL="$BASE_URL/server/$LATEST_SERVER_ID/lengkapi"
  LENGKAPI_PAGE=$(curl -s -b $COOKIE_JAR "$LENGKAPI_URL")
  if [ $? -ne 0 ]; then
    echo "Failed to get lengkapi page"
  else
    # Check if the form is pre-filled with the dummy data
    # We'll look for the nama_pengirim value in the input field
    if echo "$LENGKAPI_PAGE" | grep -q 'value="Test Pengirim"'; then
      echo "Nama pengirim pre-filled correctly in lengkapi page"
    else
      echo "Nama pengirim NOT pre-filled correctly in lengkapi page"
      # Save the lengkapi page for debugging
      echo "$LENGKAPI_PAGE" > lengkapi_page_debug.html
    fi
    # Similarly, we can check other fields, but we'll just check one for brevity
  fi
else
  echo "No server to check in lengkapi page"
fi

echo ""
echo "=== Test completed ==="
# Clean up cookie jar
rm -f $COOKIE_JAR