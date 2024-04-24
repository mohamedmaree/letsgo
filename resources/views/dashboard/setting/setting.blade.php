@extends('dashboard.layout.master')
	@section('title')
	الاعدادات
	@endsection
<!-- style -->
@section('style')
<link href="{{asset('dashboard/fileinput/css/fileinput.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('dashboard/fileinput/css/fileinput-rtl.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('dashboard/bgrins/spectrum.css')}}" rel='stylesheet' >
<!-- Include Editor style. -->
<link href="https://cdn.jsdelivr.net/npm/froala-editor@2.9.1/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/froala-editor@2.9.1/css/froala_style.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/froala_style.min.css" rel="stylesheet" type="text/css" />
 
@endsection
<!-- /style -->
@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h6 class="panel-title">الاعدادات 
					<!-- <a href="https://marsooldelivery.4hoste.com/admin/login/{{Auth::user()->email}}" class="btn btn-primary">مرسول اوامر </a> -->
				</h6>
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="reload"></a></li>
                	</ul>
            	</div>
			</div>

			<div class="panel-body">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<!-- site setting -->
						<li class="active"><a href="#basic-tab1" data-toggle="tab">اعدادات الموقع</a></li>
						<!-- social media -->
						<li><a href="#basic-tab2" data-toggle="tab">مواقع التواصل</a></li>
						<!-- Ads -->
						<li><a href="#basic-tab8" data-toggle="tab">الاعلانات</a></li>						
						<!-- email and sms -->
						<li><a href="#basic-tab3" data-toggle="tab">الرسائل و الايميل</a></li>
						<!-- copyright -->
						<li><a href="#basic-tab4" data-toggle="tab">الشروط والأحكام </a></li>
						<!-- email template -->
						<li><a href="#basic-tab5" data-toggle="tab">قالب الايميل  </a></li>
						<!-- notification -->
						<li><a href="#basic-tab6" data-toggle="tab">الاشعارات </a></li>
						<!-- api -->
						<li><a href="#basic-tab7" data-toggle="tab">API </a></li>
					</ul>

					<div class="tab-content">

						<!-- site setting -->
						<div class="tab-pane active" id="basic-tab1">
							<div class="row">
								<!-- main setting -->
								<div class="col-md-6">
										<div class="panel panel-flat">
											<div class="panel-heading">
												<h5 class="panel-title">اعدادات عامه</h5>
												<div class="heading-elements">
													<ul class="icons-list">
								                		<li><a data-action="collapse"></a></li>
								                		<li><a data-action="reload"></a></li>
								                	</ul>
							                	</div>
											</div>

											<div class="panel-body">
												<form action="{{route('updatesitesetting')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
													{{csrf_field()}}
													<div class="form-group">
														<label class="col-lg-3 control-label">البريد الالكتروني:</label>
														<div class="col-lg-9">
															<input type="email" value="{{setting('site_email')}}" name="site_email" class="form-control" placeholder="البريد الالكتروني للتطبيق">
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">رقم الهاتف:</label>
														<div class="col-lg-9">
															<input type="text" value="{{setting('site_phone')}}" name="site_phone" class="form-control" placeholder="رقم الهاتف للتطبيق">
														</div>
													</div>	
													<div class="form-group">
														<label class="col-lg-3 control-label">رقم دعم العملاء:</label>
														<div class="col-lg-9">
															<input type="text" value="{{setting('clients_support_phone')}}" name="clients_support_phone" class="form-control" placeholder="رقم دعم العملاء">
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">رقم دعم القادة:</label>
														<div class="col-lg-9">
															<input type="text" value="{{setting('captains_support_phone')}}" name="captains_support_phone" class="form-control" placeholder="رقم دعم القادة">
														</div>
													</div>													
													<div class="form-group">
														<label class="col-lg-3 control-label">اسم التطبيق:</label>
														<div class="col-lg-9">
															<input type="text" value="{{setting('site_title')}}" name="site_name" class="form-control" placeholder="اسم الموقع">
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">العملة بالعربية:</label>
														<div class="col-lg-9">
															<input type="text" value="{{setting('site_currency_ar')}}" name="site_currency_ar" class="form-control" placeholder="العملة بالعربية">
														</div>
													</div>
                                                    <div class="form-group">
														<label class="col-lg-3 control-label">العملة بالإنجليزية:</label>
														<div class="col-lg-9">
															<input type="text" value="{{setting('site_currency_en')}}" name="site_currency_en" class="form-control" placeholder="العملة بالنجليزية">
														</div>
													</div>
                                                    <div class="form-group" >
														<label class="col-lg-3 control-label">الرصيد عند التسجيل:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('free_balance')}}" name="free_balance" class="form-control" placeholder="10" min="0"> 
														</div>
														<div class="col-lg-2">
															وحده
														</div>
													</div>																										
													<div class="form-group">
														<label class="col-lg-3 control-label">نسبة التطبيق من التوصيلة:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('site_percentage')}}" name="site_percentage" class="form-control" placeholder="نسبة التطبيق" min="0" max="100">
														</div>
														<div class="col-lg-2">
															%
														</div>
													</div>	
													<div class="form-group">
														<label class="col-lg-3 control-label"> ضريبة القيمة المضافة:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('added_value')}}" name="added_value" class="form-control" placeholder="ضريبة القيمة المضافة" min="0" max="100" step="0.01">
														</div>
														<div class="col-lg-2">
															%
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label"> رسوم تحويل stc :</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('stc_percentage')}}" name="stc_percentage" class="form-control" placeholder="رسوم تحويل stc :" min="0" max="100" step="0.1">
														</div>
														<div class="col-lg-2">
															%
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-3 control-label">عمولة وصل:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('wasl_value')}}" name="wasl_value" class="form-control" placeholder="عمولة وصل" min="0" max="100" step="0.001">
														</div>
														<div class="col-lg-2">
															وحدة
														</div>
													</div>
                                                    <div class="form-group">
														<label class="col-lg-3 control-label">نطاق بحث التطبيق الافتراضي:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('distance')}}" name="distance" class="form-control" placeholder="20" min="0"> 
														</div>
														<div class="col-lg-2">
															كم
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">نطاق بحث التطبيق الادني:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('min_distance')}}" name="min_distance" class="form-control" placeholder="20" min="0"> 
														</div>
														<div class="col-lg-2">
															كم
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">نطاق بحث التطبيق الاقصي:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('max_distance')}}" name="max_distance" class="form-control" placeholder="20" min="0"> 
														</div>
														<div class="col-lg-2">
															كم
														</div>
													</div>
													<!-- <div class="form-group">
														<label class="col-lg-3 control-label">سعر الكيلومتر (توصيل طعام):</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('km_price')}}" name="km_price" class="form-control" placeholder="1" min="0" step="0.01"> 
														</div>
														<div class="col-lg-2">
															وحده
														</div>
													</div> -->
													<!-- <div class="form-group">
														<label class="col-lg-3 control-label">الحد الادني لسعر التوصيل (طعام):</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('min_order_price')}}" name="min_order_price" class="form-control" placeholder="1" min="0" step="0.01"> 
														</div>
														<div class="col-lg-2">
															وحده
														</div>
													</div> -->
													<!-- <div class="form-group">
														<label class="col-lg-3 control-label">سعر الغاء العميل للطلب (طعام):</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('client_cancel')}}" name="client_cancel" class="form-control" placeholder="1" min="0" step="0.01"> 
														</div>
														<div class="col-lg-2">
															وحده
														</div>
													</div> -->
													<!-- <div class="form-group">
														<label class="col-lg-3 control-label">سعر الغاء القائد للطلب (طعام):</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('captain_cancel')}}" name="captain_cancel" class="form-control" placeholder="1" min="0" step="0.01"> 
														</div>
														<div class="col-lg-2">
															وحده
														</div>
													</div> -->
													<div class="form-group">
														<label class="col-lg-3 control-label">بداية الدورة المالية:</label>
														<div class="col-lg-9">
															<?php $start_day = setting('start_day');?>
															<select class="form-control" name="start_day">
																<option value="Saturday" <?= ($start_day == 'Saturday')? "selected='selected'":'';?> >السبت </option>
																<option value="Sunday" <?= ($start_day == 'Sunday')? "selected='selected'":'';?> >الأحد</option>
																<option value="Monday" <?= ($start_day == 'Monday')? "selected='selected'":'' ;?> >الأثنين</option>
																<option value="Tuesday" <?= ($start_day == 'Tuesday')? "selected='selected'":'' ;?> >الثلاثاء</option>
																<option value="Wednesday" <?= ($start_day == 'Wednesday')? "selected='selected'":'' ;?> >الأربعاء</option>
																<option value="Thursday" <?= ($start_day == 'Thursday')? "selected='selected'":'' ;?> >الخميس</option>
																<option value="Friday" <?= ($start_day == 'Friday')? "selected='selected'":'' ;?> >الجمعة</option>
															</select>
														</div>
													</div>
													<hr>
                                                    <div class="form-group">
														<label class="col-lg-3 control-label">عدد الطلبات بساعة الذروة الاولي:</label>
														<div class="col-lg-6">
															<input type="number" value="{{setting('first_rush_hour')}}" name="first_rush_hour" class="form-control" placeholder="30" min="0"> 
														</div>
														<div class="col-lg-3">
															طلب داخل نطاقك
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">مضاعفة سعر المشوار:</label>
														<div class="col-lg-6">
															<input type="number" value="{{setting('first_rush_hour_percentage')}}" name="first_rush_hour_percentage" class="form-control" placeholder="30" min="0"> 
														</div>
														<div class="col-lg-3">
															%
														</div>
													</div>
                                                    <div class="form-group">
														<label class="col-lg-3 control-label">عدد الطلبات بساعة الذروة الثانية:</label>
														<div class="col-lg-6">
															<input type="number" value="{{setting('second_rush_hour')}}" name="second_rush_hour" class="form-control" placeholder="30" min="0"> 
														</div>
														<div class="col-lg-3">
															طلب داخل نطاقك
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">مضاعفة سعر المشوار:</label>
														<div class="col-lg-6">
															<input type="number" value="{{setting('second_rush_hour_percentage')}}" name="second_rush_hour_percentage" class="form-control" placeholder="30" min="0"> 
														</div>
														<div class="col-lg-3">
															%
														</div>
													</div>
                                                    <div class="form-group">
														<label class="col-lg-3 control-label">عدد الطلبات بساعة الذروة الثالثة:</label>
														<div class="col-lg-6">
															<input type="number" value="{{setting('third_rush_hour')}}" name="third_rush_hour" class="form-control" placeholder="30" min="0"> 
														</div>
														<div class="col-lg-3">
															طلب داخل نطاقك
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">مضاعفة سعر المشوار:</label>
														<div class="col-lg-6">
															<input type="number" value="{{setting('third_rush_hour_percentage')}}" name="third_rush_hour_percentage" class="form-control" placeholder="30" min="0"> 
														</div>
														<div class="col-lg-3">
															%
														</div>
													</div>													
													<!-- <hr/>
                                                    <div class="form-group">
														<label class="col-lg-3 control-label">الحد الأقصي للانسحاب:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('max_withdraw_day')}}" name="max_withdraw_day" class="form-control" placeholder="4" min="1"> 
														</div>
														<div class="col-lg-2">
															مرات باليوم
														</div>
													</div>
                                                    <div class="form-group">
														<label class="col-lg-3 control-label">عدد ساعات الحظر لتخطي الانسحابات باليوم:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('withdraw_block_hours')}}" name="withdraw_block_hours" class="form-control" placeholder="4" min="0"> 
														</div>
														<div class="col-lg-2">
															ساعات
														</div>
													</div>
                                                    <div class="form-group">
														<label class="col-lg-3 control-label">حذف الطلب المعلق بعد:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('order_close_time')}}" name="order_close_time" class="form-control" placeholder="24" min="0"> 
														</div>
														<div class="col-lg-2">
															ساعات
														</div>
													</div> -->
													<!-- <hr/>																																																																	 -->
									        		<div class="form-group">
														<label class="col-lg-10 control-label">السماح بوجود دين علي القائد:</label>
															<input class="checkbox" type="checkbox" name="allow_debt_captain" id="allow_debt_captain" {{(setting('allow_debt_captain') == 'true')? 'checked':''}} > 
															<div class="col-lg-2">
															<i class="icon-checkbox"></i>
															</div> 
									        		</div>													
                                                    <div class="form-group" style="display:{{(setting('allow_debt_captain') == 'true')? 'block':'none'}};" id="max_debt_captain">
														<label class="col-lg-3 control-label">الحد الأقصي للدين:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('max_debt_captain')}}" name="max_debt_captain" class="form-control" placeholder="10" min="0"> 
														</div>
														<div class="col-lg-2">
															{{setting('site_currency_ar')}}
														</div>
													</div>

									        		<div class="form-group">
														<label class="col-lg-10 control-label">السماح بوجود دين علي العميل:</label>
															<input class="checkbox" type="checkbox" name="allow_debt_client" id="allow_debt_client" {{(setting('allow_debt_client') == 'true')? 'checked':''}} > 
															<div class="col-lg-2">
															<i class="icon-checkbox"></i>
															</div> 
									        		</div>													
                                                    <div class="form-group" style="display:{{(setting('allow_debt_client') == 'true')? 'block':'none'}};" id="max_debt_client">
														<label class="col-lg-3 control-label">الحد الأقصي للدين:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('max_debt_client')}}" name="max_debt_client" class="form-control" placeholder="10" min="0"> 
														</div>
														<div class="col-lg-2">
															{{setting('site_currency_ar')}}
														</div>
													</div>													

                                                    <div class="form-group">
														<label class="col-lg-3 control-label">الحد الأقصي للمبلغ الزائد عن تكلفة الرحلة:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('max_tips')}}" name="max_tips" class="form-control" placeholder="50" min="0"> 
														</div>
														<div class="col-lg-2">
															{{setting('site_currency_ar')}}
														</div>
													</div>													
                                                    <hr/>
                                                    <div class="form-group">
														<label class="col-lg-3 control-label">مكافأة دعوة عميل:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('invite_client_balance')}}" name="invite_client_balance" class="form-control" placeholder="15" min="0"> 
														</div>
														<div class="col-lg-2">
															وحده
														</div>
													</div>
                                                    <div class="form-group">
														<label class="col-lg-3 control-label">مكافأة دعوة قائد:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('invite_captain_balance')}}" name="invite_captain_balance" class="form-control" placeholder="15" min="0"> 
														</div>
														<div class="col-lg-2">
															وحده
														</div>
													</div>	

                                                    <hr/>
                                                    <div class="form-group">
														<label class="col-lg-3 control-label">سفراء القادة</label>
													</div>	
													<div class="form-group">
														<label class="col-lg-3 control-label"> عدد الرحلات:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('ambassador_num_orders')}}" name="ambassador_num_orders" class="form-control" placeholder="15" min="0"> 
														</div>
														<div class="col-lg-2">
															رحلة
														</div>
													</div>	
													<div class="form-group">
														<label class="col-lg-3 control-label"> خلال:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('ambassador_num_days')}}" name="ambassador_num_days" class="form-control" placeholder="15" min="0"> 
														</div>
														<div class="col-lg-2">
															يوم
														</div>
													</div>	
													<div class="form-group">
														<label class="col-lg-3 control-label"> المكافآة:</label>
														<div class="col-lg-7">
															<input type="number" value="{{setting('ambassador_balance')}}" name="ambassador_balance" class="form-control" placeholder="15" min="0" step="0.01"> 
														</div>
														<div class="col-lg-2">
															ريال
														</div>
													</div>	
													<!-- <div class="form-group">
														<label class="col-lg-3 control-label">دليل القائد:</label>
														<div class="col-lg-6 img-appendblock">
															 <video width="320" height="240" controls>
															  <source src="{{asset('dashboard/uploads/setting/guide_video/'.setting('guide_video'))}}" type="video/mp4">
															  <source src="{{asset('dashboard/uploads/setting/guide_video/'.setting('guide_video'))}}" type="video/ogg">
															  Your browser does not support the video tag.
															</video> 															
															<input type="file" name="guide_video">
														</div>
													</div> -->

                                                    <hr/>
													<div class="form-group">
														<label class="col-lg-3 control-label">لوجو الموقع:</label>
														<div class="col-lg-6 img-appendblock">
															<img class="photo" src="{{asset('dashboard/uploads/setting/site_logo/'.setting('site_logo'))}}" title="اختيار لوجو" onclick="changeSitelogo()" style="height: 210px; width: 210px;cursor: pointer;border-radius:10px">
															<input type="file" name="logo" class="hidden">
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label"> خلفية صفحة الدفع:</label>
														<div class="col-lg-6 img-appendblock">
															<img class="cover" src="{{asset('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))}}" title="اختيار صورة" onclick="changePaymentPageBackground()" style="height: 210px; width: 210px;cursor: pointer;border-radius:10px">
															<input type="file" name="payment_page_background" style="display: none;">
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-3 control-label"> رسالة قبول طلب العمل كقائد: </label>
														<div class="col-lg-9">
															<textarea rows="5" cols="5" name="agree_message" class="form-control" placeholder="رسالة قبول طلب العمل كقائد:">{{setting('agree_message')}}</textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label"> رسالة رفض طلب العمل كقائد: </label>
														<div class="col-lg-9">
															<textarea rows="5" cols="5" name="refuse_message" class="form-control" placeholder="رسالة رفض طلب العمل كقائد:">{{setting('refuse_message')}}</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار طلب جديد </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="newOrder_msg_ar" class="form-control" placeholder="نص اشعار طلب جديد بالعربية">{{setting('newOrder_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="newOrder_msg_en" class="form-control" placeholder="نص اشعار طلب جديد بالانجليزية">{{setting('newOrder_msg_en')}}</textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار ارفاق رحلة </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="attachOrder_msg_ar" class="form-control" placeholder="نص اشعار ارفاق رحلة بالعربية">{{setting('attachOrder_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="attachOrder_msg_en" class="form-control" placeholder="نص اشعار ارفاق رحلة بالانجليزية">{{setting('attachOrder_msg_en')}}</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار قبول رحلة </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="AcceptOrder_msg_ar" class="form-control" placeholder="نص اشعار قبول رحلة بالعربية">{{setting('AcceptOrder_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="AcceptOrder_msg_en" class="form-control" placeholder="نص اشعار قبول رحلة بالانجليزية">{{setting('AcceptOrder_msg_en')}}</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار قبول كابتن </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="activeCaptain_msg_ar" class="form-control" placeholder="نص اشعار قبول كابتن بالعربية">{{setting('activeCaptain_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="activeCaptain_msg_en" class="form-control" placeholder="نص اشعار قبول كابتن بالانجليزية">{{setting('activeCaptain_msg_en')}}</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار حظر حساب </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="block_user_msg_ar" class="form-control" placeholder="نص اشعار حظر حساب بالعربية">{{setting('block_user_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="block_user_msg_en" class="form-control" placeholder="نص اشعار حظر حساب بالانجليزية">{{setting('block_user_msg_en')}}</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار حذف حساب </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="delete_user_msg_ar" class="form-control" placeholder="نص اشعار حذف حساب بالعربية">{{setting('delete_user_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="delete_user_msg_en" class="form-control" placeholder="نص اشعار حذف حساب بالانجليزية">{{setting('delete_user_msg_en')}}</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار في الطريق </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="inWayToOrder_msg_ar" class="form-control" placeholder="نص اشعار في الطريق بالعربية">{{setting('inWayToOrder_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="inWayToOrder_msg_en" class="form-control" placeholder="نص اشعار في الطريق بالانجليزية">{{setting('inWayToOrder_msg_en')}}</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار وصل لاصطحابك </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="arrivedToOrder_msg_ar" class="form-control" placeholder="نص اشعار وصل لاصطحابك بالعربية">{{setting('arrivedToOrder_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="arrivedToOrder_msg_en" class="form-control" placeholder="نص اشعار وصل لاصطحابك بالانجليزية">{{setting('arrivedToOrder_msg_en')}}</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار بدأ الرحلة </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="startJourney_msg_ar" class="form-control" placeholder="نص اشعار بدأ الرحلة بالعربية">{{setting('startJourney_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="startJourney_msg_en" class="form-control" placeholder="نص اشعار بدأ الرحلة بالانجليزية">{{setting('startJourney_msg_en')}}</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار الانسحاب من رحلة </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="withdrawOrder_msg_ar" class="form-control" placeholder="نص اشعار الانسحاب من رحلة بالعربية">{{setting('withdrawOrder_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="withdrawOrder_msg_en" class="form-control" placeholder="نص اشعار الانسحاب من رحلة بالانجليزية">{{setting('withdrawOrder_msg_en')}}</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار انهاء رحلة </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="finishSimpleOrder_msg_ar" class="form-control" placeholder="نص اشعار انهاء رحلة بالعربية">{{setting('finishSimpleOrder_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="finishSimpleOrder_msg_en" class="form-control" placeholder="نص اشعار انهاء رحلة بالانجليزية">{{setting('finishSimpleOrder_msg_en')}}</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار اضافة رصيد </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="addedBalance_msg_ar" class="form-control" placeholder="نص اشعار اضافة رصيد بالعربية">{{setting('addedBalance_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="addedBalance_msg_en" class="form-control" placeholder="نص اشعار اضافة رصيد بالانجليزية">{{setting('addedBalance_msg_en')}}</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار تآكيد انهاء الرحلة </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="ConfirmfinishSimpleOrder_msg_ar" class="form-control" placeholder="نص اشعار  تآكيد انهاء الرحلة بالعربية">{{setting('ConfirmfinishSimpleOrder_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="ConfirmfinishSimpleOrder_msg_en" class="form-control" placeholder="نص اشعار  تآكيد انهاء الرحلة بالانجليزية">{{setting('ConfirmfinishSimpleOrder_msg_en')}}</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-2 control-label"> نص اشعار إلغاء الرحلة </label>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="cancelOrder_msg_ar" class="form-control" placeholder="نص اشعار إلغاء الرحلة بالعربية">{{setting('cancelOrder_msg_ar')}}</textarea>
														</div>
														<div class="col-lg-5">
															<textarea rows="5" cols="5" name="cancelOrder_msg_en" class="form-control" placeholder="نص اشعار إلغاء الرحلة بالانجليزية">{{setting('cancelOrder_msg_en')}}</textarea>
														</div>
													</div>

													<div class="text-left">
														<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
													</div>
												</form>
											</div>
										</div>
								</div>
								<!-- seo setting -->
								<div class="col-md-6">
									{{csrf_field()}}
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">SEO</h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>
										<div class="panel-body">
											<form action="{{route('updateseo')}}" method="post" class="form-horizontal">
												{{csrf_field()}}
												<div class="form-group">
													<label class="col-lg-3 control-label">وصف التطبيق :</label>
													<div class="col-lg-9">
														<textarea rows="5" cols="5" name="site_description" class="form-control" placeholder="وصف الموقع">{{setting('site_description')}}</textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الكلمات الدلاليه :</label>
													<div class="col-lg-9">
														<textarea rows="5" cols="5" name="site_tagged" class="form-control" placeholder="الكلمات الآفتتاحيه">{{setting('site_tagged')}}</textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">حقوق التطبيق :</label>
													<div class="col-lg-9">
														<textarea rows="5" cols="5" name="site_copyrigth" class="form-control" placeholder="حقوق الشركه">{{setting('site_copyrigth')}}</textarea>
													</div>
												</div>

												<div class="text-left">
													<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- social media -->
						<div class="tab-pane" id="basic-tab2">
								<div class="col-md-12">
									{{csrf_field()}}
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">مواقع التواصل </h5>
											<div class="heading-elements">
												<ul class="icons-list">
													<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> اضافة موقع </button>
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">

											<table class="table datatable-basic">
												<thead>
													<tr>
														<th>الوجو</th>
														<th>الاسم</th>
														<th>اللينك</th>
														<th>تاريخ الاضافه</th>
														<th>التحكم</th>
													</tr>
												</thead>
												<tbody>
													@foreach($socials as $social)
														<tr>
															<td><img src="{{asset('dashboard/uploads/socialicon/'.$social->logo)}}" style="width:40px;height: 40px" class="img-circle" alt=""></td>
															<td>{{$social->name}}</td>
															<td>{{str_limit($social->link,30)}}</td>
															<td>{{$social->created_at->diffForHumans()}}</td>
															<td>
															<ul class="icons-list">
																<li class="dropdown">
																	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
																		<i class="icon-menu9"></i>
																	</a>

																	<ul class="dropdown-menu dropdown-menu-right">
																		<li>
																			<a href="#" data-toggle="modal" data-target="#exampleModal2" class="openEditmodal" 
																			data-id  ="{{$social->id}}" 
																			data-name="{{$social->name}}"
																			data-link="{{$social->link}}"
																			data-logo="{{$social->logo}}">
																			<i class="icon-pencil7"></i>تعديل
																			</a>
																		</li>
																		<form action="{{route('deletesocial')}}" method="post">
																			{{csrf_field()}}
																			<input type="hidden" name="id" value="{{$social->id}}">
																			<li><button type="submit" class="generalDelete reset"><i class="icon-trash"></i>حذف</button></li>
																		</form>
																	</ul>
																</li>
															</ul>
															</td>
														</tr>
													@endforeach
													@if(count($socials) == 0) <tr><td></td><td></td><td>لا توجد مواقع تواصل</td></tr>  @endif
												</tbody>
											</table>

										</div>
									</div>
								</div>
						</div>

						<!-- Ads -->
						<div class="tab-pane" id="basic-tab8">
								<div class="col-md-12">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">الاعلانات </h5>
											<div class="heading-elements">
												<ul class="icons-list">
													<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#exampleModal3"><i class="icon-plus3"></i> اضافة اعلان </button>
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">

											<table class="table datatable-basic">
												<thead>
													<tr>
														<th>صورة الاعلان</th>
														<th>الرابط</th>
														<th>تاريخ الانتهاء</th>
														<th>ملاحظة</th>
														<th>تاريخ الاضافه</th>
														<th>التحكم</th>
													</tr>
												</thead>
												<tbody>
													@foreach($ads as $ad)
														<tr>
															<td><img src="{{asset('dashboard/uploads/ads/'.$ad->image)}}" style="width:60px;height: 60px" class="img-circle" alt=""></td>
															<td>@if($ad->link)
																<a href="{{$ad->link}}" target="blank">مشاهدة </a>
																@endif
															</td>
															<td>{{$ad->end_at}}</td>
															<td>{{$ad->notes}}</td>
															<td>{{$ad->created_at}}</td>
															<td>
															<ul class="icons-list">
																<li class="dropdown">
																	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
																		<i class="icon-menu9"></i>
																	</a>

																	<ul class="dropdown-menu dropdown-menu-right">
																		<li>
																			<a href="#" data-toggle="modal" data-target="#exampleModal4" class="openEditmodal2" 
																				data-id="{{$ad->id}}" 
																				data-adendat="{{$ad->end_at}}"
																				data-adlink="{{$ad->link}}"
																				data-adnotes="{{$ad->notes}}"
																				data-adimage="{{$ad->image}}">
																			<i class="icon-pencil7"></i>تعديل
																			</a>
																		</li>
																		<form action="{{route('deleteAd')}}" method="post">
																			{{csrf_field()}}
																			<input type="hidden" name="id" value="{{$ad->id}}">
																			<li><button type="submit" class="generalDelete reset"><i class="icon-trash"></i>حذف</button></li>
																		</form>
																	</ul>
																</li>
															</ul>
															</td>
														</tr>
													@endforeach
													@if(count($ads) == 0) <tr><td></td><td></td><td>لا توجد اعلانات</td></tr>  @endif
												</tbody>
											</table>

										</div>
									</div>
								</div>
						</div>

						<!-- email and sms -->
						<div class="tab-pane" id="basic-tab3">
							<div class="row">

								<!-- smtp setting -->
								<div class="col-md-6">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">SMTP</h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">
											<form action="{{route('updatesmtp')}}" method="post" class="form-horizontal">
												{{csrf_field()}}
												<div class="form-group">
													<div class="col-lg-12">
						                                <label class="checkbox" style="display:block">
						                                <input type="checkbox" name="smtp_active" {{($smtp->active == 'true')?'checked':''}}/>
						                                <i class="icon-checkbox"></i>
						                                <label style="padding-right:8px">تنشيط</label>
						                                </label> 													
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-3 control-label">اسم المستخدم :</label>
													<div class="col-lg-9">
														<input type="text" name="smtp_username" value="{{isset($smtp->username)?$smtp->username:''}}" placeholder="اسم المستخدم" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الرقم السرى :</label>
													<div class="col-lg-9">
														<input type="password" name="smtp_password" value="{{isset($smtp->password)?$smtp->password:''}}" placeholder="الرقم السرى" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الايميل المرسل :</label>
													<div class="col-lg-9">
														<input type="text" name="smtp_sender_email" value="{{isset($smtp->sender_email)?$smtp->sender_email:''}}" placeholder="الايميل المرسل" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الاسم المرسل :</label>
													<div class="col-lg-9">
														<input type="text" name="smtp_sender_name" value="{{isset($smtp->sender_name)?$smtp->sender_name:''}}" placeholder="الاسم المرسل" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">البورت :</label>
													<div class="col-lg-9">
														<input type="number" name="smtp_port" value="{{isset($smtp->port)?$smtp->port:''}}" placeholder="البورت" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الهوست :</label>
													<div class="col-lg-9">
														<input type="text" name="smtp_host" value="{{isset($smtp->host)?$smtp->host:''}}" placeholder="الهوست" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">التشفير :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($smtp->encryption)?$smtp->encryption:''}}" name="smtp_encryption" placeholder="التشفير" class="form-control">
													</div>
												</div>

												<div class="text-left">
													<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
												</div>
											</form>
										</div>
									</div>
								</div>


								<!-- Mobily setting -->
								<div class="col-md-6">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">Mobily</h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">
											<form action="{{route('updatemobily')}}" method="post" class="form-horizontal">
												{{csrf_field()}}
												<div class="form-group">
													<div class="col-lg-12">
						                                <label class="checkbox" style="display:block">
						                                <input type="checkbox" name="mobily_active" {{($mobily->active == 'true')?'checked':''}}/>
						                                <i class="icon-checkbox"></i>
						                                <label style="padding-right:8px">تنشيط</label>
						                                </label> 													
													</div>
												</div>												
												<div class="form-group">
													<label class="col-lg-3 control-label">المستخدم :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($mobily->number)?$mobily->number:''}}" name="mobily_number" placeholder="المستخدم" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الرقم السرى :</label>
													<div class="col-lg-9">
														<input type="password" value="{{isset($mobily->password)?$mobily->password:''}}" name="mobily_password" placeholder="الرقم السرى" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">اسم الراسل :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($mobily->sender_name)?$mobily->sender_name:''}}" name="mobily_sender_name" placeholder="اسم الراسل" class="form-control">
													</div>
												</div>

												<div class="text-left">
													<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
												</div>
											</form>
										</div>
									</div>
								</div>

								<!-- yamamah setting -->
								<div class="col-md-6">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">yamamah</h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">
											<form action="{{route('updateyamama')}}" method="post" class="form-horizontal">
												{{csrf_field()}}
												<div class="form-group">
													<div class="col-lg-12">
						                                <label class="checkbox" style="display:block">
						                                <input type="checkbox" name="yamamah_active" {{($yamamah->active == 'true')?'checked':''}}/>
						                                <i class="icon-checkbox"></i>
						                                <label style="padding-right:8px">تنشيط</label>
						                                </label> 													
													</div>
												</div>														
												<div class="form-group">
													<label class="col-lg-3 control-label">المستخدم :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($yamamah->number)?$yamamah->number:''}}" name="yamamah_number" placeholder="المستخدم" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الرقم السرى :</label>
													<div class="col-lg-9">
														<input type="password" value="{{isset($yamamah->password)?$yamamah->password:''}}" name="yamamah_password" placeholder="الرقم السرى" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">اسم الراسل :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($yamamah->sender_name)?$yamamah->sender_name:''}}" name="yamamah_sender_name" placeholder="اسم الراسل" class="form-control">
													</div>
												</div>

												<div class="text-left">
													<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
												</div>
											</form>
										</div>
									</div>
								</div>

								<!-- Oursms setting -->
								<div class="col-md-6">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">Our Sms</h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">
											<form action="{{route('updateoursms')}}" method="post" class="form-horizontal">
												{{csrf_field()}}
												<div class="form-group">
													<div class="col-lg-12">
						                                <label class="checkbox" style="display:block">
						                                <input type="checkbox" name="oursms_active" {{($oursms->active == 'true')?'checked':''}}/>
						                                <i class="icon-checkbox"></i>
						                                <label style="padding-right:8px">تنشيط</label>
						                                </label> 													
													</div>
												</div>														
												<div class="form-group">
													<label class="col-lg-3 control-label">المستخدم :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($oursms->number)?$oursms->number:''}}" name="oursms_number" placeholder="المستخدم" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الرقم السرى :</label>
													<div class="col-lg-9">
														<input type="password" value="{{isset($oursms->password)?$oursms->password:''}}" name="oursms_password" placeholder="الرقم السرى" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">اسم الراسل :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($oursms->sender_name)?$oursms->sender_name:''}}" name="oursms_sender_name" placeholder="اسم الراسل" class="form-control">
													</div>
												</div>

												<div class="text-left">
													<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
												</div>
											</form>
										</div>
									</div>
								</div>
<!-- Hisms setting -->
									<div class="col-md-6">
										<div class="panel panel-flat">
											<div class="panel-heading">
												<h5 class="panel-title">Hi Sms</h5>
												<div class="heading-elements">
													<ul class="icons-list">
														<li><a data-action="collapse"></a></li>
														<li><a data-action="reload"></a></li>
													</ul>
												</div>
											</div>

											<div class="panel-body">
												<form action="{{route('updatehisms')}}" method="post" class="form-horizontal">
													{{csrf_field()}}
													<div class="form-group">
														<div class="col-lg-12">
															<label class="checkbox" style="display:block">
																<input type="checkbox" name="hisms_active" {{($hisms->active == 'true')?'checked':''}}/>
																<i class="icon-checkbox"></i>
																<label style="padding-right:8px">تنشيط</label>
															</label>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">المستخدم :</label>
														<div class="col-lg-9">
															<input type="text" value="{{isset($hisms->number)?$hisms->number:''}}" name="hisms_number" placeholder="المستخدم" class="form-control">
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-3 control-label">الرقم السرى :</label>
														<div class="col-lg-9">
															<input type="text" value="{{isset($hisms->password)?$hisms->password:''}}" name="hisms_password" placeholder="الرقم السرى" class="form-control">
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-3 control-label">اسم الراسل :</label>
														<div class="col-lg-9">
															<input type="text" value="{{isset($hisms->sender_name)?$hisms->sender_name:''}}" name="hisms_sender_name" placeholder="اسم الراسل" class="form-control">
														</div>
													</div>

													<div class="text-left">
														<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
													</div>
												</form>
											</div>
										</div>
									</div>

									<!-- 4jawaly setting -->
									<div class="col-md-6">
										<div class="panel panel-flat">
											<div class="panel-heading">
												<h5 class="panel-title">4Jawaly </h5>
												<div class="heading-elements">
													<ul class="icons-list">
														<li><a data-action="collapse"></a></li>
														<li><a data-action="reload"></a></li>
													</ul>
												</div>
											</div>

											<div class="panel-body">
												<form action="{{route('updatejawaly')}}" method="post" class="form-horizontal">
													{{csrf_field()}}
													<div class="form-group">
														<div class="col-lg-12">
															<label class="checkbox" style="display:block">
																<input type="checkbox" name="jawaly_active" {{($jawaly->active == 'true')?'checked':''}}/>
																<i class="icon-checkbox"></i>
																<label style="padding-right:8px">تنشيط</label>
															</label>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">المستخدم :</label>
														<div class="col-lg-9">
															<input type="text" value="{{isset($jawaly->number)?$jawaly->number:''}}" name="jawaly_number" placeholder="المستخدم" class="form-control">
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-3 control-label">الرقم السرى :</label>
														<div class="col-lg-9">
															<input type="text" value="{{isset($jawaly->password)?$jawaly->password:''}}" name="jawaly_password" placeholder="الرقم السرى" class="form-control">
														</div>
													</div>

													<div class="form-group">
														<label class="col-lg-3 control-label">اسم الراسل :</label>
														<div class="col-lg-9">
															<input type="text" value="{{isset($jawaly->sender_name)?$jawaly->sender_name:''}}" name="jawaly_sender_name" placeholder="اسم الراسل" class="form-control">
														</div>
													</div>

													<div class="text-left">
														<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								<!-- unifonic setting -->
								<div class="col-md-6">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">Unifonic</h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">
											<form action="{{route('updateunifonic')}}" method="post" class="form-horizontal">
												{{csrf_field()}}
												<div class="form-group">
													<div class="col-lg-12">
						                                <label class="checkbox" style="display:block">
						                                <input type="checkbox" name="unifonic_active" {{($unifonic->active == 'true')?'checked':''}}/>
						                                <i class="icon-checkbox"></i>
						                                <label style="padding-right:8px">تنشيط</label>
						                                </label> 													
													</div>
												</div>														
												<div class="form-group">
													<label class="col-lg-3 control-label">المستخدم :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($unifonic->number)?$unifonic->number:''}}" name="unifonic_number" placeholder="المستخدم" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الرقم السرى :</label>
													<div class="col-lg-9">
														<input type="password" value="{{isset($unifonic->password)?$unifonic->password:''}}" name="unifonic_password" placeholder="الرقم السرى" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">اسم الراسل :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($unifonic->sender_name)?$unifonic->sender_name:''}}" name="unifonic_sender_name" placeholder="اسم الراسل" class="form-control">
													</div>
												</div>

												<div class="text-left">
													<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
												</div>
											</form>
										</div>
									</div>
								</div>

								<!-- gateway setting -->
								<div class="col-md-6">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">Gateway SMS</h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">
											<form action="{{route('updategateway')}}" method="post" class="form-horizontal">
												{{csrf_field()}}
												<div class="form-group">
													<div class="col-lg-12">
						                                <label class="checkbox" style="display:block">
						                                <input type="checkbox" name="gateway_active" {{($gateway->active == 'true')?'checked':''}}/>
						                                <i class="icon-checkbox"></i>
						                                <label style="padding-right:8px">تنشيط</label>
						                                </label> 													
													</div>
												</div>														
												<div class="form-group">
													<label class="col-lg-3 control-label">المستخدم :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($gateway->number)?$gateway->number:''}}" name="gateway_number" placeholder="المستخدم" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الرقم السرى :</label>
													<div class="col-lg-9">
														<input type="password" value="{{isset($gateway->password)?$gateway->password:''}}" name="gateway_password" placeholder="الرقم السرى" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">اسم الراسل :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($gateway->sender_name)?$gateway->sender_name:''}}" name="gateway_sender_name" placeholder="اسم الراسل" class="form-control">
													</div>
												</div>

												<div class="text-left">
													<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
												</div>
											</form>
										</div>
									</div>
								</div>

								<!-- msegat setting -->
								<div class="col-md-6">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">msegat SMS</h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">
											<form action="{{route('updatemsegat')}}" method="post" class="form-horizontal">
												{{csrf_field()}}
												<div class="form-group">
													<div class="col-lg-12">
						                                <label class="checkbox" style="display:block">
						                                <input type="checkbox" name="msegat_active" {{( $msegat->active == 'true')?'checked':''}}/>
						                                <i class="icon-checkbox"></i>
						                                <label style="padding-right:8px">تنشيط</label>
						                                </label> 													
													</div>
												</div>														
												<div class="form-group">
													<label class="col-lg-3 control-label">المستخدم :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($msegat->number)?$msegat->number:''}}" name="msegat_number" placeholder="المستخدم" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الرقم السرى :</label>
													<div class="col-lg-9">
														<input type="password" value="{{isset($msegat->password)?$msegat->password:''}}" name="msegat_password" placeholder="الرقم السرى" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">اسم الراسل :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($msegat->sender_name)?$msegat->sender_name:''}}" name="msegat_sender_name" placeholder="اسم الراسل" class="form-control">
													</div>
												</div>

												<div class="text-left">
													<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
												</div>
											</form>
										</div>
									</div>
								</div>
								
								<!-- nexmosms setting -->
								<div class="col-md-6">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">Nexmo sms</h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">
											<form action="{{route('updateNexmosms')}}" method="post" class="form-horizontal">
												{{csrf_field()}}
												<div class="form-group">
													<div class="col-lg-12">
						                                <label class="checkbox" style="display:block">
						                                <input type="checkbox" name="nexmosms_active" {{($nexmosms->active == 'true')?'checked':''}}/>
						                                <i class="icon-checkbox"></i>
						                                <label style="padding-right:8px">تنشيط</label>
						                                </label> 													
													</div>
												</div>														
												<div class="form-group">
													<label class="col-lg-3 control-label">المستخدم :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($nexmosms->number)?$nexmosms->number:''}}" name="nexmosms_number" placeholder="API KEY" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الرقم السرى :</label>
													<div class="col-lg-9">
														<input type="password" value="{{isset($nexmosms->password)?$nexmosms->password:''}}" name="nexmosms_password" placeholder="API SECRET" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">اسم الراسل :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($nexmosms->sender_name)?$nexmosms->sender_name:''}}" name="nexmosms_sender_name" placeholder="اسم الراسل" class="form-control">
													</div>
												</div>

												<div class="text-left">
													<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
												</div>
											</form>
										</div>
									</div>
								</div>


								<!-- Twilio setting -->
								<div class="col-md-6">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">Twilio sms</h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">
											<form action="{{route('updateTwilio')}}" method="post" class="form-horizontal">
												{{csrf_field()}}
												<div class="form-group">
													<div class="col-lg-12">
						                                <label class="checkbox" style="display:block">
						                                <input type="checkbox" name="twilio_active" {{($twilio->active == 'true')?'checked':''}}/>
						                                <i class="icon-checkbox"></i>
						                                <label style="padding-right:8px">تنشيط</label>
						                                </label> 													
													</div>
												</div>														
												<div class="form-group">
													<label class="col-lg-3 control-label">المستخدم :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($twilio->number)?$twilio->number:''}}" name="twilio_number" placeholder="المستخدم" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الرقم السرى :</label>
													<div class="col-lg-9">
														<input type="password" value="{{isset($twilio->password)?$twilio->password:''}}" name="twilio_password" placeholder="الرقم السرى" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">اسم الراسل :</label>
													<div class="col-lg-9">
														<input type="text" value="{{isset($twilio->sender_name)?$twilio->sender_name:''}}" name="twilio_sender_name" placeholder="اسم الراسل" class="form-control">
													</div>
												</div>

												<div class="text-left">
													<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
												</div>
											</form>
										</div>
									</div>
								</div>



							</div>
						</div>

						<!-- copyright -->
						<div class="tab-pane" id="basic-tab4">
							<div class="col-md-12">
								<div class="panel panel-flat">
									<div class="panel-heading">
										<h5 class="panel-title">الشروط والأحكام </h5>
										<div class="heading-elements">
											<ul class="icons-list">
						                		<li><a data-action="collapse"></a></li>
						                		<li><a data-action="reload"></a></li>
						                	</ul>
					                	</div>
									</div>
									<div class="panel-body">
										<form action="{{route('updatesiteTermsAndPrivacy')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
											{{csrf_field()}}
													<div class="form-group">
														<label class="col-lg-3 control-label">شروط الاستخدام (بالعربية):</label>
														<div class="col-lg-6">
														<textarea name="terms_ar" class="form-control froala-editor" cols="5" rows="5" placeholder="شروط الاستخدام (بالعربية)">{{setting('terms_ar')}}</textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">شروط الاستخدام (بالانجليزية):</label>
														<div class="col-lg-6">
														<textarea name="terms_en" class="form-control froala-editor" cols="5" rows="5" placeholder="شروط الاستخدام (بالانجليزية)">{{setting('terms_en')}}</textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">سياسة الخصوصية (بالعربية):</label>
														<div class="col-lg-6">
														<textarea name="privacy_ar" class="form-control froala-editor" cols="5" rows="5" placeholder="سياسة الخصوصية (بالعربية)">{{setting('privacy_ar')}}</textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">سياسة الخصوصية (بالانجليزية):</label>
														<div class="col-lg-6">
														<textarea name="privacy_en" class="form-control froala-editor" cols="5" rows="5" placeholder="سياسة الخصوصية (بالانجليزية)">{{setting('privacy_en')}}</textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">نبذه عن التطبيق (بالعربية):</label>
														<div class="col-lg-6">
														<textarea name="about_app_ar" class="form-control froala-editor" cols="5" rows="5" placeholder="نبذه عن التطبيق (بالعربية)">{{setting('about_app_ar')}}</textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-3 control-label">نبذه عن التطبيق (بالانجليزية):</label>
														<div class="col-lg-6">
														<textarea name="about_app_en" class="form-control froala-editor" cols="5" rows="5" placeholder="نبذه عن التطبيق (بالانجليزية)">{{setting('about_app_en')}}</textarea>
														</div>
													</div>													
											<div class="text-left">
												<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- email template -->
						<div class="tab-pane" id="basic-tab5">
								<div class="col-md-12">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">قالب الايميل </h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>
										<div class="panel-body">
											<form action="{{route('updateemailtemplate')}}" method="post" class="form-horizontal">
												{{csrf_field()}}
												<div class="row">
													<div class="form-group col-sm-4" >
														<label>لون الخط</label>
														<input type='color' id="color" value="{{$Html->email_font_color}}" name="email_font_color" value="#ff0000" style="width: 90%; height: 100px; cursor: pointer; ">
													</div>

													<div class="form-group col-sm-4" >
														<label>لون الهيدر</label>
														<input type='color' id="color" value="{{$Html->email_header_color}}" name="email_header_color" value="#ff0000" style="width: 90%; height: 100px; cursor: pointer; ">
													</div>

													<div class="form-group col-sm-4" >
														<label>لون الفوتر</label>
														<input type='color' id="color" value="{{$Html->email_footer_color}}" name="email_footer_color" value="#ff0000" style="width: 90%; height: 100px; cursor: pointer; ">
													</div>

													<div class="text-left">
														<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
						</div>

						<!-- notification -->
						<div class="tab-pane" id="basic-tab6">
							<div class="row">

								<!-- one signal -->
								<div class="col-md-6">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">one signal</h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">
											<form action="{{route('updateonesignal')}}" method="post" class="form-horizontal">
												{{csrf_field()}}
												<div class="form-group">
													<div class="col-lg-12">
						                                <label class="checkbox" style="display:block">
						                                <input type="checkbox" name="onesignal_active" {{($onesignal->active == 'true')?'checked':''}}/>
						                                <i class="icon-checkbox"></i>
						                                <label style="padding-right:8px">تنشيط</label>
						                                </label> 													
													</div>
												</div>												
												<div class="form-group">
													<div class="col-lg-9">
														<input type="text" value="{{isset($onesignal->application_id)? $onesignal->application_id:''}}" name="oneSignal_application_id" placeholder="application ID" class="form-control">
													</div>
													<label class="col-lg-3 control-label">: application ID</label>
												</div>

												<div class="form-group">
													<div class="col-lg-9">
														<input type="text" value="{{isset($onesignal->authorization)?$onesignal->authorization:''}}" name="oneSignal_authorization" placeholder="authorization" class="form-control">
													</div>
													<label class="col-lg-3 control-label">: authorization</label>
												</div>

												<div class="text-left">
													<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
												</div>
											</form>
										</div>
									</div>
								</div>

								<!-- FCM -->
								<div class="col-md-6">
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">FCM</h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">
											<form action="{{route('updatefcm')}}" method="post" class="form-horizontal">
												{{csrf_field()}}
												<div class="form-group">
													<div class="col-lg-12">
						                                <label class="checkbox" style="display:block">
						                                <input type="checkbox" name="fcm_active" {{($fcm->active == 'true')?'checked':''}}/>
						                                <i class="icon-checkbox"></i>
						                                <label style="padding-right:8px">تنشيط</label>
						                                </label> 													
													</div>
												</div>												
												<div class="form-group">
													<div class="col-lg-9">
														<input type="text" value="{{isset($fcm->server_key)?$fcm->server_key:''}}" name="fcm_server_key" placeholder="server key" class="form-control">
													</div>
													<label class="col-lg-3 control-label">: server key</label>
												</div>

												<div class="form-group">
													<div class="col-lg-9">
														<input type="text" value="{{isset($fcm->sender_id)?$fcm->sender_id:''}}" name="fcm_sender_id" placeholder="sender id" class="form-control">
													</div>
													<label class="col-lg-3 control-label">: sender id</label>
												</div>

												<div class="text-left">
													<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
												</div>
											</form>
										</div>
									</div>
								</div>

							</div>
						</div>

						<!-- api -->
						<div class="tab-pane" id="basic-tab7">
							<div class="col-md-12">
								<div class="panel panel-flat">
									<div class="panel-heading">
 										<div class="heading-elements">
											<ul class="icons-list">
						                		<li><a data-action="collapse"></a></li>
						                		<li><a data-action="reload"></a></li>
						                	</ul>
					                	</div>
									</div>
									<div class="panel-body">
										<form action="{{route('updategooglePlacesKey')}}" method="post" class="form-horizontal">
											{{csrf_field()}}
											<div class="form-group text-center">
												<div class="col-lg-12">
													<p class="alert alert-primary" style="font-size: 20px; margin-bottom: 5px">Google Places Key</p>
													<textarea placeholder="Google Places key" name="google_places_key" class="form-control" rows="5">{{ setting('google_places_key')}}</textarea>
												</div>
											</div>

											<div class="text-center">
												<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
											</div>
										</form>
									</div>
									<div class="panel-body">
										<form action="{{route('updatewaslApiKey')}}" method="post" class="form-horizontal">
											{{csrf_field()}}
											<div class="form-group text-center">
												<div class="col-lg-12">
													<p class="alert alert-primary" style="font-size: 20px; margin-bottom: 5px">Wasl API Key</p>
													<textarea placeholder="Wasl API key" name="wasl_api_key" class="form-control" rows="5">{{ setting('wasl_api_key')}}</textarea>
												</div>
											</div>

											<div class="text-center">
												<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
											</div>
										</form>
									</div>
									<div class="panel-body">
										<form action="{{route('updateCurrencyConverter')}}" method="post" class="form-horizontal">
											{{csrf_field()}}
											<div class="form-group text-center">
												<div class="col-lg-12">
													<p class="alert alert-primary" style="font-size: 20px; margin-bottom: 5px">Currency Converter key</p>
													<textarea placeholder="Currency Converter key" name="currencyconverterapi" class="form-control" rows="5">{{ setting('currencyconverterapi')}}</textarea>
												</div>
											</div>

											<div class="text-center">
												<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
											</div>
										</form>
									</div>

									<div class="panel-body">
										<form action="{{route('updategoogleanalytics')}}" method="post" class="form-horizontal">
											{{csrf_field()}}
											<div class="form-group text-center">
												<div class="col-lg-12">
													<p class="alert alert-primary" style="font-size: 20px; margin-bottom: 5px">Google Analytics</p>
													<textarea placeholder="google analytics code" name="google_analytics" class="form-control" rows="10">{{$Html->google_analytics}}</textarea>
												</div>
											</div>

											<div class="text-center">
												<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
											</div>
										</form>
									</div>
									<div class="panel-body">
										<form action="{{route('updatelivechat')}}" method="post" class="form-horizontal">
											{{csrf_field()}}
											<div class="form-group text-center">
												<div class="col-lg-12">
													<p class="alert alert-primary" style="font-size: 20px; margin-bottom: 5px">Live Chat  </p>
													<textarea placeholder="live chat  code" name="live_chat" class="form-control" rows="10">{{$Html->live_chat}}</textarea>
												</div>
											</div>

											<div class="text-center">
												<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>


		<!-- Add Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">أضافة موقع تواصل جديد</h5>
		      </div>
		      <div class="modal-body">
		        <div class="row">
		        	<form action="{{route('addsocials')}}" method="POST" enctype="multipart/form-data">
		        		{{csrf_field()}}
		        		<div class="col-sm-3 text-center">
		        			<label style="margin-bottom: 0">اختيار لوجو</label>
		        			<i class="icon-camera"  onclick="changeAdd()" style="cursor: pointer;"></i>
		        			<div class="images-upload-block">
		        				<input type="file" name="add_logo" class="image-uploader" id="hidden">
		        			</div>
		        		</div>

		        		<div class="col-sm-9" style="margin-top: 35px">
		        			<input type="text" name="site_name" class="form-control" placeholder="اسم الموقع ">
		        			<input type="text" name="site_link" class="form-control" placeholder="لينك الموقع ">
		        		</div>

				        <div class="col-sm-12" style="margin-top: 10px">
					      	<button type="submit" class="btn btn-primary addCategory">اضافه</button>
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
				        </div>

		        	</form>
		        </div>
		      </div>

		    </div>
		  </div>
		</div>
		<!-- /Add Modal -->

		<!-- Edit Modal -->
		<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel"> تعديل : <span class="editingName"></span> </h5>
		      </div>
		      <div class="modal-body">
		        <div class="row">
		        	<form action="{{route('updatesocials')}}" method="post" enctype="multipart/form-data">

		        		<!-- token and born id -->
		        		{{csrf_field()}}
		        		<input type="hidden" name="id" value="">
		        		<!-- /token and born id -->

		        		<div class="col-sm-3 text-center">
		        			<label>اختيار لوجو</label>
		        			<img src="" class="replaceImage" style="width: 120px;height: 120px;cursor: pointer" onclick="changeEdit()">
		        			<input type="file" name="edit_logo" style="display: none;">
		        		</div>
		        		<div class="col-sm-9" style="margin-top: 18px">
		        			<label>اسم الموقع</label>
		        			<input type="text" name="edit_site_name" class="form-control">
		        			<label>لينك الموقع</label>
		        			<input type="text" name="edit_site_link" class="form-control">
		        		</div>

				      <div class="col-sm-12" style="margin-top: 10px">
				      	<button type="submit" class="btn btn-primary" >حفظ التعديلات</button>
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
				      </div>
		        	</form>
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
		<!-- /Edit Modal -->
		
		<!-- Add Modal -->
		<div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">أضافة اعلان جديد</h5>
		      </div>
		      <div class="modal-body">
		        <div class="row">
		        	<form action="{{route('addAd')}}" method="POST" enctype="multipart/form-data">
		        		{{csrf_field()}}
		        		<div class="col-sm-3 text-center">
		        			<label style="margin-bottom: 0">صورة الاعلان</label>
		        			<i class="icon-camera"  onclick="changeAddad()" style="cursor: pointer;"></i>
		        			<div class="images-upload-block">
		        				<input type="file" name="ad_image" class="image-uploader" id="hidden">
		        			</div>
		        		</div>
		        		<div class="col-sm-9" style="margin-top: 35px">
		        			<label>رابط الاعلان</label>
		        			<input type="url" name="ad_link" class="form-control" placeholder="رابط الاعلان ">
		        			<label>تاريخ الانتهاء</label>
		        			<input type="date" name="ad_end_at" class="form-control" placeholder="تاريخ الانتهاء ">
		        			<textarea name="ad_notes" class="form-control" placeholder="ملاحظات..."></textarea>
		        		</div>

				        <div class="col-sm-12" style="margin-top: 10px">
					      	<button type="submit" class="btn btn-primary addCategory">اضافه</button>
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
				        </div>

		        	</form>
		        </div>
		      </div>

		    </div>
		  </div>
		</div>
		<!-- /Add Modal -->

		<!-- Edit Modal -->
		<div class="modal fade" id="exampleModal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel"> تعديل الاعلان</h5>
		      </div>
		      <div class="modal-body">
		        <div class="row">
		        	<form action="{{route('updateAd')}}" method="post" enctype="multipart/form-data">
		        		<!-- token and born id -->
		        		{{csrf_field()}}
		        		<input type="hidden" name="ad_id" value="">
		        		<!-- /token and born id -->
		        		<div class="col-sm-3 text-center">
		        			<label>صورة الاعلان</label>
		        			<img src="" class="replaceAdImage" id="addimage" style="width: 120px;height: 120px;cursor: pointer" onclick="changeEditad()">
		        			<input type="file" name="edit_ad_image" style="display: none;">
		        		</div>
		        		<div class="col-sm-9" style="margin-top: 18px">
							<label>رابط الاعلان</label>
		        			<input type="url" name="edit_ad_link" class="form-control" placeholder="رابط الاعلان ">
		        			<label>تاريخ الانتهاء</label>
		        			<input type="date" name="edit_end_at" class="form-control">
		        			<textarea name="edit_notes" class="form-control" placeholder="ملاحظات..."></textarea>
		        		</div>

				      <div class="col-sm-12" style="margin-top: 10px">
				      	<button type="submit" class="btn btn-primary" >حفظ التعديلات</button>
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
				      </div>
		        	</form>
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
		<!-- /Edit Modal -->

	</div>
</div>


<!-- javascript -->
@section('script')
<script src="{{asset('dashboard/bgrins/spectrum.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@2.9.1/js/froala_editor.pkgd.min.js"></script>

<script type="text/javascript">
  $(function() {
    $('textarea.froala-editor').froalaEditor();
  });
  

$(document).on('change','#allow_debt_captain',function(){
    if(this.checked) {
	   $('#max_debt_captain').show();	
    }else{
	   $('#max_debt_captain').hide();	
    }
});

$(document).on('change','#allow_debt_client',function(){
    if(this.checked) {
	   $('#max_debt_client').show();	
    }else{
	   $('#max_debt_client').hide();	
    }
});
	//open edit modal
	$(document).on('click','.openEditmodal',function(){
		//get valus 
		var id    = $(this).data('id')
		var name  = $(this).data('name')
		var link  = $(this).data('link')
		var logo  = $(this).data('logo')

		//set values in modal inputs
		$("input[name='id']")           .val(id)
		$("input[name='edit_site_name']")    .val(name)
		$("input[name='edit_site_link']")    .val(link)
		var link = "{{asset('dashboard/uploads/socialicon/')}}" +'/'+ logo
		$(".replaceImage").attr('src',link)
		$('.editingName').text(name)
	})
	
	$(document).on('click','.openEditmodal2',function(){
		//get valus 
		var id       = $(this).data('id')
		var adendat  = $(this).data('adendat')
		var adlink  = $(this).data('adlink')
		var adnotes  = $(this).data('adnotes')
		var adimage  = $(this).data('adimage')

		//set values in modal inputs
		$("input[name='ad_id']")           .val(id)
		$("input[name='edit_ad_link']")    .val(adlink)
		$("input[name='edit_end_at']")    .val(adendat)
		$("textarea[name='edit_notes']")    .val(adnotes)
		var link = "{{asset('dashboard/uploads/ads/')}}" +'/'+ adimage
		$(".replaceAdImage").attr('src',link)
	})
	//select logo
	function changeAdd(){$("input[name='add_logo']").click()}
	function changeAddad(){$("input[name='ad_image']").click()}
	function changeSitelogo(){$("input[name='logo']").click()}
	function changePaymentPageBackground(){$("input[name='payment_page_background']").click()}
	
	// function changeSide_ad(){$("input[name='side_ad']").click()}
	// function changeBottom_ad(){$("input[name='bottom_ad']").click()}
	function changeEdit(){$("input[name='edit_logo']").click()}
	function changeEditad(){$("input[name='edit_ad_image']").click()}


	//stay in current tab after reload
	$(function() { 
	    // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
	    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	        // save the latest tab; use cookies if you like 'em better:
	        localStorage.setItem('lastTab', $(this).attr('href'));
	    });

	    // go to the latest tab, if it exists:
	    var lastTab = localStorage.getItem('lastTab');
	    if (lastTab) {
	        $('[href="' + lastTab + '"]').tab('show');
	    }
	});


$(document).on('change','#color',function(){
	console.log($(this).val());
});


	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
	
</script>

<script type="text/javascript" src="{{asset('dashboard/fileinput/js/fileinput.min.js')}}"></script>
@endsection
<!-- /javascript -->

@endsection