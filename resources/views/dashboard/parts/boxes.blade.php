<style type="text/css">
    .myelements i
    {
        font-size: 46px;
        margin-top: 20%;
    }
</style>

<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="row">
            <!-- Members online -->
         @foreach(Home() as $h)
            <div class="col-lg-4">
            <div class="panel" style="background: {{$h['color']}}">
            <div class="panel-body" style="height: 110px">
            <div class="heading-elements myelements">
            {!! $h['icon'] !!}
            </div>
      @endforeach      <!-- /members online -->
        </div>
    </div>
</div>