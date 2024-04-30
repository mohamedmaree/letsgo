<?php

Route::get('/', 'WelcomePage\WelcomePageController@index');
/*---------------------------------Start Of FrontEnd--------------------------*/
   Route::get('getCitiesByCountry/{country_id?}', 'CityController@getCitiesByCountry')->name('getCitiesByCountry');
   Route::get('conditions', 'PagesController@conditions')->name('conditions');
   Route::get('privacy', 'PagesController@privacy')->name('privacy');
   Route::get('about-us', 'PagesController@aboutApp')->name('aboutApp');
   Route::get('contact-us', 'PagesController@contactUs')->name('contactUs');
   
    Route::get('captainPerformance/{userid?}/{page?}/{lang?}'      ,'UsersController@captainPerformance')->name('captainPerformance');
	Route::get('captainAvailableTimes/{userid?}/{page?}/{lang?}'   ,'UsersController@captainAvailableTimes');
	Route::get('captainOrdersRatings/{userid?}/{page?}/{lang?}'    ,'UsersController@captainOrdersRatings');	
	Route::get('captainOrdersAcceptance/{userid?}/{page?}/{lang?}' ,'UsersController@captainOrdersAcceptance');	
	Route::get('captainShowOrder/{order_id?}/{lang?}'                    ,'OrderController@captainShowOrder');

   Route::get('guideVideo', 'UsersController@guideVideo')->name('guideVideo');
   Route::get('captainSignupForm/{share_code?}', 'UsersController@captainSignupForm')->name('captainSignupForm');
   Route::post('captainSignup', 'UsersController@captainSignup')->name('captainSignup');
   Route::get('captainSignupForm2', 'UsersController@captainSignupForm2')->name('captainSignupForm2');
   Route::post('captainSignup2', 'UsersController@captainSignup2')->name('captainSignup2');
   Route::get('captainSignupForm3', 'UsersController@captainSignupForm3')->name('captainSignupForm3');
   Route::post('captainSignup3', 'UsersController@captainSignup3')->name('captainSignup3');
   Route::get('captainSignupForm4', 'UsersController@captainSignupForm4')->name('captainSignupForm4');
   Route::post('captainSignup4', 'UsersController@captainSignup4')->name('captainSignup4');

   Route::post('sendCode', 'UsersController@sendCode')->name('sendCode');
   Route::get('userCode/{phone?}', 'UsersController@userCode')->name('userCode');
   Route::get('codeVerfication', 'UsersController@codeVerfication')->name('codeVerfication');

   //payment
Route::get('payment/{user_id?}/{package_id?}', 'PaymentsController@payment')->name('payment');
Route::post('sendPaymentCode', 'PaymentsController@sendPaymentCode')->name('sendPaymentCode');
Route::get('paymentUserCode/{phone?}', 'PaymentsController@paymentUserCode')->name('paymentUserCode');
Route::get('paymentCodeVerfication', 'PaymentsController@paymentCodeVerfication')->name('paymentCodeVerfication');

//hyperpay
Route::get('/hyper-index/{id}',[
    'uses'=>'PaymentsController@hyperIndex',
    'as'=>'hyperIndex'
]);

Route::get('/visa-index/{id}',[
    'uses'=>'PaymentsController@visaIndex',
    'as'=>'visaIndex'
]);

Route::get('/visa-result',[
    'uses'=>'PaymentsController@visaResult',
    'as'=>'visaResult'
]);

Route::get('/mada-index/{id}',[
    'uses'=>'PaymentsController@madaIndex',
    'as'=>'madaIndex'
]);

Route::get('/mada-result',[
    'uses'=>'PaymentsController@madaResult',
    'as'=>'madaResult'
]);

Route::get('/apple-index/{id}',[
    'uses'=>'PaymentsController@appleTransferBalance',
    'as'=>'appleTransferBalance'
]);

Route::get('/apple-result',[
    'uses'=>'PaymentsController@appleTransferBalanceResult',
    'as'=>'appleTransferBalanceResult'
]);

Route::get('/stc-index/{id}',[
    'uses'=>'PaymentsController@stcIndex',
    'as'=>'stcIndex'
]);

Route::get('/stc-result',[
    'uses'=>'PaymentsController@stcResult',
    'as'=>'stcResult'
]);

Route::get('/paymentSuccess',[
    'uses'=>'PaymentsController@paymentSuccess',
    'as'=>'paymentSuccess'
]);

    //home
	Route::get('home/{lat?}/{lng?}', 'HomeController@index');
Route::group(['middleware' => ['auth']], function () {
  // Route::get('logout','UsersController@logout');
    Route::post('transferBalance', 'PaymentsController@transferBalance')->name('transferBalance');
    Route::get('visaTransferBalance/{amount}' ,'PaymentsController@visaTransferBalance')->name('visaTransferBalance');
    Route::get('visaTransferBalanceResult', 'PaymentsController@visaTransferBalanceResult')->name('visaTransferBalanceResult');
    Route::get('madaTransferBalance/{amount}' ,'PaymentsController@madaTransferBalance')->name('madaTransferBalance');
    Route::get('madaTransferBalanceResult', 'PaymentsController@madaTransferBalanceResult')->name('madaTransferBalanceResult');
    Route::get('stcTransferBalance/{amount}' ,'PaymentsController@stcTransferBalance')->name('stcTransferBalance');
    Route::get('stcTransferBalanceResult', 'PaymentsController@stcTransferBalanceResult')->name('stcTransferBalanceResult');
   
    //profile
    Route::post('updateProfile','UsersController@updateProfile')->name('updateProfile');
    Route::post('changePassword','UsersController@changePassword')->name('changePassword');
  
});

/*---------------------------------End Of FrontEnd--------------------------*/



/*---------------------------------Start Of DashBoard--------------------------*/

