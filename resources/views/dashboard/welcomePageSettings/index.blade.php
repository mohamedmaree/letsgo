@extends('dashboard.layout.master')
@section('title')
    اعدادات الصفحه التعرفيه / تعديل الاعدادات
@endsection
<!-- style -->
@section('style')

<!-- Include Editor style. -->
<link href="https://cdn.jsdelivr.net/npm/froala-editor@2.9.1/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/froala-editor@2.9.1/css/froala_style.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/froala_style.min.css" rel="stylesheet" type="text/css" />
 
@endsection
@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">اعدادات الصفحه التعرفيه / تعديل الاعدادات </h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <!-- <li><a data-action="close"></a></li> -->
                </ul>
            </div>
        </div>

        <!-- buttons -->
        <div class="panel-body">
            <div>
                <form method="post" action="{{ route('welcomePage.update') }}" enctype="multipart/form-data" id="settingForm">
                    @csrf

                        <div>

                            <div class="form-group">
                                <h3>الرسالة الترحبيه</h3>
                                <input type="text" name="settings[welcome_msg]" value="{{ $welcomePageSettings['welcome_msg'] ?? '' }}" class="form-control">

                            </div>


                            <div class="form-group">
                                <h3>'صورة الرسالة الترحبيه </h3>
                                <a data-fancybox="gallery" id="link1"
                                   href="{{ isset($welcomePageSettings['img_welcome_msg']) ? asset('assets/uploads/welcomePageSettings/' . $welcomePageSettings['img_welcome_msg']) : asset('img/defaults/camera.png') }}"><img
                                            id="output-imgInp1"
                                            src="{{ isset($welcomePageSettings['img_welcome_msg']) ? asset('assets/uploads/welcomePageSettings/' . $welcomePageSettings['img_welcome_msg']) : asset('img/defaults/camera.png') }}"
                                            width="100px" height="100px"></a>
                                <input type="file" name="settings[img_welcome_msg]" accept="image/*" class="form-control input-img"
                                       id="imgInp1" onchange="loadFile(event)">
                          
                            </div>

                            <div class="form-group">
                                <h3>صورة حوال التطبيق</h3>
                                <a data-fancybox="gallery" id="link2"
                                   href="{{ isset($welcomePageSettings['img_about_msg']) ? asset('assets/uploads/welcomePageSettings/' . $welcomePageSettings['img_about_msg']) : asset('img/defaults/camera.png') }}"><img
                                            id="output-imgInp2"
                                            src="{{ isset($welcomePageSettings['img_about_msg']) ? asset('assets/uploads/welcomePageSettings/' . $welcomePageSettings['img_about_msg']) : asset('img/defaults/camera.png') }}"
                                            width="100px" height="100px"></a>
                                <input type="file" name="settings[img_about_msg]" accept="image/*" class="form-control"
                                       onchange="loadFile(event)"
                                       id="imgInp2">
                          
                            </div>

                            <div class="form-group">
                                <h3>لينك العميل GooglePlay : </h3>
                                <input type="url" name="settings[google_play]" value="{{ $welcomePageSettings['google_play']??'' }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <h3>لينك القائد GooglePlay : </h3>
                                <input type="url" name="settings[google_play2]" value="{{ $welcomePageSettings['google_play2']??'' }}" class="form-control">
                            </div>

                            <div class="form-group">
                                <h3>لينك العميل AppleStore : </h3>
                                <input type="url" name="settings[apple_store]" value="{{ $welcomePageSettings['apple_store']??'' }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <h3>لينك القائد AppleStore : </h3>
                                <input type="url" name="settings[apple_store2]" value="{{ $welcomePageSettings['apple_store2']??'' }}" class="form-control">
                            </div>


                            <div class="form-group">
                                <h3>لون الهيدر : </h3>
                                <input type="color" name="settings[color_header]" value="{{ $welcomePageSettings['color_header']??'' }}"
                                       class="form-control">
                          
                            </div>

                            <div class="form-group">
                                <h3>لون الناف : </h3>
                                <input type="color" name="settings[color_navbar]" value="{{ $welcomePageSettings['color_navbar']??'' }}"
                                       class="form-control">
                          
                            </div>

                            <div class="form-group">
                                <h3>لون الفوتر : </h3>
                                <input type="color" name="settings[color_footer]" value="{{ $welcomePageSettings['color_footer']??'' }}"
                                       class="form-control">
                          
                            </div>

                            <div class="form-group">
                                <h3>لون نهاية الفوتر : </h3>
                                <input type="color" name="settings[color_footer_end]" value="{{ $welcomePageSettings['color_footer_end']??'' }}"
                                       class="form-control">
                          
                            </div>

                            <div class="form-group">
                                <h3>لون حول التطبيق : </h3>
                                <input type="color" name="settings[color_about]" value="{{ $welcomePageSettings['color_about']??'' }}"
                                       class="form-control">
                          
                            </div>
                            <div class="form-group">
                                <h3>العنوان : </h3>
                                <input type="text" name="settings[address]" value="{{ $welcomePageSettings['address']??'' }}" class="form-control">
                            </div>

                            <div class="form-group">
                                <h3>الهاتف : </h3>
                                <input type="text" name="settings[phone]" value="{{ $welcomePageSettings['phone']??'' }}" class="form-control">
                            </div>

                            <div class="form-group">
                                <h3>البريد الالكتروني : </h3>
                                <input type="text" name="settings[email]" value="{{ $welcomePageSettings['email']??'' }}" class="form-control">
                            </div>

                            <div class="form-group">
                                <h3>لون مميزاتنا </h3>
                                <input type="color" name="settings[color_advantage]" value="{{ $welcomePageSettings['color_advantage'] ??''}}"
                                       class="form-control">
                          
                            </div>

                            <div class="form-group">
                                <h3>لون الخلفية</h3>
                                <input type="color" name="settings[color_background]" value="{{ $welcomePageSettings['color_background']??'' }}"
                                       class="form-control">
                          
                            </div>

                        </div>

                        <div class="form-group">
                            <h3>
                               حول التطبيق
                            </h3>
                            <textarea class="form-control froala-editor" name="settings[about]"
                                      rows="3">{!!  $welcomePageSettings['about']??'' !!}</textarea>

                        </div>

                        <div class="col-sm-12 d-flex flex-sm-row flex-column justify-content-end">
                            <button type="submit" style="width: 100%" class="btn btn-success w-100 mr-sm-1 mb-1 mb-sm-0">
                                حفظ التغييرات
                            </button>
                        </div>
                </form>

            </div>
        </div>
    </div>

    <!-- javascript -->

@section('script')

<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css"/>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@2.9.1/js/froala_editor.pkgd.min.js"></script>
<script type="text/javascript">
  $(function() {
    $('textarea.froala-editor').froalaEditor();
  });
</script>
@endsection
@endsection
