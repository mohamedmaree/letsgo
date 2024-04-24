@extends('dashboard.layout.master')
@section('title')
    اعدادات الصفحه التعرفيه / اراء العملاء
@endsection
@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">اعدادات الصفحه التعرفيه / اراء العملاء </h5>
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
                    <button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة رأى</span></button>
                </div>
                <div class="col-xs-3">
                    <button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الاراء : {{count($customerReviews)}} </span> </button>
                </div>
            </div>
        </div>
        <!-- /buttons -->

        <table class="table datatable-basic">
            <thead>
            <tr>
                <th>#</th>
                <th>الصوره</th>
                <th>الاسم  </th>
                <th>التعليق </th>
                <th>التقيم </th>
                <th>انشاء منذ</th>
                <th>التحكم</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1 ?>
            @foreach($customerReviews as $customerReview)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>
                        <img src="{{ $customerReview->imagePath }}" alt="" width="50" height="50">
                    </td>
                    <td>{{ $customerReview->name }}</td>
                    <td>{{ $customerReview->comment }}</td>
                    <td>{{ $customerReview->rate }}</td>
                    <td>{{ $customerReview->created_at->diffForHumans() }}</td>
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
                                            data-model="{{ $customerReview }}"
                                        >
                                            <i class="icon-pencil7"></i>تعديل
                                        </a>
                                    </li>
                                    <!-- delete button -->
                                    <form action="{{route('customerReview.destroy', $customerReview['id'])}}" method="POST">
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
                        <h5 class="modal-title" id="exampleModalLabel">أضافة رأى جديد</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form actionaction="{{route('customerReview.store')}}" id="addplan" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>الاسم : </label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                    </div>

                                    <div class="col-sm-12">
                                        <label>التعليق : </label>
                                        <textarea class="form-control" name="comment">{{ old('comment') }}</textarea>
                                    </div>

                                    <div class="col-sm-12">
                                        <label>التقييم : </label>
                                        <select name="rate" class="form-control" id="">
                                            <option value="0" {{ old('rate') == '0' ? 'selected' : '' }}>0</option>
                                            <option value="1" {{ old('rate') == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('rate') == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ old('rate') == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ old('rate') == '4' ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ old('rate') == '5' ? 'selected' : '' }}>5</option>
                                        </select>
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
                        <h5 class="modal-title" id="exampleModalLabel"> تعديل الرأى</h5>
                    </div>
                    <div class="modal-body">
                        <form actionaction="{{route('customerReview.update')}}" id="addplan" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            @method('PUT')
                            <input type="hidden" name="customerReview_id" class="id">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>الاسم : </label>
                                    <input type="text" class="form-control name" name="name" value="{{ old('name') }}">
                                </div>

                                <div class="col-sm-12">
                                    <label>التعليق : </label>
                                    <textarea class="form-control comment" name="comment">{{ old('comment') }}</textarea>
                                </div>

                                <div class="col-sm-12">
                                    <label>التقييم : </label>
                                    <select name="rate" class="form-control rate" id="">
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>

                                <div class="col-sm-12">
                                    <label>الصوره : </label>
                                    <br>
                                        <img src="" class="image" width="100" height="100" alt="">
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
        $('.name').val(model.name);
        $('.comment').val(model.comment);
        $('.rate').val(model.rate);
        $('.image').attr('src','{{ asset('assets/uploads/customer_reviews') }}' + '/' + model.image);


    })

    $('.generalDelete').on('click',function(e){
        var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
        if(result == false){
            e.preventDefault();
        }
    });

</script>
@endsection