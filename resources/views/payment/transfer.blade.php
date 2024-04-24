@extends('layouts.paymentLayout')
@section('content')

                <form class="form" action="{{route('transferBalance')}}" method="POST" >
                   {{csrf_field()}}
                   <p class="up-label">
                       <span class="tit">اسم المستخدم :</span>
                       <span class="val">{{$user->name}}</span>
                   </p>
                   <p class="up-label">
                       <span class="tit">رصيد حساب :</span>   
                       <span class="val">{{round($user->balance,2)}} {{setting('site_currency_ar')}}</span>
                   </p>
                    <div class="form-group">
                        <label class="label-control">مبلغ التحويل</label>
                        <input name="amount" type="number" value="{{($amount)??''}}" class="form-control" placeholder="مبلغ التحويل" {{($amount != "")?"readonly='readonly' " : ""}} required/>
                            @if($errors->has('amount'))
                                <div class="alert alert-danger" role="alert">
                                {{$errors->first('amount')}}
                                </div>
                            @endif                           
                    </div>
                   <button type="submit" class="btn btn-send">ارسال الطلب</button>
                </form>

@endsection