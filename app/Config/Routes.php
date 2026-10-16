<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Router\RouteCollection;

$routes->get('/test', function() {
    return "Routes are working!";
});

$routes->get('/', 'AuthController::login');

$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::processRegister');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::processLogin');
$routes->get('logout', 'AuthController::logout');
$routes->post('logout', 'AuthController::logout');
$routes->get('forgot-password', 'AuthController::forgotPassword');
$routes->post('forgot-password', 'AuthController::processForgotPassword');
$routes->get('reset-password/(:any)', 'AuthController::resetPassword/$1');
$routes->post('reset-password', 'AuthController::processResetPassword');

$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('itso-dashboard', 'ItsoDashboard::index', ['filter' => 'auth']);

$routes->get('profile-picture/(:any)', 'AuthController::getProfilePicture/$1');
$routes->get('profile-picture/thumbnail/(:any)', 'AuthController::getProfilePicture/$1/true');

$routes->group('itso', ['filter' => 'auth'], function($routes) {
    
    $routes->get('equipment', 'ItsoDashboard::equipment');
    $routes->get('equipment/add', 'ItsoDashboard::addEquipment');
    $routes->post('equipment/save', 'ItsoDashboard::saveEquipment');
    $routes->get('equipment/edit/(:num)', 'ItsoDashboard::editEquipment/$1');
    $routes->post('equipment/update/(:num)', 'ItsoDashboard::updateEquipment/$1');
    $routes->get('equipment/view/(:num)', 'ItsoDashboard::viewEquipment/$1');
    $routes->get('equipment/toggle-status/(:num)', 'ItsoDashboard::toggleEquipmentStatus/$1');
    $routes->get('equipment/delete/(:num)', 'ItsoDashboard::deleteEquipment/$1');
    $routes->get('about_us', 'ItsoDashboard::about');

    $routes->get('borrow-requests', 'ItsoDashboard::borrowRequests');
    $routes->post('borrow-requests/approve/(:num)', 'ItsoDashboard::approveRequest/$1');
    $routes->post('borrow-requests/reject/(:num)', 'ItsoDashboard::rejectRequest/$1');
    $routes->post('borrow-requests/complete/(:num)', 'ItsoDashboard::completeRequest/$1');
    
    $routes->get('users', 'ItsoDashboard::users');
    $routes->get('users/edit/(:num)', 'ItsoDashboard::editUser/$1');
    $routes->put('users/update/(:num)', 'ItsoDashboard::updateUser/$1');
    $routes->post('users/update/(:num)', 'ItsoDashboard::updateUser/$1');
    
    $routes->post('users/toggle-status/(:num)', 'ItsoDashboard::toggleUserStatus/$1');
    $routes->get('users/toggle-status/(:num)', 'ItsoDashboard::toggleUserStatus/$1');
    
    $routes->get('returned_equipment', 'ItsoDashboard::returnedEquipment');

    $routes->get('reports', 'ItsoDashboard::reports');

    $routes->get('generate-active-equipment-report', 'ItsoDashboard::generateActiveEquipmentReport');
    $routes->get('generate-unusable-equipment-report', 'ItsoDashboard::generateUnusableEquipmentReport');
    $routes->get('generate-borrowing-history-report', 'ItsoDashboard::generateBorrowingHistoryReport');
    
    $routes->get('settings', 'ItsoDashboard::settings');
});

$routes->group('associate', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'AssociateDashboard::index');
    $routes->get('equipment', 'AssociateDashboard::equipment');
    $routes->post('equipment/borrow', 'AssociateDashboard::borrowEquipment');
    $routes->post('equipment/return', 'AssociateDashboard::returnEquipment');
    $routes->get('reservations', 'AssociateDashboard::reservations');
    $routes->get('reservations/new', 'AssociateDashboard::newReservation');
    $routes->post('reservations/submit', 'AssociateDashboard::submitBorrowRequest');
    $routes->get('reservations/cancel/(:num)', 'AssociateDashboard::cancelReservation/$1');
    
    $routes->get('about_us', 'AssociateDashboard::about_us');
});

$routes->group('student', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'StudentDashboard::index');
    $routes->get('equipment', 'StudentDashboard::equipment');
    $routes->post('equipment/borrow', 'StudentDashboard::borrowEquipment');
    $routes->post('equipment/return', 'StudentDashboard::returnEquipment');
    $routes->get('reservations', 'StudentDashboard::reservations');
    $routes->get('reservations/new', 'StudentDashboard::newReservation');
    $routes->post('reservations/submit', 'StudentDashboard::submitBorrowRequest');
    $routes->get('reservations/cancel/(:num)', 'StudentDashboard::cancelReservation/$1');
});

$routes->get('(:any)', 'AuthController::login');