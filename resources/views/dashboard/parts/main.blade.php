@extends('dashboard.layout.master')
	@section('script')
		<script type="text/javascript" src="{{asset('dashboard/js/plugins/visualization/d3/d3.min.js')}}"></script>
		<script type="text/javascript" src="{{asset('dashboard/js/plugins/visualization/d3/d3_tooltip.js')}}"></script>
		<script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/styling/switchery.min.js')}}"></script>
		<script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/styling/uniform.min.js')}}"></script>
		<script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
		<script type="text/javascript" src="{{asset('dashboard/js/plugins/ui/moment/moment.min.js')}}"></script>
		<script type="text/javascript" src="{{asset('dashboard/js/plugins/pickers/daterangepicker.js')}}"></script>
		<script type="text/javascript" src="{{asset('dashboard/js/pages/dashboard.js')}}"></script>

	@endsection
@section('content')
	@include('dashboard.parts.boxes')
@endsection