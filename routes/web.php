<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//AUTH
Auth::routes(['verify' => true]);
Route::get('/', 'HomeController@index')->name('home');
Route::get('/unauthorised', 'UserController@unauthorised')->name('unauthorised');
Route::get('/testmail', 'SuperAdminController@testMail');
Route::get('/notifications', 'UserNotificationsController@show')->name('show-notifications');


//--------ADMIN ------------------

//SUPER ADMIN & ADMIN
        //All Users
    Route::get('/new-user', 'SuperAdminController@createUser')->name('new-user');
    Route::post('/save-user', 'SuperAdminController@storeUser')->name('save-user');
    Route::put('/update-users-role/{user}', 'SuperAdminController@updateUserRole')->name('update-user-role');
    Route::put('/unsuspenduser/{user}', 'SuspensionController@unsuspendUser')->name('unsuspend-user');
    Route::put('/suspenduser/{user}', 'SuspensionController@suspendUser')->name('suspend-user');
    Route::get('/viewuser/{user}', 'UserController@showUser')->name('view-user');
    Route::post('/updatedp/{user}', 'UserController@updateUserDP')->name('updateUserDP');
    Route::put('/updatefull/{user}', 'UserController@fullUserUpdate')->name('updateUser');
    Route::delete('/deleteuser/{user}', 'UserController@destroyUser')->name('deleteUser');
    Route::put('/update-users-dept/{user}', 'AdminController@updateUserDept')->name('updateUser-dept');
    Route::get('/users/{roleName}', 'UserController@indexStaff')->name('indexStaff');
    
    
        //Driver
    Route::get('newdriver', 'DriverController@createDriver')->name('newDriver');
    Route::get('drivers', 'DriverController@indexDriver')->name('indexDrivers');
    Route::put('update-drivers-availability/{driver}', 'DriverController@updateDriverAvailability')->name('updateDriverAvailability');
    Route::put('update-drivers-car/{driver}', 'DriverController@AddCarToDriver')->name('AddCarToDriver');
    Route::post('savedriver', 'DriverController@storeDriver')->name('storeDriver');
    Route::put('update-drivers-dept/{driver}', 'DriverController@updateDriverDept')->name('updateDriverDept');
    Route::post('driverprofile/{driver}', 'DriverController@updateDriverProfile')->name('updateDriverProfile');
    Route::put('fullUpdate/{driver}', 'DriverController@fullUpdate')->name('fullUpdate');
    Route::delete('deletedriver/{driver}', 'DriverController@destroyDriver')->name('destroyDriver');
        //Department
    Route::get('newdepartment', 'DepartmentController@createDepartment')->name('createDepartment');
    Route::post('save-department', 'DepartmentController@storeDepartment')->name('storeDepartment');
    Route::get('departments/{dept}', 'DepartmentController@showDepartment')->name('showDepartment');
        //Car
    Route::get('new-car', 'CarController@createCar')->name('createCar');
    Route::post('save-car', 'CarController@storeCar')->name('storeCar');
    Route::get('cars', 'CarController@indexCar')->name('indexCar');
    Route::get('editcar/{car}', 'CarController@editCar')->name('editCar');
    Route::put('updatecar/{car}', 'CarController@updateCar')->name('updateCar');
    Route::delete('deletecar/{car}', 'CarController@destroyCar')->name('destroyCar');


    //TripS
    Route::get('trips', 'TripController@allTrips')->name('allTrips');
    Route::put('updatetrip/{trip}', 'TripController@updateTrip')->name('updateTrip');
    Route::post('deny/{trip}', 'TripController@denyTrip')->name('denyTrip');
    //Emergency Trips
    Route::get('emertrips', 'EmergencyTripController@index')->name('index');
    Route::post('dismissemergtrip/{trip}', 'EmergencyTripController@dismissEmergencyTrip')->name('dismissEmergencyTrip');
    Route::get('approvetrip/{trip}', 'EmergencyTripController@showApproval')->name('showApproval');
    Route::post('saveapproval/{eTrip}', 'EmergencyTripController@saveApproval')->name('saveApproval');
    Route::post('dismissexemergtrip/{trip}', 'EmergencyTripController@dismissExpiredEmergencyTrip')->name('dismissExpiredEmergencyTrip');
    Route::get('adminemergencytrip', 'EmergencyTripController@createAdminEmergency')->name('createAdminEmergency');
    Route::post('save-admintrip', 'EmergencyTripController@storeAdminEmergency')->name('storeAdminEmergency');
    
    //Reports
    Route::get('reports', 'ReportController@reportsDisplay')->name('reportsDisplay');
    Route::get('carreports', 'ReportController@carReportIndex')->name('carReportIndex');
    Route::get('carreport/{report}', 'ReportController@showCarReport')->name('showCarReport');
    Route::get('driverreports', 'ReportController@driverReportIndex')->name('driverReportIndex');
    Route::get('driverreport/{report}', 'ReportController@showDriverReport')->name('showDriverReport');
    Route::get('tripreports', 'ReportController@tripReportIndex')->name('tripReportIndex');
    Route::get('tripreport/{trip}', 'ReportController@tripReportShow')->name('tripReportShow');


