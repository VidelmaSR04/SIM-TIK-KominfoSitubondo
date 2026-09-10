#!/bin/bash

set -x

BASE_URL="http://127.0.0.1:8000"
COOKIE_JAR="cookies_submit.txt"

rm -f $COOKIE_JAR

# Step 1: Get login page to get token for login
echo "Step 1: Get login page"
LOGIN_PAGE=$(curl -s -c $COOKIE_JAR "$BASE_URL/login")
if [ $? -ne 0 ]; then
  echo "Failed to get login page"
  exit 1
fi
LOGIN_TOKEN=$(echo "$LOGIN_PAGE" | grep -oP '(?<=name="_token" value=")[^"]*' | head -1)
if [ -z "$LOGIN_TOKEN" ]; then
  echo "Failed to extract login token"
  echo "$LOGIN_PAGE" > login_page_debug.html
  exit 1
fi
echo "Login token: $LOGIN_TOKEN"

# Step 2: Login
echo "Step 2: Login"
LOGIN_DATA="_token=$LOGIN_TOKEN&email=testuser@example.com&password=password"
LOGIN_RESPONSE=$(curl -s -b $COOKIE_JAR -c $COOKIE_JAR -d "$LOGIN_DATA" -X POST "$BASE_URL/login")
if [ $? -ne 0 ]; then
  echo "Failed to login"
  exit 1
fi
# Check if login succeeded by looking for redirect to inputdatauser in headers or by following redirect
# We'll just get the cookies and try to access inputdatauser

# Step 3: Get the form page to get form token
echo "Step 3: Get form page"
FORM_PAGE=$(curl -s -b $COOKIE_JAR "$BASE_URL/inputdatauser")
if [ $? -ne 0 ]; then
  echo "Failed to get form page"
  exit 1
fi
FORM_TOKEN=$(echo "$FORM_PAGE" | grep -oP '(?<=name="_token" value=")[^"]*' | head -1)
if [ -z "$FORM_TOKEN" ]; then
  echo "Failed to extract form token"
  echo "$FORM_PAGE" > form_page_debug.html
  exit 1
fi
echo "Form token: $FORM_TOKEN"

# Step 4: Submit the form
echo "Step 4: Submit form"
SUBMIT_DATA="_token=$FORM_TOKEN&nama_pengirim=Test%20Pengirim&opd=Dinas%20Pendidikan%20dan%20Kebudayaan&nama_penerima=Test%20Penerima&jenis_perangkat=router&merk_perangkat=MIKROTIK&tanggal_input=$(date +%Y-%m-%d)"
echo "Submit data: $SUBMIT_DATA"
SUBMIT_RESPONSE=$(curl -s -b $COOKIE_JAR -c $COOKIE_JAR -d "$SUBMIT_DATA" -X POST "$BASE_URL/inputdatauser")
if [ $? -ne 0 ]; then
  echo "Failed to submit form"
  exit 1
fi
echo "Submit response received, length: ${#SUBMIT_RESPONSE}"

# Check if submit response is a redirect (by looking for Location header in the response headers)
# We didn't capture headers, so let's do a separate request to capture headers
echo "Step 5: Check for redirect"
REDIRECT_RESPONSE=$(curl -s -b $COOKIE_JAR -i -d "$SUBMIT_DATA" -X POST "$BASE_URL/inputdatauser")
echo "$REDIRECT_RESPONSE" > redirect_response.txt
REDIRECT_URL=$(echo "$REDIRECT_RESPONSE" | grep -i "^Location:" | head -1 | cut -d' ' -f2 | tr -d '\r')
if [ -z "$REDIRECT_URL" ]; then
  echo "No Location header found in response"
  # Maybe the response is JSON or the redirect is in the body
  # Let's check the response body for a redirect message
  # We'll just assume it redirected to dashboarduser and check that
  REDIRECT_URL="/dashboarduser"
else
  echo "Redirect URL: $REDIRECT_URL"
fi

# Follow the redirect
echo "Step 6: Follow redirect"
FOLLOW_RESPONSE=$(curl -s -b $COOKIE_JAR "$BASE_URL$REDIRECT_URL")
if [ $? -ne 0 ]; then
  echo "Failed to follow redirect"
  exit 1
fi
echo "Follow response received, length: ${##FOLLOW_RESPONSE}"

# Check for success message
if echo "$FOLLOW_RESPONSE" | grep -q "Perangkat berhasil didaftarkan"; then
  echo "SUCCESS: Success message found in redirect page"
else
  echo "FAILURE: Success message NOT found in redirect page"
  # Save the follow response for debugging
  echo "$FOLLOW_RESPONSE" > follow_response_debug.html
fi

# Step 7: Check the database for the inserted server (via tinker)
echo "Step 7: Check database"
LATEST_SERVER_ID=$(cd /c/laragon/www/SIM-TIK-KominfoSitubondo && php -r "
require __DIR__.'/vendor/autoload.php';
\$app = require_once __DIR__.'/bootstrap/app.php';
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
}")
if [ "$LATEST_SERVER_ID" -gt 0 ]; then
  echo "Server found in database with ID: $LATEST_SERVER_ID"
else
  echo "No server found in database for the test user"
fi

# Get the server details
if [ "$LATEST_SERVER_ID" -gt 0 ]; then
  SERVER_DETAILS=$(cd /c/laragon/www/SIM-TIK-KominfoSitubondo && php -r "
require __DIR__.'/vendor/autoload.php';
\$app = require_once __DIR__.'/bootstrap/app.php';
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

echo "Test completed."