Route::group(['prefix'=>'admin','middleware'=>['UserAuth','Admin','checkRole']],function(){

	/*Start Of DashBoard Controller (Intro Page)*/
	Route::get('dashboard',[
		'uses'  =>'DashBoardController@Index',
		'as'    =>'dashboard',
		'title' =>'الرئيسيه',
		'icon'  =>'<i class="icon-home4"></i>',
		]);
		Route::get('mapNotifications',[
			'uses'  =>'DashBoardController@mapNotifications',
			'as'    =>'mapNotifications',
			'icon'  =>'<i class="icon-map"></i>',
			'title' =>'اشعارات الخريطة',
			'child' =>[
						   'sendMapNotifications',
					]		
			]);
		Route::post('sendMapNotifications',[
			'uses' =>'DashBoardController@sendMapNotifications',
			'as'   =>'sendMapNotifications',
			'title'=>'ارسال اشعارات حسب الخريطة'
		]);

		#tracking center
		Route::get('trackingCenterOnjobCaptains',[
			'uses'=>'DashBoardController@trackingCenterOnjobCaptains',
			'as'  =>'trackingCenterOnjobCaptains',
			'title'=>'مركز عمليات التتبع',
			'icon'  =>'<i class="glyphicon glyphicon-search"></i>',
			'child'=>[
			   'getOnjobCaptainsLocation',
			   'trackingCenterOfflineCaptains',
			   'getOfflineCaptainsLocation',
			   'trackingCenterAvailableCaptains',
			   'getAvailableCaptainsLocation',
			   'trackingCenterTocustomerCaptains',
			   'getTocustomerCaptainsLocation',
			   'searchDriver',
			   'getCurrentCaptainLocation',
			   'getNearformOrderAvailableCaptainsLocation',
			]
		]);
	
		Route::get('getOnjobCaptainsLocation',[
			'uses' =>'DashBoardController@getOnjobCaptainsLocation',
			'as'   =>'getOnjobCaptainsLocation',
			'title'=>'تحديث احداثيات القادة فى رحلة'
		]);
		Route::get('trackingCenterOfflineCaptains',[
			'uses' =>'DashBoardController@trackingCenterOfflineCaptains',
			'as'   =>'trackingCenterOfflineCaptains',
			'title'=>'تتبع القادة غير متاحين'
		]);
		Route::get('getOfflineCaptainsLocation',[
			'uses' =>'DashBoardController@getOfflineCaptainsLocation',
			'as'   =>'getOfflineCaptainsLocation',
			'title'=>'تحديث احداثيات القادة الغير متاحين'
		]);
		Route::get('trackingCenterAvailableCaptains',[
			'uses' =>'DashBoardController@trackingCenterAvailableCaptains',
			'as'   =>'trackingCenterAvailableCaptains',
			'title'=>'تتبع القادة المتاحين'
		]);
		Route::get('getAvailableCaptainsLocation',[
			'uses' =>'DashBoardController@getAvailableCaptainsLocation',
			'as'   =>'getAvailableCaptainsLocation',
			'title'=>'تحديث احداثيات القادة المتاحين'
		]);
	
		Route::get('getNearformOrderAvailableCaptainsLocation/{order_id?}/{types?}',[
			'uses' =>'OrderController@getNearformOrderAvailableCaptainsLocation',
			'as'   =>'getNearformOrderAvailableCaptainsLocation',
			'title'=>'تحديث احداثيات القادة المتاحين بالقرب من الرحلة'
		]);
	
		Route::get('trackingCenterTocustomerCaptains',[
			'uses' =>'DashBoardController@trackingCenterTocustomerCaptains',
			'as'   =>'trackingCenterTocustomerCaptains',
			'title'=>'تتبع القادة الي العميل'
		]);
		Route::get('getTocustomerCaptainsLocation',[
			'uses' =>'DashBoardController@getTocustomerCaptainsLocation',
			'as'   =>'getTocustomerCaptainsLocation',
			'title'=>'تحديث احداثيات القادة الي العميل'
		]);
	
		Route::post('searchDriver',[
			'uses' =>'DashBoardController@searchDriver',
			'as'   =>'searchDriver',
			'title'=>'البحث عن قائد'
		]);
		Route::get('getCurrentCaptainLocation/{captain_id?}',[
			'uses' =>'DashBoardController@getCurrentCaptainLocation',
			'as'   =>'getCurrentCaptainLocation',
			'title'=>'تحديث احداثيات القائد'
		]);
	
		/*------------ start Of CallCenterController ----------*/
		Route::get('callCenter',[
			'uses'=>'CallCenterController@callCenter',
			'as'  =>'callCenter',
			'title'=>'مركز الكول سنتر',
			'icon'  =>'<i class="glyphicon glyphicon-search"></i>',
			'child'=>[
			   'searchCaptainsAndOrders',
			   'searchCaptainsAndOrdersAjax',
			   'notifyOrderToCaptain',
			]
		]);
		Route::post('searchCaptainsAndOrders',[
			'uses' =>'CallCenterController@searchCaptainsAndOrders',
			'as'   =>'searchCaptainsAndOrders',
			'title'=>'البحث عن القادة والرحلات'
		]);
		Route::post('searchCaptainsAndOrdersAjax',[
			'uses' =>'CallCenterController@searchCaptainsAndOrdersAjax',
			'as'   =>'searchCaptainsAndOrdersAjax',
			'title'=>'تحديث البحث عن القادة والرحلات'
		]);	
		Route::post('notifyOrderToCaptain',[
			'uses'  =>'CallCenterController@notifyOrderToCaptain',
			'as'    =>'notifyOrderToCaptain',
			'title' =>'اشعار الكابتن بوجود رحلة'
		]);	
		/*------------ End Of CallCenterController ----------*/
	/*------------ start Of UsersController ----------*/
	Route::get('addUserMeta',[
		'uses'=>'UsersController@addUserMeta',
		'as'  =>'addUserMeta',
		'title'=>'اضافة طلب العمل كقائد',
		'icon'  =>'<i class="icon-user"></i>',
		'child' =>[
				'storeUserMeta',
			]		
		]);

	Route::post('storeUserMeta',[
		'uses' =>'UsersController@storeUserMeta',
		'as'   =>'storeUserMeta',
		'title'=>'حفظ اضافة طلب العمل كقائد'
	]);

	Route::get('captainsAddedByAmbassador/{id?}',[
		'uses' =>'UsersController@captainsAddedByAmbassador',
		'as'   =>'captainsAddedByAmbassador',
		'title'=>'القادة المضافين بواسطتك',
		'icon' =>'<i class="fa fa-group"></i>',
	]);
	

	#users list
	Route::get('allusers',[
		'uses' =>'UsersController@allUsers',
		'as'   =>'allusers',
		'title'=>'الاعضاء', 
		'icon' =>'<i class="icon-vcard"></i>',
		'subTitle'=>'الكل',
        'subIcon' =>'<i class="glyphicon glyphicon-cog"></i>',		
		'child'=>[
		    'downloadAllUsers',
		    'clients',
		    'downloadClients',
		    'providers',
		    'downloadProviders',
		    'manualBlockedProviders',
		    'autoBlockedProviders',
			
			'onlineProviders',
			'offlineProviders',
			
		    'supervisiors',
		    'downloadSupervisiors',	
			'ambassadors',
		    'downloadAmbassadors',
			'reviewers',
		    'downloadReviewers',
			'reviewerAgreedUsersMeta',
		    'reviewerRefusedUsersMeta',			    
			// 'completeUsersMeta',
		    'uncompleteUsersMeta',
			'downloadUncompleteUserMeta',
			'emailUncompleteUsersMeta',
			'SmsMessageUncompleteUsersMeta',
			'notificationUncompleteUsersMeta',

			'pendingUsersMeta',
		    'agreedUsersMeta',
		    'refusedUsersMeta',
		    'userMeta',
			'editUserMeta',
		    'updateUserMeta',
		    'agreeUserMeta',
			'refuseUserMeta',
		    'deleteUserMeta',
			'adduser',
			'userProfile',
			'userPerformance',
			'finishCaptainWeekMoney',
			'captainMoneyHistory',
			'downloadCaptainMoneyArchive',
			'userAvailableTimes',
			'userOrdersRatings',
			'ordersAcceptance',
			'updateuser',
			'delete-user',
			'deleteUsers',
			'emailAllusers',
			'emailClients',
			'emailCaptains',
			'emailSupervisiors',

			'captainCars',
			'createCaptainCar',
			'updateCaptainCar',
			'DeleteCaptainCar',
			'userOrdersArchive',
			'comments',
			'deleteComment',
			'adminUserPayments',
			'downloadadminUserPayments',
			'adminUserPaymentsElectronic',
			'downloadadminUserPaymentsElectronic',			
			'SmsMessageAll',
			'SmsMessageClients',
			'SmsMessageProviders',
			'SmsMessageSupervisiors',
			'notificationAllUsers',
			'notificationClients',
			'notificationProviders',
			'notificationSupervisiors',
			'currentUserEmail',
			'currentUserSms',
			'currentUserNotification',
			'adminAddCoupon',
			'admincreateBlock',
			'admincancelBlock'
		]
	]);

	Route::get('downloadAllUsers',[
		'uses'=>'UsersController@downloadAllUsers',
		'as'  =>'downloadAllUsers',
		'title'=>'تحميل بيانات كل الأعضاء'
	]);
	Route::get('clients',[
		'uses' =>'UsersController@Users',
		'as'   =>'clients',
		'title'=>'العملاء',
		'icon' =>'<i class="glyphicon glyphicon-user"></i>',
        'hasFather' => true
	]);
	Route::get('downloadClients',[
		'uses'=>'UsersController@downloadClients',
		'as'  =>'downloadClients',
		'title'=>'تحميل بيانات العملاء'
	]);
	Route::get('providers',[
		'uses' =>'UsersController@Providers',
		'as'   =>'providers',
		'title'=>'القاده',
		'icon' =>'<i class="fa fa-car"></i>',
        'hasFather' => true
	]);
	Route::get('downloadProviders',[
		'uses'=>'UsersController@downloadProviders',
		'as'  =>'downloadProviders',
		'title'=>'تحميل بيانات القاده'
	]);
	Route::get('manualBlockedProviders',[
		'uses' =>'UsersController@manualBlockedProviders',
		'as'   =>'manualBlockedProviders',
		'title'=>'القادة المحظورين يدويا',
		'icon' =>'<i class="fa fa-lock"></i>',
        'hasFather' => true
	]);

	Route::get('autoBlockedProviders',[
		'uses' =>'UsersController@autoBlockedProviders',
		'as'   =>'autoBlockedProviders',
		'title'=>'القادة المحظورين تلقائياً',
		'icon' =>'<i class="fa fa-lock"></i>',
        'hasFather' => true
	]);

	Route::get('onlineProviders',[
		'uses' =>'UsersController@onlineProviders',
		'as'   =>'onlineProviders',
		'title'=>'القادة المتصلين',
		'icon' =>'<i class="fa fa-car"></i>',
        'hasFather' => true
	]);
	Route::get('offlineProviders',[
		'uses' =>'UsersController@offlineProviders',
		'as'   =>'offlineProviders',
		'title'=>'القادة الغير متصلين',
		'icon' =>'<i class="fa fa-car"></i>',
        'hasFather' => true
	]);


	Route::get('supervisiors',[
		'uses' =>'UsersController@supervisiors',
		'as'   =>'supervisiors',
		'title'=>'ادارة التطبيق',
		'icon' =>'<i class="glyphicon glyphicon-pencil"></i>',
        'hasFather' => true
	]);
	Route::get('downloadSupervisiors',[
		'uses'=>'UsersController@downloadSupervisiors',
		'as'  =>'downloadSupervisiors',
		'title'=>'تحميل بيانات ادراة التطبيق'
	]);	
	
	Route::get('reviewers',[
		'uses' =>'UsersController@reviewers',
		'as'   =>'reviewers',
		'title'=>'المراجعين',
		'icon' =>'<i class="glyphicon glyphicon-pencil"></i>',
        'hasFather' => true
	]);
	Route::get('downloadReviewers',[
		'uses'=>'UsersController@downloadReviewers',
		'as'  =>'downloadReviewers',
		'title'=>'تحميل بيانات المراجعين'
	]);	

	Route::get('ambassadors',[
		'uses' =>'UsersController@ambassadors',
		'as'   =>'ambassadors',
		'title'=>'سفراء القادة',
		'icon' =>'<i class="glyphicon glyphicon-pencil"></i>',
        'hasFather' => true
	]);
	Route::get('downloadAmbassadors',[
		'uses'=>'UsersController@downloadAmbassadors',
		'as'  =>'downloadAmbassadors',
		'title'=>'تحميل بيانات السفراء'
	]);	

	Route::get('completeUsersMeta',[
		'uses' =>'UsersController@completeUsersMeta',
		'as'   =>'completeUsersMeta',
		'title'=>'طلبات العمل كقائد مكتملة',
		'icon' =>'<i class="glyphicon glyphicon-file"></i>',
        'hasFather' => true
	]);

	Route::get('uncompleteUsersMeta',[
		'uses' =>'UsersController@uncompleteUsersMeta',
		'as'   =>'uncompleteUsersMeta',
		'title'=>'طلبات العمل كقائد غير مكتملة',
		'icon' =>'<i class="glyphicon glyphicon-file"></i>',
        'hasFather' => true
	]);

	Route::get('downloadUncompleteUserMeta',[
		'uses' =>'UsersController@downloadUncompleteUserMeta',
		'as'   =>'downloadUncompleteUserMeta',
		'title'=>'تحميل طلبات العمل كقائد غير مكتملة',
	]);

	Route::post('emailUncompleteUsersMeta',[
		'uses' =>'UsersController@emailUncompleteUsersMeta',
		'as'   =>'emailUncompleteUsersMeta',
		'title'=>'ارسال email لطلبات العمل الغير مكتمله'
	]);	
	Route::post('SmsMessageUncompleteUsersMeta',[
		'uses' =>'UsersController@SmsMessageUncompleteUsersMeta',
		'as'   =>'SmsMessageUncompleteUsersMeta',
		'title'=>'ارسال sms لطلبات العمل الغير مكتملة'
	]);

	Route::post('notificationUncompleteUsersMeta',[
		'uses' =>'UsersController@notificationUncompleteUsersMeta',
		'as'   =>'notificationUncompleteUsersMeta',
		'title'=>'ارسال notification لطلبات العمل الغير مكتمله'
	]);

	Route::get('pendingUsersMeta',[
		'uses' =>'UsersController@pendingUsersMeta',
		'as'   =>'pendingUsersMeta',
		'title'=>'طلبات العمل كقائد المعلقة',
		'icon' =>'<i class="glyphicon glyphicon-file"></i>',
        'hasFather' => true
	]);

	Route::get('agreedUsersMeta',[
		'uses' =>'UsersController@agreedUsersMeta',
		'as'   =>'agreedUsersMeta',
		'title'=>'طلبات العمل كقائد المقبولة',
		'icon' =>'<i class="glyphicon glyphicon-file"></i>',
        'hasFather' => true
	]);

	Route::get('refusedUsersMeta',[
		'uses' =>'UsersController@refusedUsersMeta',
		'as'   =>'refusedUsersMeta',
		'title'=>'طلبات العمل كقائد المرفوضة',
		'icon' =>'<i class="glyphicon glyphicon-file"></i>',
        'hasFather' => true
	]);

	Route::get('reviewerAgreedUsersMeta/{reviewer_id?}',[
		'uses' =>'UsersController@reviewerAgreedUsersMeta',
		'as'   =>'reviewerAgreedUsersMeta',
		'title'=>'طلبات العمل كقائد المقبولة من المراجع',
		// 'icon' =>'<i class="glyphicon glyphicon-file"></i>',
        // 'hasFather' => true
	]);
	
	Route::get('reviewerRefusedUsersMeta/{reviewer_id?}',[
		'uses' =>'UsersController@reviewerRefusedUsersMeta',
		'as'   =>'reviewerRefusedUsersMeta',
		'title'=>'طلبات العمل كقائد المرفوضة من المراجع',
		// 'icon' =>'<i class="glyphicon glyphicon-file"></i>',
        // 'hasFather' => true
	]);

	Route::get('userMeta/{id}',[
		'uses'=>'UsersController@userMeta',
		'as'  =>'userMeta',
		'title'=>'طلب العمل كقائد'
	]);

	Route::get('editUserMeta/{id}',[
		'uses'=>'UsersController@editUserMeta',
		'as'  =>'editUserMeta',
		'title'=>'تعديل طلب العمل كقائد'
	]);

	Route::post('updateUserMeta',[
		'uses' =>'UsersController@updateUserMeta',
		'as'   =>'updateUserMeta',
		'title'=>'حفظ تعديل طلب العمل كقائد'
	]);

	Route::post('agreeUserMeta',[
		'uses' =>'UsersController@agreeUserMeta',
		'as'   =>'agreeUserMeta',
		'title'=>'قبول طلب العمل كمندوب'
	]);

	Route::post('refuseUserMeta',[
		'uses' =>'UsersController@refuseUserMeta',
		'as'   =>'refuseUserMeta',
		'title'=>'رفض طلب العمل كمندوب'
	]);


	Route::post('deleteUserMeta',[
		'uses' =>'UsersController@deleteUserMeta',
		'as'   =>'deleteUserMeta',
		'title'=>'حذف طلب العمل كمندوب'
	]);
	#add user
	Route::post('add-user',[
		'uses' =>'UsersController@AddUser',
		'as'   =>'adduser',
		'title'=>'اضافة عضو'
	]);
    #userProfile
	Route::get('userProfile/{id?}',[
		'uses'=>'UsersController@userProfile',
		'as'  =>'userProfile',
		'title'=>'مشاهدة بيانات العضو'
	]);

	Route::get('userPerformance/{id?}/{page?}',[
		'uses'=>'UsersController@userPerformance',
		'as'  =>'userPerformance',
		'title'=>'أداء القائد'
	]);

	Route::post('finishCaptainWeekMoney',[
		'uses'=>'UsersController@finishCaptainWeekMoney',
		'as'  =>'finishCaptainWeekMoney',
		'title'=>'انهاء حسابات القائد الاسبوعية'
	]);	
	
	Route::get('captainMoneyHistory/{id?}',[
		'uses'=>'UsersController@captainMoneyHistory',
		'as'  =>'captainMoneyHistory',
		'title'=>'أرشيف حسابات القائد الاسبوعية'
	]);		

	Route::get('downloadCaptainMoneyArchive/{id?}',[
		'uses'=>'UsersController@downloadCaptainMoneyArchive',
		'as'  =>'downloadCaptainMoneyArchive',
		'title'=>'تحميل أرشيف حسابات القائد'
	]);

	Route::get('userAvailableTimes/{id?}/{page?}',[
		'uses'=>'UsersController@userAvailableTimes',
		'as'  =>'userAvailableTimes',
		'title'=>'ساعات توفر القائد'
	]);	
	Route::get('userOrdersRatings/{id?}/{page?}',[
		'uses'=>'UsersController@userOrdersRatings',
		'as'  =>'userOrdersRatings',
		'title'=>'اجمالي تقييمات القائد'
	]);	
	Route::get('ordersAcceptance/{id?}/{page?}',[
		'uses'=>'UsersController@ordersAcceptance',
		'as'  =>'ordersAcceptance',
		'title'=>'نسبة قبول القائد'
	]);			
	
	Route::post('update-user',[
		'uses' =>'UsersController@UpdateUser',
		'as'   =>'updateuser',
		'title'=>'تحديث عضو'
	]);

	#delete user
	Route::get('delete-user/{id?}',[
		'uses' =>'UsersController@deleteUser',
		'as'   =>'delete-user',
		'title'=>'حذف عضو'
	]);

	#delete users
	Route::post('deleteUsers',[
		'uses' =>'UsersController@deleteUsers',
		'as'   =>'deleteUsers',
		'title'=>'حذف أكثر من عضو'
	]);
	
	Route::post('emailAllusers',[
		'uses' =>'UsersController@emailAllusers',
		'as'   =>'emailAllusers',
		'title'=>'ارسال email للجميع'
	]);
	Route::post('emailClients',[
		'uses' =>'UsersController@emailClients',
		'as'   =>'emailClients',
		'title'=>'ارسال email للعملاء'
	]);	
	Route::post('emailCaptains',[
		'uses' =>'UsersController@emailCaptains',
		'as'   =>'emailCaptains',
		'title'=>'ارسال email للقادة'
	]);	
		
	Route::post('emailSupervisiors',[
		'uses' =>'UsersController@emailSupervisiors',
		'as'   =>'emailSupervisiors',
		'title'=>'ارسال email لادارة التطبيق'
	]);			

	Route::get('userOrdersArchive/{userid?}',[
		'uses' =>'UsersController@userOrdersArchive',
		'as'   =>'userOrdersArchive',
		'title'=>'أرشيف الرحلات '
	]);	

	Route::get('comments/{id?}',[
		'uses' =>'UsersController@comments',
		'as'   =>'comments',
		'title'=>'التعليقات '
	]);	
	Route::post('deleteComment',[
		'uses' =>'UsersController@deleteComment',
		'as'   =>'deleteComment',
		'title'=>'حذف التعليقات'
	]);		
	
	Route::get('adminUserPayments/{id?}',[
		'uses' =>'UsersController@adminUserPayments',
		'as'   =>'adminUserPayments',
		'title'=>'التعاملات المالية للمحفظة الجارية '
	]);		

	Route::get('downloadadminUserPayments/{id?}',[
		'uses' =>'UsersController@downloadadminUserPayments',
		'as'   =>'downloadadminUserPayments',
		'title'=>'تحميل التعاملات المالية للمحفظة الجارية '
	]);
	Route::get('adminUserPaymentsElectronic/{id?}',[
		'uses' =>'UsersController@adminUserPaymentsElectronic',
		'as'   =>'adminUserPaymentsElectronic',
		'title'=>'التعاملات المالية للمحفظة الالكترونية '
	]);		

	Route::get('downloadadminUserPaymentsElectronic/{id?}',[
		'uses' =>'UsersController@downloadadminUserPaymentsElectronic',
		'as'   =>'downloadadminUserPaymentsElectronic',
		'title'=>'تحميل التعاملات المالية للمحفظة الالكترونية '
	]);

	Route::get('captainCars/{id?}',[
		'uses' =>'UsersController@captainCars',
		'as'   =>'captainCars',
		'title'=>'سيارات القائد '
	]);
	Route::post('createCaptainCar',[
		'uses' =>'UsersController@createCaptainCar',
		'as'   =>'createCaptainCar',
		'title'=>'اضافة سيارة للقائد'
	]);

	#update store
	Route::post('updateCaptainCar',[
		'uses' =>'UsersController@updateCaptainCar',
		'as'   =>'updateCaptainCar',
		'title'=>'تحديث سيارة قائد'
	]);

	#delete store
	Route::post('DeleteCaptainCar',[
		'uses' =>'UsersController@DeleteCaptainCar',
		'as'   =>'DeleteCaptainCar',
		'title'=>'حذف سيارة قائد'
	]);		
	#sms for all users
	Route::post('SmsMessageAll',[
		'uses' =>'UsersController@SmsMessageAll',
		'as'   =>'SmsMessageAll',
		'title'=>'ارسال sms للجميع'
	]);

	#sms for users
	Route::post('SmsMessageClients',[
		'uses' =>'UsersController@SmsMessageClients',
		'as'   =>'SmsMessageClients',
		'title'=>'ارسال sms للعملاء'
	]);
	#sms for users
	Route::post('SmsMessageProviders',[
		'uses' =>'UsersController@SmsMessageProviders',
		'as'   =>'SmsMessageProviders',
		'title'=>'ارسال sms المندوبين'
	]);
	Route::post('SmsMessageSupervisiors',[
		'uses' =>'UsersController@SmsMessageSupervisiors',
		'as'   =>'SmsMessageSupervisiors',
		'title'=>'ارسال sms ادارة التطبيق'
	]);
	#notification for all users
	Route::post('notificationAllUsers',[
		'uses' =>'UsersController@notificationAllUsers',
		'as'   =>'notificationAllUsers',
		'title'=>'ارسال notification للجميع'
	]);

	#notification for clients
	Route::post('notificationClients',[
		'uses' =>'UsersController@notificationClients',
		'as'   =>'notificationClients',
		'title'=>'ارسال notification للعملاء'
	]);

	#notification for providers
	Route::post('notificationProviders',[
		'uses' =>'UsersController@notificationProviders',
		'as'   =>'notificationProviders',
		'title'=>'ارسال notification للمندوبين'
	]);
	Route::post('notificationSupervisiors',[
		'uses' =>'UsersController@notificationSupervisiors',
		'as'   =>'notificationSupervisiors',
		'title'=>'ارسال notification ادارة التطبيق'
	]);
	Route::post('currentUserEmail',[
		'uses' =>'UsersController@currentUserEmail',
		'as'   =>'currentUserEmail',
		'title'=>'ارسال email لعضو'
	]);
	#send sms for current user
	Route::post('currentUserSms',[
		'uses' =>'UsersController@currentUserSms',
		'as'   =>'currentUserSms',
		'title'=>'ارسال sms لعضو'
	]);

	#send notification for current user
	Route::post('currentUserNotification',[
		'uses' =>'UsersController@currentUserNotification',
		'as'   =>'currentUserNotification',
		'title'=>'ارسال notification لعضو'
	]);

	Route::post('adminAddCoupon',[
		'uses' =>'UsersController@adminAddCoupon',
		'as'   =>'adminAddCoupon',
		'title'=>'أضافة كوبون خصم'
	]);

	Route::post('admincreateBlock',[
		'uses' =>'UsersController@admincreateBlock',
		'as'   =>'admincreateBlock',
		'title'=>'حظر عضو'
	]);

	Route::post('admincancelBlock',[
		'uses' =>'UsersController@admincancelBlock',
		'as'   =>'admincancelBlock',
		'title'=>'انهاء حظر عضو'
	]);



	/*------------ End Of UsersController ----------*/

	/*------------ start Of OrderController ----------*/
	#orders list
	Route::get('Adminorders',[
		'uses' =>'OrderController@Adminorders',
		'as'   =>'Adminorders',
		'title'=>'رحلات توصيل الأشخاص', 
		'icon' =>'<i class="fa fa-car"></i>',
		'subTitle'=>'الكل',
        'subIcon' =>'<i class="glyphicon glyphicon-cog"></i>',
		'child'=>[
			'downloadAllOrders',
			'openOrders',
			'downloadOpenOrders',
			'inprogressOrders',
			'downloadInprogressOrders',
			'finishedOrders',
			'downloadFinishedOrders',
			'closedOrders',
			'downloadClosedOrders',
			'showOrder',
			'attachOrderToCaptain',
			'AdminupdateOrder',
			'AdmindeleteOrder',
			'AdmincloseOrder',
			'AdminfinishOrder',
			'orderWithdrawReasons',
			'deleteOrderWithdrawReason',
			'cancelWithdrawReasons',
			'createReason',
			'updateReason',
			'DeleteReason',
		]
	]);
	Route::get('downloadAllOrders',[
		'uses' =>'OrderController@downloadAllOrders',
		'as'   =>'downloadAllOrders',
		'title'=>'تحميل بيانات كل الرحلات',
	]);

	Route::get('openOrders',[
		'uses' =>'OrderController@openOrders',
		'as'   =>'openOrders',
		'title'=>'جديدة',
		'icon' =>'<i class="glyphicon glyphicon-bullhorn"></i>',
        'hasFather' => true
	]);
	Route::get('downloadOpenOrders',[
		'uses' =>'OrderController@downloadOpenOrders',
		'as'   =>'downloadOpenOrders',
		'title'=>'تحميل الرحلات المفتوحة',
	]);
	Route::get('inprogressOrders',[
		'uses' =>'OrderController@inprogressOrders',
		'as'   =>'inprogressOrders',
		'title'=>'قيد التنفيذ',
		'icon' =>'<i class="glyphicon glyphicon-hourglass"></i>',
        'hasFather' => true
	]);
	Route::get('downloadInprogressOrders',[
		'uses' =>'OrderController@downloadInprogressOrders',
		'as'   =>'downloadInprogressOrders',
		'title'=>'تحميل الرحلات قيد التنفيذ',
	]);
	Route::get('finishedOrders',[
		'uses' =>'OrderController@finishedOrders',
		'as'   =>'finishedOrders',
		'title'=>'منتهية',
		'icon' =>'<i class="glyphicon glyphicon-saved"></i>',
        'hasFather' => true
	]);
	Route::get('downloadFinishedOrders',[
		'uses' =>'OrderController@downloadFinishedOrders',
		'as'   =>'downloadFinishedOrders',
		'title'=>'تحميل الرحلات منتهية',
	]);
	Route::get('closedOrders',[
		'uses' =>'OrderController@closedOrders',
		'as'   =>'closedOrders',
		'title'=>'مغلقة',
		'icon' =>'<i class="glyphicon glyphicon-minus-sign"></i>',
        'hasFather' => true
	]);
	Route::get('downloadClosedOrders',[
		'uses' =>'OrderController@downloadClosedOrders',
		'as'   =>'downloadClosedOrders',
		'title'=>'تحميل الرحلات المغلقة',
	]);		
	Route::get('showOrder/{id?}',[
		'uses'  =>'OrderController@showOrder',
		'as'    =>'showOrder',
		'title' =>'مشاهدة الطلب'
	]);
	Route::post('attachOrderToCaptain',[
        'uses'  =>'OrderController@attachOrderToCaptain',
        'as'    =>'attachOrderToCaptain',
        'title' =>'إرفاق الرحلة لكاتبن'
    ]);
	Route::post('AdminupdateOrder',[
		'uses' =>'OrderController@AdminupdateOrder',
		'as'   =>'AdminupdateOrder',
		'title'=>'تحديث الطلب'
	]);
	Route::post('AdmindeleteOrder',[
		'uses' =>'OrderController@AdmindeleteOrder',
		'as'   =>'AdmindeleteOrder',
		'title'=>'حذف الطلب '
	]);
	Route::post('AdmincloseOrder',[
		'uses' =>'OrderController@AdmincloseOrder',
		'as'   =>'AdmincloseOrder',
		'title'=>'إغلاق الطلب '
	]);
	Route::post('AdminfinishOrder',[
		'uses' =>'OrderController@AdminfinishOrder',
		'as'   =>'AdminfinishOrder',
		'title'=>'إنهاء الطلب '
	]);
	
	Route::get('orderWithdrawReasons',[
		'uses' =>'OrderController@orderWithdrawReasons',
		'as'   =>'orderWithdrawReasons',
		'title'=>'رحلات تم الانسحاب منها',
		'icon' =>'<i class="glyphicon glyphicon-remove-sign"></i>',
		'hasFather' => true
	]);

	#delete message
	Route::post('deleteOrderWithdrawReason',[
		'uses' =>'OrderController@deleteOrderWithdrawReason',
		'as'   =>'deleteOrderWithdrawReason',
		'title'=>'حذف سبب انسحاب من طلب'
	]);

	Route::get('cancelWithdrawReasons',[
		'uses' =>'OrderController@cancelWithdrawReasons',
		'as'   =>'cancelWithdrawReasons',
		'title'=>'أسباب الإغلاق والانسحاب',
		'icon' =>'<i class="glyphicon glyphicon-tasks"></i>',
        'hasFather' => true
	]);	
	
	Route::post('createReason',[
		'uses' =>'OrderController@createReason',
		'as'   =>'createReason',
		'title'=>'اضافة سبب إغلاق وانسحاب'
	]);
	Route::post('updateReason',[
		'uses' =>'OrderController@updateReason',
		'as'   =>'updateReason',
		'title'=>'تعديل أسباب إغلاق وانسحاب'
	]);
	Route::post('DeleteReason',[
		'uses' =>'OrderController@DeleteReason',
		'as'   =>'DeleteReason',
		'title'=>'حذف سبب إغلاق وانسحاب.'
	]);	

//food delivery internal app orders 
	#orders list
	// Route::get('AdminFoodorders',[
	// 	'uses' =>'OrderController@AdminFoodOrders',
	// 	'as'   =>'AdminFoodOrders',
	// 	'title'=>'رحلات توصيل الطعام بالتطبيق', 
	// 	'icon' =>'<i class="fa fa-cutlery"></i>',
	// 	'subTitle'=>'الكل',
 //        'subIcon' =>'<i class="glyphicon glyphicon-cog"></i>',
	// 	'child'=>[
	// 		'downloadAllFoodOrders',
	// 		'FoodOpenOrders',
	// 		'downloadFoodOpenOrders',
	// 		'FoodInprogressOrders',
	// 		'downloadFoodInprogressOrders',
	// 		'FoodFinishedOrders',
	// 		'downloadFoodFinishedOrders',
	// 		'FoodClosedOrders',
	// 		'downloadFoodClosedOrders',
	// 		'FoodOrderWithdrawReasons',
	// 	]
	// ]);
	// Route::get('downloadAllFoodOrders',[
	// 	'uses' =>'OrderController@downloadAllFoodOrders',
	// 	'as'   =>'downloadAllFoodOrders',
	// 	'title'=>'تحميل بيانات كل رحلات الطعام',
	// ]);

	// Route::get('FoodOpenOrders',[
	// 	'uses' =>'OrderController@FoodOpenOrders',
	// 	'as'   =>'FoodOpenOrders',
	// 	'title'=>'جديدة',
	// 	'icon' =>'<i class="glyphicon glyphicon-bullhorn"></i>',
 //        'hasFather' => true
	// ]);
	// Route::get('downloadFoodOpenOrders',[
	// 	'uses' =>'OrderController@downloadFoodOpenOrders',
	// 	'as'   =>'downloadFoodOpenOrders',
	// 	'title'=>'تحميل رحلات الطعام الجديدة',
	// ]);
	// Route::get('FoodInprogressOrders',[
	// 	'uses' =>'OrderController@FoodInprogressOrders',
	// 	'as'   =>'FoodInprogressOrders',
	// 	'title'=>'قيد التنفيذ',
	// 	'icon' =>'<i class="glyphicon glyphicon-hourglass"></i>',
 //        'hasFather' => true
	// ]);
	// Route::get('downloadFoodInprogressOrders',[
	// 	'uses' =>'OrderController@downloadFoodInprogressOrders',
	// 	'as'   =>'downloadFoodInprogressOrders',
	// 	'title'=>'تحميل رحلات الطعام قيد التنفيذ',
	// ]);
	// Route::get('FoodFinishedOrders',[
	// 	'uses' =>'OrderController@FoodFinishedOrders',
	// 	'as'   =>'FoodFinishedOrders',
	// 	'title'=>'منتهية',
	// 	'icon' =>'<i class="glyphicon glyphicon-saved"></i>',
 //        'hasFather' => true
	// ]);
	// Route::get('downloadFoodFinishedOrders',[
	// 	'uses' =>'OrderController@downloadFoodFinishedOrders',
	// 	'as'   =>'downloadFoodFinishedOrders',
	// 	'title'=>'تحميل رحلات الطعام المنتهية',
	// ]);
	// Route::get('FoodClosedOrders',[
	// 	'uses' =>'OrderController@FoodClosedOrders',
	// 	'as'   =>'FoodClosedOrders',
	// 	'title'=>'مغلقة',
	// 	'icon' =>'<i class="glyphicon glyphicon-minus-sign"></i>',
 //        'hasFather' => true
	// ]);
	// Route::get('downloadFoodClosedOrders',[
	// 	'uses' =>'OrderController@downloadFoodClosedOrders',
	// 	'as'   =>'downloadFoodClosedOrders',
	// 	'title'=>'تحميل رحلات الطعام المغلقة',
	// ]);		
	
	// Route::get('FoodOrderWithdrawReasons',[
	// 	'uses' =>'OrderController@FoodOrderWithdrawReasons',
	// 	'as'   =>'FoodOrderWithdrawReasons',
	// 	'title'=>'رحلات الطعام تم الانسحاب منها',
	// 	'icon' =>'<i class="glyphicon glyphicon-remove-sign"></i>',
	// 	'hasFather' => true
	// ]);


//package delivery external app orders from other apps joined with api
	#orders list
	// Route::get('AdminExternalorders',[
	// 	'uses' =>'OrderController@AdminExternalorders',
	// 	'as'   =>'AdminExternalorders',
	// 	'title'=>'طلبات توصيل خارج التطبيق', 
	// 	'icon' =>'<i class="glyphicon glyphicon-cloud-download"></i>',
	// 	'subTitle'=>'الكل',
 //        'subIcon' =>'<i class="glyphicon glyphicon-cog"></i>',
	// 	'child'=>[
	// 		'downloadAllExternalOrders',
	// 		'ExternalOpenOrders',
	// 		'downloadExternalOpenOrders',
	// 		'ExternalInprogressOrders',
	// 		'downloadExternalInprogressOrders',
	// 		'ExternalFinishedOrders',
	// 		'downloadExternalFinishedOrders',
	// 		// 'ExternalClosedOrders',
	// 		// 'downloadExternalClosedOrders',
	// 	]
	// ]);
	// Route::get('downloadAllExternalOrders',[
	// 	'uses' =>'OrderController@downloadAllExternalOrders',
	// 	'as'   =>'downloadAllExternalOrders',
	// 	'title'=>'تحميل طلبات توصيل خارج التطبيق',
	// ]);

	// Route::get('ExternalOpenOrders',[
	// 	'uses' =>'OrderController@ExternalOpenOrders',
	// 	'as'   =>'ExternalOpenOrders',
	// 	'title'=>'المفتوحة',
	// 	'icon' =>'<i class="glyphicon glyphicon-bullhorn"></i>',
 //        'hasFather' => true
	// ]);
	// Route::get('downloadExternalOpenOrders',[
	// 	'uses' =>'OrderController@downloadExternalOpenOrders',
	// 	'as'   =>'downloadExternalOpenOrders',
	// 	'title'=>'تحميل الطلبات المفتوحة',
	// ]);
	// Route::get('ExternalInprogressOrders',[
	// 	'uses' =>'OrderController@ExternalInprogressOrders',
	// 	'as'   =>'ExternalInprogressOrders',
	// 	'title'=>'قيد التنفيذ',
	// 	'icon' =>'<i class="glyphicon glyphicon-hourglass"></i>',
 //        'hasFather' => true
	// ]);
	// Route::get('downloadExternalInprogressOrders',[
	// 	'uses' =>'OrderController@downloadExternalInprogressOrders',
	// 	'as'   =>'downloadExternalInprogressOrders',
	// 	'title'=>'تحميل الطلبات قيد التنفيذ',
	// ]);
	// Route::get('ExternalFinishedOrders',[
	// 	'uses' =>'OrderController@ExternalFinishedOrders',
	// 	'as'   =>'ExternalFinishedOrders',
	// 	'title'=>'منتهية',
	// 	'icon' =>'<i class="glyphicon glyphicon-saved"></i>',
 //        'hasFather' => true
	// ]);
	// Route::get('downloadExternalFinishedOrders',[
	// 	'uses' =>'OrderController@downloadExternalFinishedOrders',
	// 	'as'   =>'downloadExternalFinishedOrders',
	// 	'title'=>'تحميل الطلبات المنتهية',
	// ]);
	// Route::get('FoodClosedOrders',[
	// 	'uses' =>'OrderController@FoodClosedOrders',
	// 	'as'   =>'FoodClosedOrders',
	// 	'title'=>'مغلقة',
	// 	'icon' =>'<i class="glyphicon glyphicon-minus-sign"></i>',
 //        'hasFather' => true
	// ]);
	// Route::get('downloadFoodClosedOrders',[
	// 	'uses' =>'OrderController@downloadFoodClosedOrders',
	// 	'as'   =>'downloadFoodClosedOrders',
	// 	'title'=>'تحميل رحلات الطعام المغلقة',
	// ]);		
	

	/*------------ End Of OrderController ----------*/
    /*------------ Start Of StoresController ----------*/
    #stores
    // Route::get('stores',[
    //     'uses' =>'StoresController@stores',
    //     'as'   =>'stores',
    //     'title'=>'المتاجر ', 
    //     'icon' =>'<i class="glyphicon glyphicon-flag"></i>',
    //     'child'=>[
    //         'createStore',
    //         'updateStore',
    //         'DeleteStore',
    //         'branches',
    //         'createBranch',
    //         'updateBranch',
    //         'menuCategories',
    //         'createMenuCategory',
    //         'updateMenuCategory',
    //         'DeleteMenuCategory',
    //         'products',
    //         'createProduct',
    //         'updateProduct',
    //         'DeleteProduct'

    //     ]
    // ]);

    // #create store
    // Route::post('createStore',[
    //     'uses' =>'StoresController@createStore',
    //     'as'   =>'createStore',
    //     'title'=>'اضافة متجر'
    // ]);

    // #update store
    // Route::post('updateStore',[
    //     'uses' =>'StoresController@updateStore',
    //     'as'   =>'updateStore',
    //     'title'=>'تحديث بيانات متجر'
    // ]);

    // #delete store
    // Route::post('DeleteStore',[
    //     'uses' =>'StoresController@DeleteStore',
    //     'as'   =>'DeleteStore',
    //     'title'=>'حذف المتجر'
    // ]);

    // #products
    // Route::get('branches/{store_id?}',[
    //     'uses' =>'StoresController@branches',
    //     'as'   =>'branches',
    //     'title'=>'الفروع', 
    // ]);

    // #create product
    // Route::post('createBranch',[
    //     'uses' =>'StoresController@createBranch',
    //     'as'   =>'createBranch',
    //     'title'=>'اضافة فرع'
    // ]);

    // #update store
    // Route::post('updateBranch',[
    //     'uses' =>'StoresController@updateBranch',
    //     'as'   =>'updateBranch',
    //     'title'=>'تحديث بيانات فرع'
    // ]);

    // /*------------ End Of StoresController ----------*/ 
    // /*------------ Start Of ProductController ----------*/
    // #products
    // Route::get('products/{store_id?}',[
    //     'uses' =>'ProductController@products',
    //     'as'   =>'products',
    //     'title'=>'المنتجات'
    // ]);

    // #create product
    // Route::post('createProduct',[
    //     'uses' =>'ProductController@createProduct',
    //     'as'   =>'createProduct',
    //     'title'=>'اضافة منتج'
    // ]);

    // #update store
    // Route::post('updateProduct',[
    //     'uses' =>'ProductController@updateProduct',
    //     'as'   =>'updateProduct',
    //     'title'=>'تحديث بيانات منتج'
    // ]);

    // #delete store
    // Route::post('DeleteProduct',[
    //     'uses' =>'ProductController@DeleteProduct',
    //     'as'   =>'DeleteProduct',
    //     'title'=>'حذف المنتج'
    // ]);
    /*------------ End Of ProductController ----------*/
    /*------------ Start Of menuCategoryController ----------*/
    #menuCategories
    // Route::get('menuCategories/{store_id?}',[
    //     'uses' =>'menuCategoryController@menuCategories',
    //     'as'   =>'menuCategories',
    //     'title'=>'أقسام المنيو', 
    // ]);

    // Route::post('createMenuCategory',[
    //     'uses' =>'menuCategoryController@createMenuCategory',
    //     'as'   =>'createMenuCategory',
    //     'title'=>'اضافة قسم بالمنيو'
    // ]);

    // Route::post('updateMenuCategory',[
    //     'uses' =>'menuCategoryController@updateMenuCategory',
    //     'as'   =>'updateMenuCategory',
    //     'title'=>'تحديث قسم بالمنيو'
    // ]);

    // Route::post('DeleteMenuCategory',[
    //     'uses' =>'menuCategoryController@DeleteMenuCategory',
    //     'as'   =>'DeleteMenuCategory',
    //     'title'=>'حذف قسم المنيو'
    // ]);
    /*------------ End Of menuCategoryController ----------*/  
    /*------------ Start Of MoneyArchiveController ----------*/
	Route::get('captainsMoneyArchive',[
		'uses' =>'MoneyArchiveController@captainsMoneyArchive',
		'as'   =>'captainsMoneyArchive',
		'title'=>'أرشيف حسابات القادة',
		'icon' =>'<i class="glyphicon glyphicon-folder-open"></i>',
		'child'=>['downloadCaptainsMoneyArchive']
	]);
	Route::get('downloadCaptainsMoneyArchive',[
		'uses'=>'MoneyArchiveController@downloadCaptainsMoneyArchive',
		'as'  =>'downloadCaptainsMoneyArchive',
		'title'=>'تحميل أرشيف حسابات القادة'
	]);
    /*------------ End Of MoneyArchiveController ----------*/

    /*------------ Start Of OffersController ----------*/
	Route::get('offers',[
		'uses' =>'OffersController@offers',
		'as'   =>'offers',
		'title'=>'عروض العملاء', 
		'icon' =>'<i class="glyphicon glyphicon-gift"></i>',
		'child'=>[
			'createOffer',
			'updateOffer',
			'DeleteOffer'
		]
	]);

	#create store
	Route::post('createOffer',[
		'uses' =>'OffersController@createOffer',
		'as'   =>'createOffer',
		'title'=>'اضافة عرض'
	]);

	#update store
	Route::post('updateOffer',[
		'uses' =>'OffersController@updateOffer',
		'as'   =>'updateOffer',
		'title'=>'تحديث العرض'
	]);

	#delete store
	Route::post('DeleteOffer',[
		'uses' =>'OffersController@DeleteOffer',
		'as'   =>'DeleteOffer',
		'title'=>'حذف العرض'
	]);
    /*------------ End Of OffersController ----------*/  
    /*------------ Start Of OffersController ----------*/
	Route::get('offersCaptain',[
		'uses' =>'OffersController@offersCaptain',
		'as'   =>'offersCaptain',
		'title'=>'عروض القادة', 
		'icon' =>'<i class="glyphicon glyphicon-gift"></i>',
		'child'=>[
			'createCaptainOffer',
			'updateCaptainOffer',
			'DeleteCaptainOffer'
		]
	]);

	#create store
	Route::post('createCaptainOffer',[
		'uses' =>'OffersController@createCaptainOffer',
		'as'   =>'createCaptainOffer',
		'title'=>'اضافة عرض'
	]);

	#update store
	Route::post('updateCaptainOffer',[
		'uses' =>'OffersController@updateCaptainOffer',
		'as'   =>'updateCaptainOffer',
		'title'=>'تحديث العرض'
	]);

	#delete store
	Route::post('DeleteCaptainOffer',[
		'uses' =>'OffersController@DeleteCaptainOffer',
		'as'   =>'DeleteCaptainOffer',
		'title'=>'حذف العرض'
	]);
    /*------------ End Of OffersController ----------*/  
	/*------------ End Of ConversationController ----------*/
	#messages page
	Route::get('conversations',[
		'uses' =>'ConversationController@conversations',
		'as'   =>'conversations',
		'title'=>'المحادثات',
		'icon' =>'<i class="glyphicon glyphicon-comment"></i>',
		'child' =>['chat','deleteConversation']
	]);

	#show message page
	Route::get('chat/{id?}',[
		'uses'=>'ConversationController@chat',
		'as'  =>'chat',
		'title'=>'تفاصيل المحادثة'
	]);

	#delete message
	Route::post('deleteConversation',[
		'uses' =>'ConversationController@deleteConversation',
		'as'   =>'deleteConversation',
		'title'=>'حذف المحادثة'
	]);
    // Chat: Start
    Route::get('admin_conversations/{user_id?}',[
        'uses' =>'ConversationController@adminConversations',
        'as'   =>'adminConversations',
        'title'=>'المحادثات مع العملاء',
        'icon' =>'<i class="glyphicon glyphicon-comment"></i>',
        'child' =>[ 'uploadFile', 'getAllUsers', 'deleteAdminConversation']
    ]);
    Route::post('upload_file',[
        'uses' =>'ConversationController@uploadFile',
        'as'   =>'uploadFile',
        'title'=>'إرسال صورة',
    ]);
    //
    Route::get('users',[
        'uses' =>'ConversationController@getAllUsers',
        'as'   =>'getAllUsers',
        'title'=>'تحميل المستخدمين',
    ]);
    #delete message
    Route::post('conversation/delete/{adminConversation}',[
        'uses' =>'ConversationController@deleteAdminConversation',
        'as'   =>'deleteAdminConversation',
        'title'=>'حذف المحادثة'
    ]);
    // Chat: End
	/*------------ End Of ConversationController ----------*/
	
	/*------------ Start Of PaymentsController ----------*/
	#reports page
	Route::get('adminPaymentsReport',[
		'uses' =>'PaymentsController@adminPaymentsReport',
		'as'   =>'adminPaymentsReport',
		'title'=>'التعاملات المالية',
		'icon' =>'<i class="glyphicon glyphicon-equalizer"></i>',
		'child'=>['downloadPaymentsReport']
	]);
	Route::get('downloadPaymentsReport',[
		'uses'=>'PaymentsController@downloadPaymentsReport',
		'as'  =>'downloadPaymentsReport',
		'title'=>'تحميل التعاملات المالية'
	]);

	/*------------ End Of PaymentsController ----------*/
	
	/*------------ Start Of ReportsController ----------*/
	#reports page
	Route::get('Profits',[
		'uses' =>'ProfitsController@Profits',
		'as'   =>'Profits',
		'title'=>'تقارير الأرباح',
		'icon' =>'<i class="glyphicon glyphicon-euro"></i>',
		'child'=>['downloadProfits']
	]);
	Route::get('downloadProfits',[
		'uses'=>'ProfitsController@downloadProfits',
		'as'  =>'downloadProfits',
		'title'=>'تحميل تقرير بالأرباح'
	]);

	/*------------ End Of ReportsController ----------*/

// Route::get('documents',[
// 'uses' =>'DocumentsController@documents',
// 'as' =>'documents',
// 'title'=>'الخدمات',
// 'subTitle'=>'وثيقه الاشتراك',
// 'icon' =>'<i class="glyphicon glyphicon-bookmark"></i>',
// 'subIcon' =>'<i class="glyphicon glyphicon-cog"></i>',
// 'child'=>[
// 'RenewalDocument',
// 'reports',
// 'deleteDocument'
// ]
// ]);
// Route::get('renewal-document',[
// 'uses' =>'DocumentsController@RenewalDocument',
// 'as' =>'RenewalDocument',
// 'title'=>'تجديد الوثيقه',
// 'icon' =>'<i class="fa fa-credit-card-alt"></i>',
// 'hasFather' => true
// ]);
// Route::get('reports',[
// 'uses' =>'DocumentsController@reports',
// 'as' =>'reports',
// 'title'=>'الشكاوي والتقارير',
// 'icon' =>'<i class="fa fa-comments"></i>',
// 'hasFather' => true
// ]);
// #delete Documents
// Route::post('delete-Document',[
// 'uses' =>'DocumentsController@deleteDocument',
// 'as' =>'deleteDocument',
// 'title'=>'حذف'
// ]); 

	/*------------ Start Of ContactUsController ----------*/
	#messages page
	Route::get('inbox-page',[
		'uses' =>'ContactUsController@InboxPage',
		'as'   =>'inbox',
		'title'=>'البريد الوارد',
		'icon' =>'<i class="glyphicon glyphicon-envelope"></i>',
		'child' =>['showmessage','deletemessage','sendsms','sendemail','sendnotification']
	]);

	#show message page
	Route::get('show-message/{id}',[
		'uses'=>'ContactUsController@ShowMessage',
		'as'  =>'showmessage',
		'title'=>'عرض الرساله'
	]);

	#send sms
	Route::post('send-sms',[
		'uses' =>'ContactUsController@SMS',
		'as'   =>'sendsms',
		'title'=>'ارسال SMS'
	]);
	#send email
	Route::post('send-email',[
		'uses' =>'ContactUsController@EMAIL',
		'as'   =>'sendemail',
		'title'=>'ارسال Email'
	]);
	#send notification
	Route::post('send-notification',[
		'uses' =>'ContactUsController@sendnotification',
		'as'   =>'sendnotification',
		'title'=>'ارسال اشعار'
	]);
	#delete message
	Route::post('delete-message',[
		'uses' =>'ContactUsController@DeleteMessage',
		'as'   =>'deletemessage',
		'title'=>'حذف الرساله'
	]);
	/*------------ End Of ContactUsController ----------*/
	/*------------ Start Of ticketsController ----------*/
	#messages page
	Route::get('tickets',[
		'uses' =>'TicketsController@tickets',
		'as'   =>'tickets',
		'title'=>'الشكاوي',
		'icon' =>'<i class="glyphicon glyphicon-exclamation-sign"></i>',
		'child' =>['ticket','smsTicket','emailTicket','notificationTicket','deleteTicket']
	]);

	#show message page
	Route::get('ticket/{id}',[
		'uses'  =>'TicketsController@ticket',
		'as'    =>'ticket',
		'title' =>'عرض الشكوي'
	]);

	#send sms
	Route::post('smsTicket',[
		'uses' =>'TicketsController@smsTicket',
		'as'   =>'smsTicket',
		'title'=>'ارسال SMS'
	]);
	#send email
	Route::post('emailTicket',[
		'uses' =>'TicketsController@emailTicket',
		'as'   =>'emailTicket',
		'title'=>'ارسال Email'
	]);
	#send notification
	Route::post('notificationTicket',[
		'uses' =>'TicketsController@notificationTicket',
		'as'   =>'notificationTicket',
		'title'=>'ارسال اشعار'
	]);
	#delete message
	Route::post('deleteTicket',[
		'uses' =>'TicketsController@deleteTicket',
		'as'   =>'deleteTicket',
		'title'=>'حذف الشكوي'
	]);
	/*------------ End Of ticketsController ----------*/	

	/*------------ Start Of ReportsController ----------*/
	#reports page
	Route::get('reports-page',[
		'uses' =>'ReportsController@ReportsPage',
		'as'   =>'reportspage',
		'title'=>'التقارير',
		'icon' =>'<i class=" icon-flag7"></i>',
		'child'=>['deleteusersreports','deletesupervisorsreports']
	]);

	#delete users reports
	Route::post('delete-users-reporst',[
		'uses' =>'ReportsController@DeleteUsersReports',
		'as'   =>'deleteusersreports',
		'title'=>'حذف تقارير الاعضاء'
	]);

	#delete supervisors reports
	Route::post('delete-supervisors-reporst',[
		'uses' =>'ReportsController@DeleteSupervisorsReports',
		'as'   =>'deletesupervisorsreports',
		'title'=>'حذف تقارير المشرفين'
	]);
	/*------------ End Of ReportsController ----------*/

	/*------------ start Of PermissionsController ----------*/
	#permissions list
	Route::get('permissions-list',[
		'uses' =>'PermissionsController@PermissionsList',
		'as'   =>'permissionslist',
		'title'=>'قائمة الصلاحيات',
		'icon' =>'<i class="icon-safe"></i>',
		'child'=>[
			'addpermissionspage',
			'addpermission',
			'editpermissionpage',
			'updatepermission',
			'deletepermission'
		]
	]);

	#add permissions page
	Route::get('permissions',[
		'uses' =>'PermissionsController@AddPermissionsPage', 
		'as'   =>'addpermissionspage',
		'title'=>'اضافة صلاحيه',

	]);

	#add permission
	Route::post('add-permission',[
		'uses' =>'PermissionsController@AddPermissions',
		'as'   =>'addpermission',
		'title' =>'تمكين اضافة صلاحيه'
	]);

	#edit permissions page
	Route::get('edit-permissions/{id}',[
		'uses' =>'PermissionsController@EditPermissions',
		'as'   =>'editpermissionpage',
		'title'=>'تعديل صلاحيه'
	]);

	#update permission
	Route::post('update-permission',[
		'uses' =>'PermissionsController@UpdatePermission',
		'as'   =>'updatepermission',
		'title'=>'تمكين تعديل صلاحيه'
	]);

	#delete permission
	Route::post('delete-permission',[
		'uses'=>'PermissionsController@DeletePermission',
		'as'  =>'deletepermission',
		'title' =>'حذف صلاحيه'
	]);
	/*------------ End Of PermissionsController ----------*/

    /*------------ Start Of CouponsController ----------*/
	Route::get('coupons',[
		'uses' =>'CouponsController@coupons',
		'as'   =>'coupons',
		'title'=>'كوبونات الخصم', 
		'icon' =>'<i class="glyphicon glyphicon-erase"></i>',
		'child'=>[
			'createCoupon',
			'generateCode',
			'updateCoupon',
			'DeleteCoupon',
			'deleteCoupons'
		]
	]);

	#create coupon
	Route::post('createCoupon',[
		'uses' =>'CouponsController@createCoupon',
		'as'   =>'createCoupon',
		'title'=>'اضافة كوبون'
	]);

	#update coupon
	Route::post('updateCoupon',[
		'uses' =>'CouponsController@updateCoupon',
		'as'   =>'updateCoupon',
		'title'=>'تحديث كوبون'
	]);

	#generateCode coupon
	Route::get('generateCode',[
		'uses' =>'CouponsController@generateCode',
		'as'   =>'generateCode',
		'title'=>'انشاء كود تحقق'
	]);

	#delete user
	Route::post('DeleteCoupon',[
		'uses' =>'CouponsController@DeleteCoupon',
		'as'   =>'DeleteCoupon',
		'title'=>'حذف كوبون'
	]);

	#delete users
	Route::post('deleteCoupons',[
		'uses' =>'CouponsController@deleteCoupons',
		'as'   =>'deleteCoupons',
		'title'=>'حذف أكثر من كوبون'
	]);
    /*------------ End Of CouponsController ----------*/
    /*------------ Start Of PackagesController ----------*/
	Route::get('clientPackages',[
		'uses' =>'PackagesController@clientPackages',
		'as'   =>'clientPackages',
		'title'=>'باقات اشتراك العملاء', 
		'icon' =>'<i class="glyphicon glyphicon-gift"></i>',
		'child'=>[
			'createClientPackage',
			'updateClientPackage',
			'DeleteClientPackage'
		]
	]);

	#create store
	Route::post('createClientPackage',[
		'uses' =>'PackagesController@createClientPackage',
		'as'   =>'createClientPackage',
		'title'=>'اضافة باقة'
	]);

	#update store
	Route::post('updateClientPackage',[
		'uses' =>'PackagesController@updateClientPackage',
		'as'   =>'updateClientPackage',
		'title'=>'تحديث باقة'
	]);

	#delete store
	Route::post('DeleteClientPackage',[
		'uses' =>'PackagesController@DeleteClientPackage',
		'as'   =>'DeleteClientPackage',
		'title'=>'حذف باقة'
	]);

	Route::get('captainPackages',[
		'uses' =>'PackagesController@captainPackages',
		'as'   =>'captainPackages',
		'title'=>'باقات اشتراك القادة', 
		'icon' =>'<i class="glyphicon glyphicon-gift"></i>',
		'child'=>[
			'createCaptainPackage',
			'updateCaptainPackage',
			'DeleteCaptainPackage'
		]
	]);

	#create store
	Route::post('createCaptainPackage',[
		'uses' =>'PackagesController@createCaptainPackage',
		'as'   =>'createCaptainPackage',
		'title'=>'اضافة باقة'
	]);

	#update store
	Route::post('updateCaptainPackage',[
		'uses' =>'PackagesController@updateCaptainPackage',
		'as'   =>'updateCaptainPackage',
		'title'=>'تحديث باقة'
	]);

	#delete store
	Route::post('DeleteCaptainPackage',[
		'uses' =>'PackagesController@DeleteCaptainPackage',
		'as'   =>'DeleteCaptainPackage',
		'title'=>'حذف باقة'
	]);

    /*------------ Start Of chargeCardsController ----------*/
	Route::get('chargeCards',[
		'uses' =>'chargeCardsController@chargeCards',
		'as'   =>'chargeCards',
		'title'=>'كوبونات الشحن', 
		'icon' =>'<i class="glyphicon glyphicon-barcode"></i>',
		'subTitle'=>'الكوبونات الجديدة',
        'subIcon' =>'<i class="glyphicon glyphicon-ok"></i>',			
		'child'=>[
		    'usedChargeCards',
			'createchargeCard',
			'downloadchargeCards',
			'generatechargeCardCode',
			'DeletechargeCard',
			'DeletechargeCards',
			'DeleteUsedchargeCard',
			'DeleteUsedchargeCards'			
		]
	]);

	Route::get('usedChargeCards',[
		'uses' =>'chargeCardsController@usedChargeCards',
		'as'   =>'usedChargeCards',
		'title'=>'الكوبونات المستخدمة',
		'icon' =>'<i class="glyphicon glyphicon-circle-arrow-down"></i>',
        'hasFather' => true
	]);

	Route::get('downloadchargeCards',[
		'uses'=>'chargeCardsController@downloadchargeCards',
		'as'  =>'downloadchargeCards',
		'title'=>'تحميل كوبونات الشحن'
	]);
	Route::post('createchargeCard',[
		'uses' =>'chargeCardsController@createchargeCard',
		'as'   =>'createchargeCard',
		'title'=>'اضافة كوبون شحن'
	]);

	#generateCode coupon
	Route::get('generatechargeCardCode',[
		'uses' =>'chargeCardsController@generatechargeCardCode',
		'as'   =>'generatechargeCardCode',
		'title'=>'انشاء كود الشحن'
	]);

	Route::get('DeletechargeCard/{id?}',[
		'uses' =>'chargeCardsController@DeletechargeCard',
		'as'   =>'DeletechargeCard',
		'title'=>'حذف كوبون'
	]);

	#delete users
	Route::post('DeletechargeCards',[
		'uses' =>'chargeCardsController@DeletechargeCards',
		'as'   =>'DeletechargeCards',
		'title'=>'حذف أكثر من كوبون'
	]);
	Route::get('DeleteUsedchargeCard/{id?}',[
		'uses' =>'chargeCardsController@DeleteUsedchargeCard',
		'as'   =>'DeleteUsedchargeCard',
		'title'=>'حذف كوبون مستخدم'
	]);

	#delete users
	Route::post('DeleteUsedchargeCards',[
		'uses' =>'chargeCardsController@DeleteUsedchargeCards',
		'as'   =>'DeleteUsedchargeCards',
		'title'=>'حذف أكثر من كوبون مستخدم'
	]);	
    /*------------ End Of chargeCardsController ----------*/
    /*------------ Start Of CartypesController ----------*/
	Route::get('cartypes',[
		'uses' =>'CartypesController@cartypes',
		'as'   =>'cartypes',
		'title'=>'تصنيفات السيارات', 
		'icon' =>'<i class="fa fa-car"></i>',
		'child'=>[
			'createCartype',
			'updateCartype',
			'DeleteCartype'
		]
	]);

	#create product
	Route::post('createCartype',[
		'uses' =>'CartypesController@createCartype',
		'as'   =>'createCartype',
		'title'=>'اضافة تصنيف سيارات'
	]);

	#update store
	Route::post('updateCartype',[
		'uses' =>'CartypesController@updateCartype',
		'as'   =>'updateCartype',
		'title'=>'تحديث تصنيف سيارات'
	]);

	#delete store
	Route::post('DeleteCartype',[
		'uses' =>'CartypesController@DeleteCartype',
		'as'   =>'DeleteCartype',
		'title'=>'حذف تصنيف سيارات'
	]);
    /*------------ End Of CartypesController ----------*/
    /*------------ Start Of CountryController ----------*/
	Route::get('countries',[
		'uses' =>'CountryController@countries',
		'as'   =>'countries',
		'title'=>'الدول والمدن', 
		'icon' =>'<i class="glyphicon glyphicon-globe"></i>',
		'child'=>[
			'createCountry',
			'updateCountry',
			'DeleteCountry',
			'cities',
			'createCity',
			'updateCity',
			'DeleteCity',
		]
	]);
	Route::post('createCountry',[
		'uses' =>'CountryController@createCountry',
		'as'   =>'createCountry',
		'title'=>'اضافة دولة'
	]);
	Route::post('updateCountry',[
		'uses' =>'CountryController@updateCountry',
		'as'   =>'updateCountry',
		'title'=>'تحديث بيانات دولة'
	]);
	Route::post('DeleteCountry',[
		'uses' =>'CountryController@DeleteCountry',
		'as'   =>'DeleteCountry',
		'title'=>'حذف دولة'
	]);
	Route::get('cities/{countryid?}',[
		'uses' =>'CountryController@cities',
		'as'   =>'cities',
		'title'=>'المدن '
	]);	
	Route::post('createCity',[
		'uses' =>'CountryController@createCity',
		'as'   =>'createCity',
		'title'=>'اضافة مدينة'
	]);
	Route::post('updateCity',[
		'uses' =>'CountryController@updateCity',
		'as'   =>'updateCity',
		'title'=>'تحديث بيانات المدينة'
	]);
	Route::post('DeleteCity',[
		'uses' =>'CountryController@DeleteCity',
		'as'   =>'DeleteCity',
		'title'=>'حذف المدينة'
	]);	
    /*------------ End Of CountryController ----------*/
    /*------------ Start Of PlanController ----------*/
	Route::get('plans',[
		'uses' =>'PlanController@plans',
		'as'   =>'plans',
		'title'=>'الخطط والمستويات', 
		'icon' =>'<i class="glyphicon glyphicon-list-alt"></i>',
		'child'=>[
			'createPlan',
			'updatePlan',
			'DeletePlan'
		]
	]);

	#create Plan
	Route::post('createPlan',[
		'uses' =>'PlanController@createPlan',
		'as'   =>'createPlan',
		'title'=>'اضافة خطة'
	]);

	#update Plan
	Route::post('updatePlan',[
		'uses' =>'PlanController@updatePlan',
		'as'   =>'updatePlan',
		'title'=>'تحديث بيانات الخطة'
	]);

	#delete Plan
	Route::post('DeletePlan',[
		'uses' =>'PlanController@DeletePlan',
		'as'   =>'DeletePlan',
		'title'=>'حذف الخطة'
	]);  
    /*------------ End Of PlanController ----------*/
    /*------------ Start Of PointsController ----------*/
    #points
	Route::get('points/',[
		'uses' =>'PointsController@points',
		'as'   =>'points',
		'title'=>'استبدال نقاط العملاء', 
		'icon' =>'<i class="glyphicon glyphicon-bitcoin"></i>',
		'child'=>[
			'createPoint',
			'updatePoint',
			'DeletePoint'
		]
	]);

	#create points
	Route::post('createPoint',[
		'uses' =>'PointsController@createPoint',
		'as'   =>'createPoint',
		'title'=>'اضافة مكافأت النقاط'
	]);

	#update store
	Route::post('updatePoint',[
		'uses' =>'PointsController@updatePoint',
		'as'   =>'updatePoint',
		'title'=>'تحديث مكافأت النقاط'
	]);

	#delete store
	Route::post('DeletePoint',[
		'uses' =>'PointsController@DeletePoint',
		'as'   =>'DeletePoint',
		'title'=>'حذف مكافأت النقاط'
	]);

    /*------------ End Of PointsController ----------*/    
    /*------------ Start Of RewardController ----------*/
    #rewards
	Route::get('rewards/',[
		'uses' =>'RewardsController@rewards',
		'as'   =>'rewards',
		'title'=>'المكافأت', 
		'icon' =>'<i class="glyphicon glyphicon-gift"></i>',
		'child'=>[
			'createRewards',
			'updateRewards',
			'DeleteRewards'
		]
	]);

	#create plans
	Route::post('createRewards',[
		'uses' =>'RewardsController@createRewards',
		'as'   =>'createRewards',
		'title'=>'اضافة مكافأة'
	]);

	#update store
	Route::post('updateRewards',[
		'uses' =>'RewardsController@updateRewards',
		'as'   =>'updateRewards',
		'title'=>'تحديث مكافأة'
	]);

	#delete store
	Route::post('DeleteRewards',[
		'uses' =>'RewardsController@DeleteRewards',
		'as'   =>'DeleteRewards',
		'title'=>'حذف مكافأة'
	]);
    /*------------ End Of RewardsController ----------*/ 
    /*------------ Start Of GuaranteesController ----------*/
    #rewards
	Route::get('guarantees/',[
		'uses' =>'GuaranteesController@guarantees',
		'as'   =>'guarantees',
		'title'=>'الضمانات', 
		'icon' =>'<i class="glyphicon glyphicon-upload"></i>',
		'child'=>[
			'createGuarantees',
			'updateGuarantees',
			'DeleteGuarantees'
		]
	]);

	#create plans
	Route::post('createGuarantees',[
		'uses' =>'GuaranteesController@createGuarantees',
		'as'   =>'createGuarantees',
		'title'=>'اضافة ضمان'
	]);

	#update store
	Route::post('updateGuarantees',[
		'uses' =>'GuaranteesController@updateGuarantees',
		'as'   =>'updateGuarantees',
		'title'=>'تحديث ضمان'
	]);

	#delete store
	Route::post('DeleteGuarantees',[
		'uses' =>'GuaranteesController@DeleteGuarantees',
		'as'   =>'DeleteGuarantees',
		'title'=>'حذف ضمان'
	]);
    /*------------ End Of RewardsController ----------*/ 
    /*------------ Start Of CashBackController ----------*/
    #cashBack
	Route::get('cashBack/',[
		'uses' =>'CashBackController@cashBack',
		'as'   =>'cashBack',
		'title'=>'الكاش باك', 
		'icon' =>'<i class="glyphicon glyphicon-gift"></i>',
		'child'=>[
			'createCashBack',
			'updateCashBack',
			'DeleteCashBack'
		]
	]);

	#create plans
	Route::post('createCashBack',[
		'uses' =>'CashBackController@createCashBack',
		'as'   =>'createCashBack',
		'title'=>'اضافة كاش باك'
	]);

	#update store
	Route::post('updateCashBack',[
		'uses' =>'CashBackController@updateCashBack',
		'as'   =>'updateCashBack',
		'title'=>'تحديث كاش باك'
	]);

	#delete store
	Route::post('DeleteCashBack',[
		'uses' =>'CashBackController@DeleteCashBack',
		'as'   =>'DeleteCashBack',
		'title'=>'حذف كاش باك'
	]);
    /*------------ End Of cashBackController ----------*/ 
    /*------------ Start Of PricesController ----------*/ 
	Route::get('prices',[
		'uses' =>'PricesController@prices',
		'as'   =>'prices',
		'title'=>'حسابات وأسعار المشاوير', 
		'icon' =>'<i class="icon-calculator"></i>',
		'child'=>[
			'createPrice',
			'updatePrice',
			'DeletePrice'
		]
	]);

	#create Plan
	Route::post('createPrice',[
		'uses' =>'PricesController@createPrice',
		'as'   =>'createPrice',
		'title'=>'اضافة خطة أسعار'
	]);

	#update Plan
	Route::post('updatePrice',[
		'uses' =>'PricesController@updatePrice',
		'as'   =>'updatePrice',
		'title'=>'تحديث خطة الأسعار'
	]);

	#delete Plan
	Route::post('DeletePrice',[
		'uses' =>'PricesController@DeletePrice',
		'as'   =>'DeletePrice',
		'title'=>'حذف خطة أسعار'
	]);  	
    /*------------ End Of PricesController ----------*/ 
	/*------------ Start Of MoneyAccountsController ----------*/
	// Route::get('money-accounts',[
	// 	'uses' =>'MoneyAccountsController@MoneyAccountsPage',
	// 	'as'   =>'moneyaccountspage',
	// 	'icon' =>'<i class="icon-cash3"></i>',
	// 	'title'=>'الحسابات الماليه',
	// 	'child'=>['moneyaccept','moneyacceptdelete','moneydelete']
	// ]);

	// #accept
	// Route::post('accept',[
	// 	'uses' =>'MoneyAccountsController@Accept',
	// 	'as'   =>'moneyaccept',
	// 	'title'=>'تأكيد معامله بنكيه',
	// ]);

	// #accept and delete
	// Route::post('accept-delete',[
	// 	'uses' =>'MoneyAccountsController@AcceptAndDelete',
	// 	'as'   =>'moneyacceptdelete',
	// 	'title'=>'تأكيد مع حذف',
	// ]);

	// #delete
	// Route::post('money-delete',[
	// 	'uses' =>'MoneyAccountsController@Delete',
	// 	'as'   =>'moneydelete',
	// 	'title'=>'حذف معامله بنكيه',
	// ]);
	/*------------ End Of MoneyAccountsController ----------*/

    /*------------ Start Of PagesController ----------*/
	Route::get('pages',[
		'uses' =>'PagesController@Pages',
		'as'   =>'pages',
		'title'=>'الصفحات والمساعدة', 
		'icon' =>'<i class="glyphicon glyphicon-edit"></i>',
		'child'=>[
			'addpage',
			'updatepage',
			'deletepage'
		]
	]);

	Route::post('add-page',[
		'uses' =>'PagesController@AddPage',
		'as'   =>'addpage',
		'title'=>'اضافة صفحة'
	]);

	Route::post('updatepage',[
		'uses' =>'PagesController@UpdatePage',
		'as'   =>'updatepage',
		'title'=>'تحديث الصفحة'
	]);
	Route::post('deletepage',[
		'uses' =>'PagesController@deletepage',
		'as'   =>'deletepage',
		'title'=>'حذف صفحة'
	]);	
    /*------------ End Of PagesController ----------*/
    
    /*------------ Start Of DashBoardController ----------*/

	#Questions list
	// Route::get('question',[
	// 	'uses' =>'DashBoardController@question',
	// 	'as'   =>'question',
	// 	'title'=>'الأسئلة', 
	// 	'icon' =>'<i class="glyphicon glyphicon-info-sign"></i>',
	// 	'child'=>[
	// 		'addQuestion',
	// 		'updateQuestion',
	// 		'deleteQuestion'
	// 	]
	// ]);

	// #add question
	// Route::post('addQuestion',[
	// 	'uses' =>'DashBoardController@addQuestion',
	// 	'as'   =>'addQuestion',
	// 	'title'=>'اضافة سؤال'
	// ]);

	// #update question
	// Route::post('updateQuestion',[
	// 	'uses' =>'DashBoardController@updateQuestion',
	// 	'as'   =>'updateQuestion',
	// 	'title'=>'تحديث سؤال'
	// ]);

	// #delete question
	// Route::post('deleteQuestion',[
	// 	'uses' =>'DashBoardController@deleteQuestion',
	// 	'as'   =>'deleteQuestion',
	// 	'title'=>'حذف سؤال'
	// ]);

	/*------------ End Of DashBoardController ----------*/

   /** Start WelcomePageController **/
    Route::group(['prefix' => 'welcome-page'], function () {
        Route::get('/advantage', [
            'uses' => 'AdvantageController@index',
            'as' => 'advantage.index',
            'title' => 'إعدادات الصفحة التعريفية',
            'icon' => '<i class="fas fa-wrench"></i>',
            'status' => true,
            'subTitle'=> 'مميزاتنا',
            'subIcon' =>'<i class="fa fa-star"></i>',
            'child' => [
                'advantage.store',
                'advantage.update',
                'advantage.destroy',
                'advantage.destroy_selected',
                'imageApp.index',
                'imageApp.store',
                'imageApp.destroy',
                'customerReview.index',
                'customerReview.store',
                'customerReview.update',
                'customerReview.destroy',
                'welcomePage.index',
                'welcomePage.update',

            ]
        ]);


        Route::post('advantage/', [
            'uses'=>'AdvantageController@store',
            'as'=>'advantage.store',
            'title'=> 'إضافة ميزة',
        ]);



        Route::put('advantage/', [
            'uses'=>'AdvantageController@update',
            'as'=>'advantage.update',
            'title'=> 'تعديل الميزة',
        ]);

        Route::delete('advantage/{id}', [
            'uses'=>'AdvantageController@destroy',
            'as'=>'advantage.destroy',
            'title'=> 'حذف الميزة',
        ]);



        Route::get('image-app/', [
            'uses'=>'ImageAppController@index',
            'as'=>'imageApp.index',
            'title'=> 'صور التطبيق',
            'icon' =>'<i class="fas fa-images"></i>',
            'hasFather'=>true
        ]);


        Route::post('image-app/', [
            'uses'=>'ImageAppController@store',
            'as'=>'imageApp.store',
            'title'=> 'إضافة صوره التطبيق',
        ]);



        Route::delete('image-app/{id}', [
            'uses'=>'ImageAppController@destroy',
            'as'=>'imageApp.destroy',
            'title'=> 'حذف صوره التطبيق',
        ]);



        Route::get('customer-reviews/', [
            'uses'=>'CustomerReviewController@index',
            'as'=>'customerReview.index',
            'title'=> 'آراء العملاء',
            'icon' =>'<i class="fas fa-chalkboard-teacher"></i>',
            'hasFather'=>true
        ]);


        Route::post('customer-reviews/', [
            'uses'=>'CustomerReviewController@store',
            'as'=>'customerReview.store',
            'title'=> 'إضافة الرأي',
        ]);


        Route::put('customer-reviews/', [
            'uses'=>'CustomerReviewController@update',
            'as'=>'customerReview.update',
            'title'=> 'تعديل الرأي',
        ]);

        Route::delete('customer-reviews/{id}', [
            'uses'=>'CustomerReviewController@destroy',
            'as'=>'customerReview.destroy',
            'title'=> 'حذف الرأي',
        ]);


        Route::get('settings/index', [
            'uses'=>'WelcomePageSettingController@index',
            'as'=>'welcomePage.index',
            'title'=> 'الإعدادات للصفحة التعريفية',
            'icon' =>'<i class="fas fa-cogs"></i>',
            'hasFather'=>true
        ]);

        Route::post('welcome-page-settings/update/setting', [
            'uses'=>'WelcomePageSettingController@siteSetting',
            'as'=>'welcomePage.update',
            'title'=> 'تعديل أعدادات الموقع التعريفي',
        ]);

    });
    /** End WelcomePageController **/
	/*------------ Start Of SettingController ----------*/

	#setting page
	Route::get('setting',[
		'uses' =>'SettingController@Setting',
		'as'   =>'setting',
		'title'=>'الاعدادات',
		'icon' =>'<i class="icon-wrench"></i>',
		'child'=>[
			'addsocials',
			'updatesocials',
			'deletesocial',
			'addAd',
			'updateAd',
			'deleteAd',				
			'updatesmtp',
			'updatesms',
			'updatemobily',
			'updateyamama',
			'updateoursms',
			'updatehisms',
			'updatejawaly',
			'updateunifonic',
			'updategateway',
			'updatemsegat',
			'updateNexmosms',
			'updateTwilio',
			'updateonesignal',
			'updatefcm',
			'updatesitesetting',
			// 'updatePlaceTypes',
			'updateseo',
			'updatesiteTermsAndPrivacy',
			'updateemailtemplate',
			'updateCurrencyConverter',
			'updategooglePlacesKey',
			'updatewaslApiKey',
			'updategoogleanalytics',
			'updatelivechat'
		]
	]);

	#add socials media
	Route::post('add-socials',[
		'uses' =>'SettingController@AddSocial',
		'as'   =>'addsocials',
		'title'=>'اضافة مواقع التواصل'
	]);

	#update socials media
	Route::post('update-socials',[
		'uses' =>'SettingController@UpdateSocial',
		'as'   =>'updatesocials',
		'title'=>'تحديث مواقع التواصل'
	]);

	#delete social
	Route::post('delete-social',[
		'uses' =>'SettingController@DeleteSocial',
		'as'   =>'deletesocial',
		'title'=>'حذف مواقع التاوصل'
	]);

	#add Ad
	Route::post('addAd',[
		'uses' =>'SettingController@addAd',
		'as'   =>'addAd',
		'title'=>'اضافة اعلان'
	]);

	#update Ad
	Route::post('updateAd',[
		'uses' =>'SettingController@updateAd',
		'as'   =>'updateAd',
		'title'=>'تحديث اعلان'
	]);

	#delete Ad
	Route::post('deleteAd',[
		'uses' =>'SettingController@deleteAd',
		'as'   =>'deleteAd',
		'title'=>'حذف اعلان'
	]);

	#update SMTP
	Route::post('update-smtp',[
		'uses' =>'SettingController@SMTP',
		'as'   =>'updatesmtp',
		'title'=>'تحديث SMTP'
	]);

	// #update SMS
	// Route::post('update-sms',[
	// 	'uses' =>'SettingController@SMS',
	// 	'as'   =>'updatesms',
	// 	'title'=>'تحديث SMS'
	// ]);

    #update Mobily
    Route::post('update-mobily',[
        'uses' =>'SettingController@Mobily',
        'as'   =>'updatemobily',
        'title'=>'تحديث Mobily'
    ]);

    #update yamamah
    Route::post('update-yamamah',[
        'uses' =>'SettingController@yamamah',
        'as'   =>'updateyamama',
        'title'=>'تحديث yamamah'
    ]);

    #update OurSms
    Route::post('update-OurSms',[
        'uses' =>'SettingController@OurSms',
        'as'   =>'updateoursms',
        'title'=>'تحديث OurSms'
    ]);

    #update 4jawaly
    Route::post('update-jawaly',[
        'uses' =>'SettingController@jawaly',
        'as'   =>'updatejawaly',
        'title'=>'تحديث jawaly'
    ]);

    #update hiSms
    Route::post('update-HiSms',[
        'uses' =>'SettingController@HiSms',
        'as'   =>'updatehisms',
        'title'=>'تحديث HiSms'
    ]);
    #update unifonic
    Route::post('update-unifonic',[
        'uses' =>'SettingController@unifonic',
        'as'   =>'updateunifonic',
        'title'=>'تحديث unifonic'
    ]);
    
    #update gateway
    Route::post('update-gateway',[
        'uses' =>'SettingController@gateway',
        'as'   =>'updategateway',
        'title'=>'تحديث Hi sms'
    ]);

    #update msegat
    Route::post('update-msegat',[
        'uses' =>'SettingController@msegat',
        'as'   =>'updatemsegat',
        'title'=>'تحديث msegat'
    ]);
    
    #update Nexmosms
    Route::post('update-Nexmosms',[
        'uses' =>'SettingController@Nexmosms',
        'as'   =>'updateNexmosms',
        'title'=>'تحديث Nexmosms'
    ]);

    #update Twilio
    Route::post('update-Twilio',[
        'uses' =>'SettingController@Twilio',
        'as'   =>'updateTwilio',
        'title'=>'تحديث Twilio'
    ]);

	#update OneSignal
	Route::post('update-onesignal',[
		'uses' =>'SettingController@OneSignal',
		'as'   =>'updateonesignal',
		'title'=>'تحديث OneSignal'
	]);

	#update FCM
	Route::post('update-FCM',[
		'uses' =>'SettingController@updateFCM',
		'as'   =>'updatefcm',
		'title'=>'تحديث FCM'
	]);

	#update SiteSetting
	Route::post('update-sitesetting',[
		'uses' =>'SettingController@SiteSetting',
		'as'   =>'updatesitesetting',
		'title'=>'تحديث الاعدادات العامه'
	]);

	# updatePlaceTypes
	// Route::post('updatePlaceTypes',[
	// 	'uses' =>'SettingController@updatePlaceTypes',
	// 	'as'   =>'updatePlaceTypes',
	// 	'title'=>'تحديث أنواع الأماكن بالتطبيق'
	// ]);
	
	#update SEO
	Route::post('update-seo',[
		'uses' =>'SettingController@SEO',
		'as'   =>'updateseo',
		'title'=>'تحديث SEO'
	]);

	#update ads
	Route::post('updatesiteTermsAndPrivacy',[
		'uses' =>'SettingController@updatesiteTermsAndPrivacy',
		'as'   =>'updatesiteTermsAndPrivacy',
		'title'=>'تحديث الشروط والأحكام '
	]);

	#update email template
	Route::post('update-emailtemplate',[
		'uses' =>'SettingController@EmailTemplate',
		'as'   =>'updateemailtemplate',
		'title'=>'تحديث قالب الايميل'
	]);
    
    #update google Places Key
	Route::post('updategooglePlacesKey',[
		'uses' =>'SettingController@updategooglePlacesKey',
		'as'   =>'updategooglePlacesKey',
		'title'=>'تحديث Google Places Key'
	]);
	Route::post('updatewaslApiKey',[
		'uses' =>'SettingController@updatewaslApiKey',
		'as'   =>'updatewaslApiKey',
		'title'=>'تحديث Wasl API Key'
	]);	
    #update currency converter api
	Route::post('updateCurrencyConverter',[
		'uses' =>'SettingController@updateCurrencyConverter',
		'as'   =>'updateCurrencyConverter',
		'title'=>'تحديث Currency Converter key'
	]);	
		
	#update api google analytics
	Route::post('update-google-analytics',[
		'uses' =>'SettingController@GoogleAnalytics',
		'as'   =>'updategoogleanalytics',
		'title'=>'تحديث google analytics'
	]);

	#update api live chat
	Route::post('update-live-chat',[
		'uses' =>'SettingController@LiveChat',
		'as'   =>'updatelivechat',
		'title'=>'تحديث live chat'
	]);

	/*------------ End Of SettingController ----------*/
    /*------------ Start Of ExternalAppTokensController ----------*/
	// Route::get('externalApps',[
	// 	'uses' =>'ExternalAppTokensController@externalApps',
	// 	'as'   =>'externalApps',
	// 	'title'=>'تطبيقات مربوطة API', 
	// 	'icon' =>'<i class="glyphicon glyphicon-link"></i>',
	// 	'child'=>[
	// 		'createExternalApp',
	// 		'updateExternalApp',
	// 		'DeleteExternalApp'
	// 	]
	// ]);

	// #create coupon
	// Route::post('createExternalApp',[
	// 	'uses' =>'ExternalAppTokensController@createExternalApp',
	// 	'as'   =>'createExternalApp',
	// 	'title'=>'اضافة تطبيق'
	// ]);

	// #update coupon
	// Route::post('updateExternalApp',[
	// 	'uses' =>'ExternalAppTokensController@updateExternalApp',
	// 	'as'   =>'updateExternalApp',
	// 	'title'=>'تحديث بيانات تطبيق'
	// ]);


	// #delete user
	// Route::post('DeleteExternalApp',[
	// 	'uses' =>'ExternalAppTokensController@DeleteExternalApp',
	// 	'as'   =>'DeleteExternalApp',
	// 	'title'=>'حذف تطبيق'
	// ]);

    /*------------ End Of CouponsController ----------*/


});
	Route::get('dd',function(){
		 echo bcrypt(123456);
	});
