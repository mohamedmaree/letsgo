<div class="sidebar sidebar-main">
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="category-content">
                @if(Auth::guard('externalAppTokens')->check())
                <div class="media">
                    <a href="#" class="media-left"><img src="{{asset('img/user/default.png')}}" class="img-circle img-sm" alt=""></a>
                    <div class="media-body">
                        <span class="media-heading text-semibold">{{(Auth::guard('externalAppTokens')->user()->client_name)??''}} </span>
                        <div class="text-size-mini text-muted">
                            <i class="icon-pin text-size-small"></i> {{(Auth::guard('externalAppTokens')->user()->app_name)??''}}
                        </div>
                    </div>

                    <div class="media-right media-middle">
                        <ul class="icons-list">
                            <li>
                                <a href="{{route('apisLogout')}}"  onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class=" icon-switch"></i></a>
                                    <form id="logout-form" action="{{ route('apisLogout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">
                    <li><a href="{{route('apisIndex')}}"> الرئيسية <i class="glyphicon glyphicon-home"></i> </a></li>
                    <li><a href="{{route('ApiOpenOrders')}}"> الطلبات المفتوحة <i class="glyphicon glyphicon-bullhorn"></i> </a></li>
                    <li><a href="{{route('ApiInprogressOrders')}}"> الطلبات قيد التنفيذ <i class="glyphicon glyphicon-hourglass"></i> </a></li>
                    <li><a href="{{route('ApiFinishedOrders')}}"> الطلبات المكتملة <i class="glyphicon glyphicon-saved"></i> </a></li>
                    <li><a href="{{route('ApiClosedOrders')}}"> الطلبات المغلقة <i class="glyphicon glyphicon-minus-sign"></i> </a></li>
                    <li><a href="{{route('ApiSetting')}}"> الاعدادات <i class="glyphicon glyphicon-cog"></i> </a></li>

                </ul>
            </div>
        </div>
        <!-- /main navigation -->

    </div>
</div>