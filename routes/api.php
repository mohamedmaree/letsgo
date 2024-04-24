<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


	
//** Start AuthController**//
Route::group( ['middleware'=>['UpdateLang'],'namespace' => 'API'], function() {

    //
    Route::any('check-publish','SettingController@checkPublish');
    Route::any('countries','SettingController@countries');
    Route::any('getCountryCities','SettingController@getCountryCities');
    Route::any('years','UserController@years');
    
    // Route::any('signUpPhoneCity','AuthController@signUpPhoneCity');
    // Route::any('signUpEmail'    ,'AuthController@signUpEmail');
    // Route::any('signUpPassword' ,'AuthController@signUpPassword');
    // Route::any('signUpName'     ,'AuthController@signUpName');

    Route::any('userSignIn','AuthController@userSignIn');
    Route::any('checkUserSignInSocial','AuthController@checkUserSignInSocial');
    Route::any('captainSignIn' ,'AuthController@captainSignIn');

    Route::post('captainSignupPhone', 'AuthController@captainSignupPhone');
    Route::post('captainSignupPassword', 'AuthController@captainSignupPassword');
    Route::post('captainSignup1', 'AuthController@captainSignup1');
    Route::post('captainSignup2', 'AuthController@captainSignup2');
    Route::post('captainSignup3', 'AuthController@captainSignup3');
    Route::post('captainSignup4', 'AuthController@captainSignup4');


    Route::any('forgetPassword'    ,'AuthController@forgetPassword');
    Route::any('resetPassword'    ,'AuthController@resetPassword');
    Route::any('about_app'    ,'HomeController@about_app');
    Route::any('terms'      ,'HomeController@terms');
    Route::any('privacy'      ,'HomeController@privacy');
    Route::any('socials'    ,'ContactUsController@socials');
    Route::any('clientHomeAds'  ,'HomeController@clientHomeAds');
    Route::any('offers','HomeController@offers');
    Route::any('captain-offers','HomeController@captainOffers');
    Route::any('helpPages','HomeController@helpPages');
   
    Route::any('clientRewards','HomeController@clientRewards');
    Route::any('captainRewards','HomeController@captainRewards');
    Route::any('guarantees','HomeController@guarantees');
    Route::any('captainRewardsGuarantees','HomeController@captainRewardsGuarantees');
    Route::any('cashBack','HomeController@cashBack');
    Route::any('supportPhone','HomeController@supportPhone');
    
});
//** End AuthController**//

