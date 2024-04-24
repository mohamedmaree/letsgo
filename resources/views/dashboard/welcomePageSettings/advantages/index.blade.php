@extends('dashboard.layout.master')
@section('title')
    اعدادات الصفحه التعرفيه / مميزاتنا
@endsection
@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">اعدادات الصفحه التعرفيه / مميزاتنا </h5>
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
            <div class="row">
                <div class="col-xs-3">
                    <button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة ميزه</span></button>
                </div>
                <div class="col-xs-3">
                    <button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد المميزات : {{count($advantages)}} </span> </button>
                </div>
            </div>
        </div>
        <!-- /buttons -->

        <table class="table datatable-basic">
            <thead>
            <tr>
                <th>#</th>
                <th>الصوره</th>
                <th>العنوان  </th>
                <th>المحتوى </th>
                <th>انشاء منذ</th>
                <th>التحكم</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1;?>
            @foreach($advantages as $advantage)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>
                        <img src="{{ $advantage->imagePath }}" alt="" width="50" height="50">
                    </td>
                    <td>{{ $advantage->title }}</td>
                    <td>{{ $advantage->content }}</td>
                    <td>{{ $advantage->created_at->diffForHumans() }}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <!-- edit button -->
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#exampleModal2" class="openEditmodal"
                                            data-model="{{ $advantage }}"
                                        >
                                            <i class="icon-pencil7"></i>تعديل
                                        </a>
                                    </li>
                                    <!-- delete button -->
                                    <form action="{{route('advantage.destroy', $advantage['id'])}}" method="POST">
                                        {{csrf_field()}}
                                        @method('DELETE')
                                        <li><button type="submit" class="generalDelete reset"><i class="icon-trash"></i>حذف</button></li>
                                    </form>
                                </ul>
                            </li>
                        </ul>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Add workstage Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">أضافة مكافأة جديدة</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form action="{{route('advantage.store')}}" id="addplan" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>العنوان : </label>
                                        <textarea class="form-control" name="title">{{ old('title') }}</textarea>
                                    </div>

                                    <div class="col-sm-12">
                                        <label>المحتوى : </label>
                                        <textarea class="form-control" name="content">{{ old('content') }}</textarea>
                                    </div>

                                    <div class="col-sm-12">
                                        <label>الصوره : </label>
                                        <input type="file" class="form-control" name="image">
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-12" style="margin-top: 10px">
                                        <button type="submit" class="btn btn-primary" > اضافة</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /Add workstage Modal -->

        <!-- Edit workstage Modal -->
        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> تعديل الميزه</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('advantage.update')}}" id="addplan" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            @method('PUT')
                            <input type="hidden" class="id" name="advantage_id">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>العنوان : </label>
                                    <textarea class="form-control title" name="title">{{ old('title') }}</textarea>
                                </div>

                                <div class="col-sm-12">
                                    <label>المحتوى : </label>
                                    <textarea class="form-control content" name="content">{{ old('content') }}</textarea>
                                </div>

                                <div class="col-sm-12">
                                    <label>الصوره : </label>
                                    <br>
                                    <img src="" alt="" class="image" width="100" height="100">
                                    <br>
                                    <input type="file" class="form-control" name="image">
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-12" style="margin-top: 10px">
                                    <button type="submit" class="btn btn-primary" > تعديل</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit user Modal -->

    </div>

    <!-- javascript -->
@section('script')
    <script type="text/javascript" src="{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <!-- <script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script> -->
    <script type="text/javascript" src="{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>
@endsection



<script type="text/javascript">
    $('.openEditmodal').on('click',function(){
        var model = $(this).data('model');

        $('.id').val(model.id);
        $('.title').val(model.title);
        $('.content').val(model.content);
        $('.image').attr('src','{{ asset('assets/uploads/advantages') }}' + '/' + model.image);


    })

    $('.generalDelete').on('click',function(e){
        var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
        if(result == false){
            e.preventDefault();
        }
    });

</script>
@endsection