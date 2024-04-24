@extends('dashboard.layout.master')
@section('title')
    اعدادات الصفحه التعرفيه / صور التطبيق
@endsection
@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">اعدادات الصفحه التعرفيه / صور التطبيق </h5>
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
                    <button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة صوره</span></button>
                </div>
                <div class="col-xs-3">
                    <button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الصور : {{count($ImageApps)}} </span> </button>
                </div>
            </div>
        </div>
        <!-- /buttons -->

        <table class="table datatable-basic">
            <thead>
            <tr>
                <th>#</th>
                <th>الصوره</th>
               <th>انشاء منذ</th>
                <th>التحكم</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1 ?>
            @foreach($ImageApps as $ImageApp)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>
                        <img src="{{ $ImageApp->imagePath }}" alt="" width="50" height="50">
                    </td>
                    <td>{{ $ImageApp->created_at->diffForHumans() }}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <!-- edit button -->

                                    <!-- delete button -->
                                    <form action="{{route('imageApp.destroy', $ImageApp['id'])}}" method="POST">
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
                        <h5 class="modal-title" id="exampleModalLabel">أضافة صوره جديدة</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form action="{{route('imageApp.store')}}" id="addplan" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="row">

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
    </div>
    <!-- /Edit user Modal -->

    </div>

    <!-- javascript -->
@section('script')
    <script type="text/javascript" src="{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <!-- <script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script> -->
    <script type="text/javascript" src="{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>
@endsection



@endsection