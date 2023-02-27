<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubAdminController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\GateKeeperController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NoticeBoardController;
use App\Http\Controllers\PreApproveEntryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\StreetController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\BuildingResidentController;
use App\Http\Controllers\FamilyMemberController;
use App\Http\Controllers\ChatRoomController;
use App\Http\Controllers\ChatRoomUserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\SocietyBuildingController;
use App\Http\Controllers\SocietyBuildingApartmentController;
use App\Http\Controllers\SocietyBuildingFloorController;

use Illuminate\Support\Facades\Artisan;


Route::middleware(['auth:sanctum'])->group(function(){



Route::post('society/addsociety', [SocietyController::class, 'addsociety']);
Route::put('society/updatesociety', [SocietyController::class, 'updatesociety']);
Route::get('society/viewallsocieties/{userid}', [SocietyController::class, 'viewallsocieties']);
Route::get('society/deletesociety/{id}', [SocietyController::class, 'deletesociety']);
Route::get('society/viewsociety/{societyid}', [SocietyController::class, 'viewsociety']);
Route::get('society/searchsociety/{q?}', [SocietyController::class, 'searchsociety']);
Route::get('society/filtersocietybuilding/{id}/{q?}', [SocietyController::class, 'filtersocietybuilding']);
Route::get('society/viewsocietiesforresidents/{type?}', [SocietyController::class, 'viewsocietiesforresidents']);
// Route::get('society/viewbuildingsforresidents', [SocietyController::class, 'viewbuildingsforresidents']);
    //User
    Route::post('logout',[RoleController::class,'logout']);
    Route::post('fcmtokenrefresh',[RoleController::class,'fcmtokenrefresh']);
    Route::post('resetpassword',[RoleController::class,'resetpassword']);
    // SubAdmin
    Route::post('registersubadmin',[SubAdminController::class,'registersubadmin']);
    Route::get('viewsubadmin/{id}',[SubAdminController::class,'viewsubadmin']);
    Route::get('deletesubadmin/{id}',[SubAdminController::class,'deletesubadmin']);
    Route::post('updatesubadmin',[SubAdminController::class,'updatesubadmin']);
    // Residents
    Route::post('registerresident',[ResidentController::class,'registerresident']);
    Route::get('viewresidents/{id}',[ResidentController::class,'viewresidents']);
    Route::get('deleteresident/{id}',[ResidentController::class,'deleteresident']);
    Route::get('searchresident/{subadminid}/{q?}',[ResidentController::class,'searchresident']);
    Route::post('updateresident',[ResidentController::class,'updateresident']);
    Route::get('loginresidentdetails/{residentid}',[ResidentController::class,'loginresidentdetails']);
    Route::get('unverifiedhouseresident/{subadminid}/{status}',[ResidentController::class,'unverifiedhouseresident']);
    Route::get('unverifiedapartmentresident/{subadminid}/{status}',[ResidentController::class,'unverifiedapartmentresident']);
    Route::post('loginresidentupdateaddress',[ResidentController::class,'loginresidentupdateaddress']);
    Route::post('verifyhouseresident',[ResidentController::class,'verifyhouseresident']);
    Route::post('verifyapartmentresident',[ResidentController::class,'verifyapartmentresident']);


    // GateKeeper
  Route::post('registergatekeeper', [GateKeeperController::class, 'registergatekeeper']);
  Route::get('viewgatekeepers/{id}', [GateKeeperController::class, 'viewgatekeepers']);
  Route::get('deletegatekeeper/{id}', [GateKeeperController::class, 'deletegatekeeper']);
  Route::post('updategatekeeper', [GateKeeperController::class, 'updategatekeeper']);


  //Events

    Route::post('event/addevent',[EventController::class,'addevent']);
    Route::post('event/addeventimages',[EventController::class,'addeventimages']);
    Route::post('event/updateevent',[EventController::class,'updateevent']);
    Route::get('event/events/{userid}',[EventController::class,'events']);
    Route::get('event/deleteevent/{id}',[EventController::class,'deleteevent']);
    Route::get('event/searchevent/{userid}/{q?}',[EventController::class,'searchevent']);



//Notice Board
Route::post('addnoticeboarddetail', [NoticeBoardController::class, 'addnoticeboarddetail']);
Route::get('viewallnotices/{id}', [NoticeBoardController::class, 'viewallnotices']);
Route::get('deletenotice/{id}', [NoticeBoardController::class, 'deletenotice']);
Route::post('updatenotice', [NoticeBoardController::class, 'updatenotice']);


// Reports
Route::post('reporttoadmin', [ReportController::class, 'reporttoadmin']);
Route::get('adminreports/{residentid}', [ReportController::class, 'adminreports']);
Route::post('updatereportstatus', [ReportController::class, 'updatereportstatus']);
Route::get('deletereport/{id}', [ReportController::class, 'deletereport']);
Route::get('reportedresidents/{subadminid}', [ReportController::class, 'reportedresidents']);
Route::get('reports/{subadminid}/{userid}', [ReportController::class, 'reports']);
Route::get('pendingreports/{subadminid}', [ReportController::class, 'pendingreports']);
Route::get('historyreportedresidents/{subadminid}', [ReportController::class, 'historyreportedresidents']);
Route::get('historyreports/{subadminid}/{userid}', [ReportController::class, 'historyreports']);


// Preapproveentry
Route::get('getgatekeepers/{subadminid}', [PreApproveEntryController::class, 'getgatekeepers']);
Route::get('getvisitorstypes', [PreApproveEntryController::class, 'getvisitorstypes']);
Route::post('addvisitorstypes', [PreApproveEntryController::class, 'addvisitorstypes']);
Route::post('addpreapproventry', [PreApproveEntryController::class, 'addpreapproventry']);
Route::post('updatepreapproveentrystatus', [PreApproveEntryController::class, 'updatepreapproveentrystatus']);
Route::post('updatepreapproveentrycheckoutstatus', [PreApproveEntryController::class, 'updatepreapproveentrycheckoutstatus']);
Route::get('viewpreapproveentryreports/{userid}', [PreApproveEntryController::class, 'viewpreapproveentryreports']);
Route::get('preapproveentryresidents/{userid}', [PreApproveEntryController::class, 'preapproveentryresidents']);
Route::get('preapproventrynotifications/{userid}', [PreApproveEntryController::class, 'preapproventrynotifications']);
Route::get('preapproveentries/{userid}', [PreApproveEntryController::class, 'preapproveentries']);
Route::get('preapproveentryhistories/{userid}', [PreApproveEntryController::class, 'preapproveentryhistories']);




// Phases
Route::post('addphases', [PhaseController::class, 'addphases']);
Route::get('phases/{subadminid}', [PhaseController::class, 'phases']);
Route::get('distinctphases/{subadminid}', [PhaseController::class, 'distinctphases']);
Route::get('viewphasesforresidents/{societyid}', [PhaseController::class, 'viewphasesforresidents']);

// Blocks
Route::post('addblocks', [BlockController::class, 'addblocks']);
Route::get('blocks/{pid}', [BlockController::class, 'blocks']);
Route::get('distinctblocks/{bid}', [BlockController::class, 'distinctblocks']);
Route::get('viewblocksforresidents/{phaseid}', [BlockController::class, 'viewblocksforresidents']);


Route::get('viewblocksforresidents/{phaseid}', [BlockController::class, 'viewblocksforresidents']);
// Streets
Route::post('addstreets', [StreetController::class, 'addstreets']);
Route::get('streets/{bid}', [StreetController::class, 'streets']);
Route::get('viewstreetsforresidents/{blockid}', [StreetController::class, 'viewstreetsforresidents']);

// Property
Route::post('addproperties', [PropertyController::class, 'addproperties']);
Route::get('properties/{sid}', [PropertyController::class, 'properties']);
Route::get('viewpropertiesforresidents/{streetid}', [PropertyController::class, 'viewpropertiesforresidents']);


   
// Society Building

Route::post('addsocietybuilding', [SocietyBuildingController::class, 'addsocietybuilding']);
Route::get('societybuildings/{pid}', [SocietyBuildingController::class, 'societybuildings']);


Route::post('addsocietybuildingfloors', [SocietyBuildingFloorController::class, 'addsocietybuildingfloors']);
Route::get('viewsocietybuildingfloors/{buildingid}', [SocietyBuildingFloorController::class, 'viewsocietybuildingfloors']);
Route::get('societybuildingfloor/{subadminid}', [SocietyBuildingFloorController::class, 'societybuildingfloor']);



Route::post('addsocietybuildingapartments', [SocietyBuildingApartmentController::class, 'addsocietybuildingapartments']);
Route::get('viewsocietybuildingapartments/{buildingid}', [SocietyBuildingApartmentController::class, 'viewsocietybuildingapartments']);
Route::get('apartments/{fid}', [SocietyBuildingApartmentController::class, 'apartments']);




  // Family Members

  Route::post('addfamilymember',[FamilyMemberController::class,'addfamilymember']);
  Route::get('viewfamilymember/{subadminid}/{residentid}',[FamilyMemberController::class,'viewfamilymember']);

  Route::get('fire',[RoleController::class,'fire']);


   //Chatroom
   Route::post('createchatroom',[ChatRoomController::class,'createchatroom']);


   //Chatroomuser
//    fetchchatroomusers

   Route::get('fetchchatroomusers/{userid}/{chatuserid}',[ChatRoomUserController::class,'fetchchatroomusers']);


  //Chats
  Route::post('conversations',[ChatController::class,'conversations']);
  Route::get('chatneighbours/{subadminid}',[ChatController::class,'chatneighbours']);
  Route::get('chatgatekeepers/{subadminid}',[ChatController::class,'chatgatekeepers']);
  Route::get('viewconversationsneighbours/{chatroomid}',[ChatController::class,'viewconversationsneighbours']);


  // Measurements

  Route::post('addmeasurement',[MeasurementController::class,'addmeasurement']);
  Route::get('housesapartmentmeasurements/{subadminid}/{type}', [MeasurementController::class, 'housesapartmentmeasurements']);

  //Bills
  Route::post('generatebill',[BillController::class,'generatebill']);
  Route::get('generatedbill/{subadminid}',[BillController::class,'generatedbill']);


});




// Authentications

Route::post('login',[RoleController::class,'login']);
Route::post('residentlogin',[ResidentController::class,'residentlogin']);
Route::post('registeruser',[RoleController::class,'registeruser']);
Route::get('allusers',[RoleController::class,'allusers']);


Route::get('clear_cache', function () {

    Artisan::call('cache:clear');

    // dd("Cache is cleared");

});






