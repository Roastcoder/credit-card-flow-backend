#!/usr/bin/env php
<?php
/**
 * Role Management API Test Script
 * 
 * This script tests all role management endpoints
 * Run: php test-role-api.php
 */

$API_BASE = 'http://localhost:3001';

echo "=== Role Management API Tests ===\n\n";

// Test 1: Get User Role
echo "Test 1: Get User Role\n";
$ch = curl_init("$API_BASE/api/auth/get-role.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['mobile' => '1234567890']));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Test 2: Update User Role
echo "Test 2: Update User Role\n";
$ch = curl_init("$API_BASE/api/auth/update-role.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'mobile' => '1234567890',
    'role' => 'manager'
]));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Test 3: List All Users
echo "Test 3: List All Users\n";
$ch = curl_init("$API_BASE/api/auth/list-users.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status: $httpCode\n";
$data = json_decode($response, true);
if (isset($data['users'])) {
    echo "Total Users: " . count($data['users']) . "\n";
    echo "First User: " . json_encode($data['users'][0] ?? 'None') . "\n\n";
} else {
    echo "Response: $response\n\n";
}

// Test 4: Update Role by User ID
echo "Test 4: Update Role by User ID\n";
$ch = curl_init("$API_BASE/api/permissions/update-role.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'user_id' => 1,
    'role' => 'admin'
]));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status: $httpCode\n";
echo "Response: $response\n\n";

// Test 5: Invalid Role Validation
echo "Test 5: Invalid Role Validation\n";
$ch = curl_init("$API_BASE/api/auth/update-role.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'mobile' => '1234567890',
    'role' => 'invalid_role'
]));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status: $httpCode (should be 400)\n";
echo "Response: $response\n\n";

echo "=== Tests Complete ===\n";
?>
