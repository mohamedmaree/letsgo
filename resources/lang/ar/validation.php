<?php

return [
	/*
		    |--------------------------------------------------------------------------
		    | Validation Language Lines
		    |--------------------------------------------------------------------------
		    |
		    | The following language lines contain the default error messages used by
		    | the validator class. Some of these rules have multiple versions such
		    | such as the size rules. Feel free to tweak each of these messages.
		    |
	*/

	'accepted' => 'يجب قبول :attribute',
	'active_url' => ':attribute لا يُمثّل رابطًا صحيحًا',
	'after' => 'يجب على :attribute أن يكون تاريخًا لاحقًا للتاريخ :date.',
	'after_or_equal' => ':attribute يجب أن يكون تاريخاً لاحقاً أو مطابقاً للتاريخ :date.',
	'alpha' => 'يجب أن لا يحتوي :attribute سوى على حروف',
	'alpha_dash' => 'يجب أن لا يحتوي :attribute على حروف، أرقام ومطّات.',
	'alpha_num' => 'يجب أن يحتوي :attribute على حروفٍ وأرقامٍ فقط',
	'array' => 'يجب أن يكون :attribute ًمصفوفة',
	'before' => 'يجب على :attribute أن يكون تاريخًا سابقًا للتاريخ :date.',
	'before_or_equal' => ':attribute يجب أن يكون تاريخا سابقا أو مطابقا للتاريخ :date',
	'between' => [
		'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
		'file' => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
		'string' => 'يجب أن يكون عدد حروف النّص :attribute بين :min و :max',
		'array' => 'يجب أن يحتوي :attribute على عدد من العناصر بين :min و :max',
	],
	'boolean' => 'يجب أن تكون قيمة :attribute إما true أو false ',
	'confirmed' => 'حقل التأكيد غير مُطابق للحقل :attribute',
	'date' => ':attribute ليس تاريخًا صحيحًا',
	'date_format' => 'لا يتوافق :attribute مع الشكل :format.',
	'different' => 'يجب أن يكون الحقلان :attribute و :other مُختلفان',
	'digits' => 'يجب أن يحتوي :attribute على :digits رقمًا/أرقام',
	'digits_between' => 'يجب أن يحتوي :attribute بين :min و :max رقمًا/أرقام ',
	'dimensions' => 'الـ :attribute يحتوي على أبعاد صورة غير صالحة.',
	'distinct' => 'للحقل :attribute قيمة مُكرّرة.',
	'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صحيح البُنية',
	'exists' => ':attribute لاغٍ',
	'file' => 'الـ :attribute يجب أن يكون ملفا.',
	'filled' => ':attribute إجباري',
	'image' => 'يجب أن يكون :attribute صورةً',
	'in' => ':attribute لاغٍ',
	'in_array' => ':attribute غير موجود في :other.',
	'integer' => 'يجب أن يكون :attribute عددًا صحيحًا',
	'ip' => 'يجب أن يكون :attribute عنوان IP صحيحًا',
	'ipv4' => 'يجب أن يكون :attribute عنوان IPv4 صحيحًا.',
	'ipv6' => 'يجب أن يكون :attribute عنوان IPv6 صحيحًا.',
	'json' => 'يجب أن يكون :attribute نصآ من نوع JSON.',
	'max' => [
		'numeric' => 'يجب أن تكون قيمة :attribute مساوية أو أصغر لـ :max.',
		'file' => 'يجب أن لا يتجاوز حجم الملف :attribute :max كيلوبايت',
		'string' => 'يجب أن لا يتجاوز طول النّص :attribute :max حروفٍ/حرفًا',
		'array' => 'يجب أن لا يحتوي :attribute على أكثر من :max عناصر/عنصر.',
	],
	'mimes' => 'يجب أن يكون ملفًا من نوع : :values.',
	'mimetypes' => 'يجب أن يكون ملفًا من نوع : :values.',
	'min' => [
		'numeric' => 'يجب أن تكون قيمة :attribute مساوية أو أكبر لـ :min.',
		'file' => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت',
		'string' => 'يجب أن يكون طول النص :attribute على الأقل :min حروفٍ/حرفًا',
		'array' => 'يجب أن يحتوي :attribute على الأقل على :min عُنصرًا/عناصر',
	],
	'not_in' => ':attribute لاغٍ',
	'numeric' => 'يجب على :attribute أن يكون رقمًا',
	'present' => 'يجب تقديم :attribute',
	'regex' => 'صيغة :attribute غير صحيحة',
	'required' => ':attribute مطلوب.',
	'required_if' => ':attribute مطلوب في حال ما إذا كان :other يساوي :value.',
	'required_unless' => ':attribute مطلوب في حال ما لم يكن :other يساوي :values.',
	'required_with' => ':attribute مطلوب إذا توفّر :values.',
	'required_with_all' => ':attribute مطلوب إذا توفّر :values.',
	'required_without' => ':attribute مطلوب إذا لم يتوفّر :values.',
	'required_without_all' => ':attribute مطلوب إذا لم يتوفّر :values.',
	'same' => 'يجب أن يتطابق :attribute مع :other',
	'size' => [
		'numeric' => 'يجب أن تكون قيمة :attribute مساوية لـ :size',
		'file' => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت',
		'string' => 'يجب أن يحتوي النص :attribute على :size حروفٍ/حرفًا بالظبط',
		'array' => 'يجب أن يحتوي :attribute على :size عنصرٍ/عناصر بالظبط',
	],
	'string' => 'يجب أن يكون :attribute نصآ.',
	'timezone' => 'يجب أن يكون :attribute نطاقًا زمنيًا صحيحًا',
	'unique' => 'قيمة :attribute مُستخدمة من قبل',
	'uploaded' => 'فشل في تحميل الـ :attribute',
	'url' => 'صيغة الرابط :attribute غير صحيحة',

	/*
		    |--------------------------------------------------------------------------
		    | Custom Validation Language Lines
		    |--------------------------------------------------------------------------
		    |
		    | Here you may specify custom validation messages for attributes using the
		    | convention "attribute.rule" to name the lines. This makes it quick to
		    | specify a specific custom language line for a given attribute rule.
		    |
	*/

	'custom' => [
		'attribute-name' => [
			'rule-name' => 'custom-message',
		],
	],

	/*
		    |--------------------------------------------------------------------------
		    | Custom Validation Attributes
		    |--------------------------------------------------------------------------
		    |
		    | The following language lines are used to swap attribute place-holders
		    | with something more reader friendly such as E-Mail Address instead
		    | of "email". This simply helps us make messages a little cleaner.
		    |
	*/

	'attributes' => [
		'name' => 'الاسم',
		'message' => 'محتوى الرسالة',
		'email' => 'البريد الإلكتروني',
		'first_name' => 'الاسم الاول',
		'last_name' => 'اسم العائلة',
		'password' => 'كلمة المرور',
		'current_password' => 'كلمة المرور الحالية ',
		'new_password' => 'كلمة المرور الجديدة',
		'password_confirmation' => 'تأكيد كلمة المرور',
		'city' => 'المدينة',
		'country' => 'الدولة',
		'address' => 'العنوان',
		'phone' => 'الهاتف',
		'mobile' => 'الجوال',
		'age' => 'العمر',
		'sex' => 'الجنس',
		'gender' => 'النوع',
		'day' => 'اليوم',
		'month' => 'الشهر',
		'year' => 'السنة',
		'hour' => 'ساعة',
		'minute' => 'دقيقة',
		'second' => 'ثانية',
		'title' => 'اللقب',
		'content' => 'المُحتوى',
		'description' => 'الوصف',
		'excerpt' => 'المُلخص',
		'date' => 'التاريخ',
		'time' => 'الوقت',
		'available' => 'مُتاح',
		'size' => 'الحجم',
		'payment_type' => 'طرق الدفع',
		'category_description'=>'وصف القسم',
		'category_name'=>'اسم القسم',
		'category_icon'=>'الايكونه',
		'image'=>'الصوره',

		'new_category_description'=>'وصف القسم',
		'new_category_name'=>'اسم القسم',
		'new_category_icon'=>'الايكونه',
		'image'=>'صورة ',
		'new_category_image'=>'صورة القسم',
		'about_tribe'=>'عن القبيله',
		'about_supervisor' =>'سيرته الذاتيه',
		'supervisor_achievements'=>'انجازاته',
		'aqsan_history' =>'تاريخ القبيله',
		'aqsan_pens' =>'اقلام القبيله',
		'about'=>'نبذه عن المدير ',
		'photo' =>'الصوره',
		'edit_name'=>'الاسم',
		'edit_phone'=>'الهاتف',
		'edit_about'=>'نبذه عن المدير',
		'edit_photo'=>'الصوره',

		'poem_writer'=>'اسم الشاعر',
		'poem_name' =>'اسم القصيده',
		'counter' =>'عدد الابيات',

		'edit_poem_writer'=>'اسم الشاعر',
		'edit_poem_name' =>'اسم القصيده',
		'edit_counter' =>'عدد الابيات',
		'edit_content' =>'المحتوى',

		'edit_shelat_writer'=>'اسم الشاعر',
		'edit_shelat_name' =>'اسم الشيله',

		'shelat_writer'=>'اسم الشاعر',
		'shelat_name' =>'اسم الشيله',

		'symbole_name' =>'اسم الرمز',

		'intro_image' =>'صورة المقدمه',

		'site_name'  =>'اسم الموقع',
		'site_link'  =>'لينك الموقع',	
		'edit_site_name'  =>'اسم الموقع',
		'edit_site_link'  =>'لينك الموقع',
		'add_logo' =>'لوجو الموقع',
		'edit_logo' =>'لوجو الموقع',

		'sms_message' =>'نص الرساله',
		'email_message' =>'نص الرساله',

		'avatar' =>'الصوره',
        'end_date' => 'تاريخ الانتهاء',
        'start_date' => 'تاريخ البداية',
        'project_id' => 'اسم المشروع',
        'category_id'=> 'نوع الخدمة',
        'service_id' => 'القسم الفرعي',
        'lat'        => ' الموقع علي الخريطة ',
        'long'        => ' الموقع علي الخريطة',
        'provider_name' => ' اسم المورد ',
        'product_name' => ' اسم المنتج ',
        'product_quantity' => ' الكمية ',
        'product_price' => ' المبلغ الاجمالي ',
        'paid' => ' المبلغ المدفوع ',
        'stay' => ' المبلغ المدين ',
        'add_provider_name' => ' اسم المورد ',
        'add_product_name' => ' اسم المنتج ',
        'add_product_quantity' => ' الكمية ',
        'add_product_price' => ' المبلغ الاجمالي ',
        'add_paid' => ' المبلغ المدفوع ',
        'add_stay' => ' المبلغ المدين ', 
        'subject'  => ' موضوع الرسالة ',
        'rating'   => 'التقييم',  
        'code'     => ' كود التحقق',
        'edit_code'     => ' كود التحقق',
        'role'     => 'الصلاحيه',   
        'edit_role'     => 'الصلاحيه',   
        'type'     => 'النوع',
        'edit_type'     => 'النوع',
        'end_at'   => 'تاريخ الانتهاء',
        'edit_end_at' => 'تاريخ الانتهاء',
        'today'    => 'اليوم',
        'value'    => 'القيمة',
        'edit_value'    => 'القيمة',
        'num_to_use' => 'عدد مرات الاستخدام', 
        'edit_num_to_use' => 'عدد مرات الاستخدام', 
        'receive_address'      => 'عنوان الاستلام',
        'edit_receive_address' => 'عنوان الاستلام',
        'deliver_address'      => 'عنوان التسليم',
        'edit_deliver_address' => 'عنوان التسليم',
        'description'          => 'تفاصيل الطلب',
        'edit_description'     => 'تفاصيل الطلب',
        'reason_ar'            => 'السبب بالعربية',
        'reason_en'            => 'السبب بالانجليزية',
        'edit_reason_ar'       => 'السبب بالعربية',
        'edit_reason_en'       => 'السبب بالانجليزية',  
        'deleteids'            => 'تحديد عناصر للحذف',       
        // 'device_id'            => 'device id',     
        'coupon'               => 'رقم الكوبون',   
        'transfer_amount'      => 'مبلغ التحويل', 
	    'later_order_date'        => 'تاريخ الرحلة',
	    'later_order_time'        => 'وقت الرحلة', 
	    'credit_num'              => 'رقم بطاقة الائتمان',        
	    'expire_date'             => 'تاريح الانتهاء',
	    'verification_number'     => 'رقم التحقق',
	    'username'                => 'الاسم',
	    'car_type_id'             => 'نوع السيارة'

	],   
];