//ACCOUNTANT  AND SUPER ADMIN AND ADMIN   
        //Fuel
    Route::post('fuelcar/{car}', 'FuelController@saveFuelRecord')->name('saveFuelRecord');
    Route::get('fuelrecords', 'FuelController@indexMonthlyRecord')->name('indexMonthlyRecord');
    Route::post('fuel/records/', 'FuelController@fuelRequestRecords')->name('fuelRequestRecords');


//Repair
    Route::post('repair/{car}', 'RepairController@saveRepairRecord')->name('saveRepairRecord');
    Route::get('repairrecords', 'RepairController@indexMonthlyRecord')->name('indexMonthlyRecord');
    Route::post('repairs/records', 'RepairController@repairRequestRecords')->name('repairRequestRecords');


//RECEPTIONIST  AND SUPER ADMIN AND ADMIN   
  
        //Switch Trips
    Route::get('switch', 'TripController@tripSwitch')->name('tripSwitch');
    Route::post('ontrip/{trip}', 'TripController@switchOnTrip')->name('switchOnTrip');
    Route::post('offtrip/{trip}', 'TripController@switchOffTrip')->name('switchOffTrip');



//SUPER ADMIN
            //Admins
    Route::get('new-admin', 'AdminController@createAdmin')->name('new-admin');
    Route::post('save-admin', 'AdminController@storeAdmin')->name('save-admin');



//ALL USERS
Route::get('profile', 'UserController@editSelfUser')->name('switchOffTrip');
Route::put('update-profile', 'UserController@updateSelfUser')->name('switchOffTrip');
Route::post('profile-picture', 'UserController@storeProfilePicture')->name('switchOffTrip');
Route::put('update-password', 'UserController@updateSelfPassword')->name('switchOffTrip');
Route::get('myemergencies', 'EmergencyTripController@myIndex')->name('switchOffTrip');

    //Drivers
Route::get('mydrivers', 'DriverController@myDriver')->name('switchOffTrip');
Route::get('drivers/{driver}', 'DriverController@showDriver')->name('switchOffTrip');

    //Cars
Route::get('mycars', 'CarController@staffIndexCar')->name('staffIndexCar');
Route::get('cars/{car}', 'CarController@showCar')->name('showCar');
Route::get('book-car/{pickedCar}', 'CarController@bookCar')->name('bookCar');
Route::post('/cartrips/{car}', 'CarController@carTripsForRequestRecords')->name('carTripsForRequestRecords');
Route::post('/fuel/{car}/records', 'CarController@carFuelRequestRecords')->name('carFuelRequestRecords');
Route::post('/repair/{car}', 'CarController@carRepairRequestRecords')->name('carRepairRequestRecords');

    //TripS
Route::get('newtrip', 'TripController@create')->name('createTrip');
Route::post('save-trip', 'TripController@store')->name('storeTrip');
Route::post('savetripreport/{trip}', 'TripController@addTripReport');
Route::get('mytrips', 'TripController@index')->name('indexTrip');
Route::post('xtrip/{trip}', 'TripController@cancelTrip')->name('cancelTrip');

    //Emergency Trips
Route::get('/newemergency', 'EmergencyTripController@create')->name('createEmergency');
Route::post('/storeemergency', 'EmergencyTripController@store')->name('storeTrip');

    //Reports
Route::get('reportadriver', 'ReportController@createDriverReport')->name('createDriverReport');
Route::get('carreport', 'ReportController@createCarReport')->name('createCarReport');
Route::post('save-carreport', 'ReportController@storeCarReport')->name('storeCarReport');
Route::post('save-driverreport', 'ReportController@storeDriverReport')->name('storeDriverReport');




//Emails
Route::get('emails', 'SuperAdminController@showEmail')->name('showEmail');
Route::post('/contact', 'SuperAdminController@storeEmail')->name('storeEmail');