Route::group(['middleware' => ['jwt.verify','UpdateLang','currencyExchange'] ,'namespace' => 'API'], function() {
    Route::any('packages','UserController@packages');

    //start stc services
        Route::any('stcSendRequest', 'PaymentsController@stcSendRequest');     
        Route::any('stcResult', 'PaymentsController@stcResult');  
        Route::any('stcPayout', 'PaymentsController@stcPayout');     
        Route::any('userPayments','PaymentsController@userPayments');
        Route::any('userStcPhones','PaymentsController@userStcPhones');
        //end stc services

        Route::any('subscribePackage', 'UserController@subscribePackage');     
        Route::any('cancelPackage', 'UserController@cancelPackage');     
        
        Route::any('saveCreditCard', 'UserController@saveCreditCard');     
        Route::any('payment_status', 'PaymentsController@payment_status');     
        
        Route::any('nearResturants','HomeController@nearResturants');
        Route::any('searchResturants','HomeController@searchResturants');
        Route::any('placeDetails','HomeController@placeDetails');
        Route::any('storeDetails','HomeController@storeDetails');

        Route::any('clientSignUp','AuthController@clientSignUp');
        
        Route::any('conversation','MessageController@conversation');
        Route::any('conversation/create','MessageController@create');
        Route::any('uploadFile','MessageController@uploadFile');
       //home 
        Route::any('captainCars'     ,'HomeController@captainCars');
        Route::any('captainCarDetails'     ,'HomeController@captainCarDetails');
        Route::any('chooseCaptainCar','HomeController@chooseCaptainCar');

        Route::any('accountActivation','AuthController@accountActivation');
        Route::any('sendActivation'   ,'AuthController@sendActivation');
        Route::any('logout'           ,'AuthController@logout');
        Route::any('updateUserlocale','UserController@updateUserlocale');
        Route::any('deviceData','UserController@deviceData');
        Route::any('updateDeviceData','UserController@updateDeviceData');
        Route::any('updateDeviceId','UserController@updateDeviceId');
        Route::any('checkUserStatus','UserController@checkUserStatus');

        Route::any('getNotifications','NotificationsController@getNotifications');
        Route::any('numNotifications','NotificationsController@numNotifications');
        Route::any('deleteNotification','NotificationsController@deleteNotification');

        //user
        Route::any('myProfile'       ,'UserController@myProfile');
        Route::any('checkBalance'     ,'UserController@checkBalance');
        
        Route::any('editProfile'     ,'UserController@editProfile');
        Route::any('replacePoints'   ,'UserController@replacePoints');
        Route::any('inviteClientBalance'   ,'UserController@inviteClientBalance');
        Route::any('myPoints'        ,'UserController@myPoints');
        Route::any('CaptainPerformance','UserController@CaptainPerformance');
        Route::any('userArchive'     ,'UserController@userArchive');
        Route::any('changeAvailable' ,'UserController@changeAvailable');
        Route::any('updateDistance' ,'UserController@updateDistance');
        
        Route::any('useBalanceFirst' ,'UserController@useBalanceFirst');
        Route::any('changePassword'  ,'UserController@changePassword');
        Route::any('ratingUser'      ,'UserController@ratingUser');
        Route::any('profileComments' ,'UserController@profileComments');
        Route::any('addCoupon'       ,'UserController@addCoupon');
        Route::any('addChargeCard'   ,'UserController@addChargeCard');
        Route::any('YourBalance'     ,'UserController@YourBalance');
        Route::any('transferMoney'   ,'UserController@transferMoney');
        
        Route::any('nearstores','UserController@nearstores'); 
        Route::any('searchStores','UserController@searchStores'); 
        Route::any('savePlace'     ,'UserController@savePlace');
        Route::any('savedPlaces'     ,'UserController@savedPlaces');
        Route::any('deleteSavedplace','UserController@deleteSavedplace');
        //tickets
        Route::any('createTicket','TicketsController@createTicket');
        Route::any('userTickets','TicketsController@userTickets');
        Route::any('userTicket','TicketsController@userTicket');
        Route::any('cancelTicket','TicketsController@cancelTicket');
        
        //orders
        Route::any('expectedTime','ClientOrderController@expectedTime');
        Route::any('expectedTimeNearestCar','ClientOrderController@expectedTimeNearestCar');
        
        Route::any('carTypesFilter','HomeController@carTypesFilter');
        Route::any('carTypes','ClientOrderController@carTypes');
        Route::any('carType','ClientOrderController@carType');
        Route::any('getExpectedDistancePriceTime','ClientOrderController@getExpectedDistancePriceTime');
        Route::any('UserCreateOrder','ClientOrderController@UserCreateOrder');
        Route::any('UserCreateFoodOrder','ClientOrderController@UserCreateFoodOrder');
        Route::any('reNotifyCaptains','ClientOrderController@reNotifyCaptains');
        
        // Route::any('CaptainCreateOrder','CaptainOrderController@CaptainCreateOrder');
        Route::any('CaptainNearOrders','CaptainOrderController@CaptainNearOrders');
        Route::any('orderDetails','ClientOrderController@orderDetails');
        Route::any('clientFinishedOrderDetails','ClientOrderController@clientFinishedOrderDetails');
        Route::any('CaptainFinishedOrderDetails','CaptainOrderController@CaptainFinishedOrderDetails');
        Route::any('CaptainAcceptOrder','CaptainOrderController@CaptainAcceptOrder');
        Route::any('ClientViewAcceptedOrderCaptain','ClientOrderController@ClientViewAcceptedOrderCaptain');        
        // Route::any('CaptainApplyBid','CaptainOrderController@CaptainApplyBid');
        // Route::any('ClientViewOrderBids','ClientOrderController@ClientViewOrderBids');
        // Route::any('ClientAgreeOrderBid','ClientOrderController@ClientAgreeOrderBid');
        Route::any('CaptainInWayToOrderClient','CaptainOrderController@CaptainInWayToOrderClient');
        Route::any('CaptainArrivedToOrderClient','CaptainOrderController@CaptainArrivedToOrderClient');
        Route::any('CaptainStartJourney','CaptainOrderController@CaptainStartJourney');
        // Route::any('CaptainStartSingleJoinedJourney','CaptainOrderController@CaptainStartSingleJoinedJourney');
        Route::any('CaptainWithdrawOrder','CaptainOrderController@CaptainWithdrawOrder');
        // Route::any('CaptainCancelClientFromJoinedOrder','CaptainOrderController@CaptainCancelClientFromJoinedOrder');
        Route::any('ClientCancelOrder','ClientOrderController@ClientCancelOrder');
        // Route::any('ClientCancelJoinOrder','ClientOrderController@ClientCancelJoinOrder');
        Route::any('CaptainFinishSimpleOrder','CaptainOrderController@CaptainFinishSimpleOrder');
        //to finish orders that comde from externel api
        Route::any('CaptainFinishOrder','CaptainOrderController@CaptainFinishOrder');

        Route::any('CaptainSimpleOrderPriceDetails','CaptainOrderController@CaptainSimpleOrderPriceDetails');
        Route::any('CaptainConfirmFinishSimpleOrder','CaptainOrderController@CaptainConfirmFinishSimpleOrder');
        // Route::any('CaptainFinishMultiUsersOrderForAll','CaptainOrderController@CaptainFinishMultiUsersOrderForAll');
        // Route::any('CaptainFinishMultiUsersOrder','CaptainOrderController@CaptainFinishMultiUsersOrder');
        // Route::any('CaptainMultiUsersOrderPriceDetails','CaptainOrderController@CaptainMultiUsersOrderPriceDetails');
        // Route::any('CaptainConfirmFinishMultiUsersOrder','CaptainOrderController@CaptainConfirmFinishMultiUsersOrder');
        // Route::any('captainFinishedNopaymentOrderClients','CaptainOrderController@captainFinishedNopaymentOrderClients');

        Route::any('ClientShowTotalOrderPrice','ClientOrderController@ClientShowTotalOrderPrice');
        Route::any('ClientChangeOrderPaymentType','ClientOrderController@ClientChangeOrderPaymentType');
        Route::any('ClientLaterOrders','ClientOrderController@ClientLaterOrders');
        Route::any('ClientLaterOrder','ClientOrderController@ClientLaterOrder');
        Route::any('clientCurrentOrder','ClientOrderController@clientCurrentOrder');
        
        
        Route::any('cancelReasons'   ,'ClientOrderController@cancelReasons');
        Route::any('UpdateClientLaterOrder','ClientOrderController@UpdateClientLaterOrder');
        Route::any('CaptainLaterOrders','CaptainOrderController@CaptainLaterOrders');
        Route::any('UpdateCaptainLaterOrder','CaptainOrderController@UpdateCaptainLaterOrder');
        
        // Route::any('ClientNearOrders','ClientOrderController@ClientNearOrders');
        // Route::any('ClientJoinOrder','ClientOrderController@ClientJoinOrder');
        // Route::any('ClientRemoteDistanationOrders','ClientOrderController@ClientRemoteDistanationOrders');
        
        // Route::any('CaptainViewOrderJoins','CaptainOrderController@CaptainViewOrderJoins');
        
        // Route::any('CaptainAcceptJoinedOrder','CaptainOrderController@CaptainAcceptJoinedOrder');
        // Route::any('CaptainInWayToJoinedClient','CaptainOrderController@CaptainInWayToJoinedClient');
        // Route::any('CaptainRefuseJoinedClient','CaptainOrderController@CaptainRefuseJoinedClient');
        // Route::any('CaptainArrivedToJoinedClient','CaptainOrderController@CaptainArrivedToJoinedClient');
        
        //contacts
        Route::any('createContact','ContactUsController@createContact');
        Route::any('contactways','ContactUsController@contactways');
        Route::any('remove-account'     ,'UserController@removeAccount');
        // *** HyperPay *** //
        Route::any('/payment-ways', 'PaymentsController@paymentWays');
        Route::any('/hyper-index', 'PaymentsController@hyperIndex');
        Route::any('/hyper-result', 'PaymentsController@hyperResult');
        Route::any('/captain-hyper-index', 'PaymentsController@captainHyperIndex');
        Route::any('/captain-hyper-result', 'PaymentsController@captainHyperResult');
        // *** HyperPay *** //
});