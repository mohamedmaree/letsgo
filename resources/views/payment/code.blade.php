@extends('layouts.paymentLayout')
@section('content')

                <form class="form" action="{{route('paymentCodeVerfication')}}" method="GET">
                {{csrf_field()}}
                <input name="phone" type="hidden" value="{{session('phone')}}" />
                    <div class="entire-data">
                        <p>رقم الهاتف</p>
                        <span>0{{session('phone')}}</span>
                        <a href="{{route('payment')}}" title="تغيير رقم الجوال">
                            <i class="fa fa-edit"></i>
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <!-- <label class="label-control">كود التحقق</label> -->
                                <input name="code" type="number" class="form-control" placeholder="كود التحقق" />
                                @if(session('errormsg'))
                                    <div class="alert alert-danger" role="alert">
                                    {{ session('errormsg') }}
                                    </div>            
                                @endif                             
                            </div>
                        </div>

                        <button type="submit" class="btn btn-send">تحقق</button>
                    </div>
                </form>

@endsection