/*-------------------------------End Of DashBoard--------------------------------*/


// Authentication Routes...
// Route::get('login', 'Auth\LoginController@LoginForm')->name('login');
// Route::post('login', 'Auth\LoginController@authenticate');
// // Registration Routes...
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('userRegister', 'Auth\RegisterController@userRegister')->name('userRegister');
// Route::post('ProviderRegister', 'Auth\RegisterController@ProviderRegister')->name('ProviderRegister');
// // Password Reset Routes...
// Route::get('forgetPassword', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// // Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::get('password/reset', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@createReset')->name('password.createReset');

//admin Login Route
Route::get('admin/login', 'Auth\LoginController@Adminloginform')->name('admin/login');
Route::get('ambassador/login', 'Auth\LoginController@Ambassadorloginform')->name('ambassador/login');
Route::post('admin/login/', 'Auth\LoginController@Adminlogin');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');


Route::get('remove-account-form', 'Auth\LoginController@removeAccountForm');
Route::post('remove-account', 'Auth\LoginController@removeAccount')->name('remove-account');


//api login
// Route::get('apislogin', 'Auth\LoginController@apisloginform')->name('apislogin');
// Route::post('apisLogin', 'Auth\LoginController@apisLogin')->name('apisLogin');
// Route::post('apisLogout', 'Auth\LoginController@apisLogout')->name('apisLogout');   

// Route::group(['prefix'=>'apis','middleware'=>['externalAppTokensAuth']],function(){
//  	Route::get('apisIndex', 'DashBoardController@apisIndex')->name('apisIndex');
//  	Route::get('ApiSetting', 'DashBoardController@ApiSetting')->name('ApiSetting');
 	
//  	Route::get('ApiOpenOrders', 'OrderController@ApiOpenOrders')->name('ApiOpenOrders');
//  	Route::get('ApiInprogressOrders', 'OrderController@ApiInprogressOrders')->name('ApiInprogressOrders');
//  	Route::get('ApiFinishedOrders', 'OrderController@ApiFinishedOrders')->name('ApiFinishedOrders');
//  	Route::get('ApiClosedOrders', 'OrderController@ApiClosedOrders')->name('ApiClosedOrders');
//  	Route::get('ApiShowOrder/{id?}', 'OrderController@ApiShowOrder')->name('ApiShowOrder');
 	

// });	

