@extends('layouts.paymentLayout')
@section('content')

                <form class="form" action="{{route('sendPaymentCode')}}" method="post">
                {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="label-control">رقم الهاتف (أدخل الرقم مباشرة بدون مفتاح الدولة) </label>
                                <input name="phone" type="number" class="form-control" placeholder="رقم الجوال بدون كود الدولة" />
                                @if(session('errormsg'))
                                    <div class="alert alert-danger" role="alert">
                                    {{ session('errormsg') }}
                                    </div>            
                                @endif  
                            </div>
                        </div>

                        <button type="submit" class="btn btn-send">أرسل كود التحقق</button>
                    </div>
                </form>

@endsection