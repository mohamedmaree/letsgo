<div class="navbar navbar-inverse">
	<div class="navbar-header">
		<a class="navbar-brand" href="https://letsgo-app.net">let'sgo</a>

		<ul class="nav navbar-nav visible-xs-block">
			<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
			<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
		</ul>
	</div>

	<div class="navbar-collapse collapse" id="navbar-mobile">
		<ul class="nav navbar-nav">
			<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>

		</ul>

		<p class="navbar-text"><span class="label bg-success-400">Online</span></p>

		<ul class="nav navbar-nav navbar-right">

			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="icon-bubbles4"></i>
					<span class="visible-xs-inline-block position-right">Messages</span>
					<span class="badge bg-warning-400">{{count(Notification()) + count(newUserMetas()) }}</span>
				</a>
				
				<div class="dropdown-menu dropdown-content width-350" style="width: 250px">

					<ul class="media-list dropdown-content-body">
						@foreach(Notification() as $n)
							<li class="media">
								<div class="media-left">

								<div class="media-body">
									<a href="{{route('showmessage',$n->id)}}" class="media-heading">
										<span class="text-semibold">{{$n->name}}</span>
										<span class="media-annotation pull-right">{{$n->created_at->diffForHumans()}}</span>
									</a>

									<span class="text-muted">{{str_limit($n->message,30)}}</span>
								</div>
							</li>
							<hr>
						@endforeach	
						@foreach(newUserMetas() as $n)
							<li class="media">
								<div class="media-left">

								<div class="media-body">
									<a href="{{route('userMeta',$n->id)}}" class="media-heading">
										<span class="text-semibold">{{$n->name}}</span>
										<span class="media-annotation pull-right">{{$n->created_at->diffForHumans()}}</span>
									</a>
									<span class="text-muted">طلب جديد للعمل كقائد </span>
								</div>
							</li>
							<hr>
						@endforeach				
					</ul>

					<div class="dropdown-content-footer">
						<a href="{{route('inbox')}}" data-popup="tooltip" title="All messages"><i class="icon-menu display-block"></i>مشاهدة جميع الرسائل</a>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>
