


<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
            <form action="upload" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="file" accept="video/*" name="video" class="form-controll">
                <input type="submit" class="btn btn-primary">
            </form>
            </div>
        </div>
    </div>
</div>

