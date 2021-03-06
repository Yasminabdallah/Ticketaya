<nav id="logged-navbar" class="navbar navbar-expand-lg navbar-light bg-dark">
  <a class="navbar-brand" href="#">LOGOHERE</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/tickets">TICKETS</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">EVENTS</a>
      </li>
      <li class="nav-item">
        <a class="nav-link " href="#">BLOG</a>
      </li>
      <li class="nav-item">
        <a class="nav-link " href="#">LOG IN </a>
      </li>
      <li class="nav-item">
        <a class="nav-link " href="#">REGISTER</a>
      </li>
    </ul>
  </div>
  @if (Auth::check())
  <input id="user_id" type="hidden" value="{{Auth::user()->id}}">
  <div class="collapse navbar-collapse">
    <ul class="nav navbar-nav">
      <li class="dropdown dropdown-notifications">
        <a href="#notifications-panel" class="dropdown-toggle" data-toggle="dropdown">
          <i data-count="0" class="glyphicon glyphicon-bell notification-icon"></i>
        </a>

        <div class="dropdown-container">
          <div class="dropdown-toolbar">
            <div class="dropdown-toolbar-actions">
              <a id="readall" href="#">Mark all as read</a>
            </div>

          </div>
          <ul class="dropdown-menu">
          </ul>
          <div class="dropdown-footer text-center">
            <a href="/notifications">View All</a>
          </div>
        </div>
      </li>
    </ul>
  </div>
             {{-- end Notification section UI --}}

  <script type="text/javascript">
    $(function () {
      var oldNotifications = {!! json_encode(Auth::user()->notifications->toArray()) !!};
      var CountoldNotifications = {!! json_encode(Auth::user()->notifications->where('is_seen','=',0)->count()) !!};
      var notificationsWrapper   = $('.dropdown-notifications');
      var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
      var notificationsCountElem = notificationsToggle.find('i[data-count]');
      //var notificationsCount     = parseInt(notificationsCountElem.data('count'));
      var notificationsCount=CountoldNotifications;
      var notifications          = notificationsWrapper.find('ul.dropdown-menu');
      Pusher.logToConsole = true;
      var pusher = new Pusher('6042cdb1e9ffa998e5be', {
        encrypted: true,
        cluster:"mt1"
      });


      function updateNotificationCount(){
        notificationsCountElem.attr('data-count', notificationsCount);
        notificationsWrapper.find('.notif-count').text(notificationsCount);
        notificationsWrapper.show();
      }
  function notificationsHtml(data,realtime){
              var existingNotifications = notifications.html();
              var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
              if(realtime){
                {{$nextId = DB::table('notifications')->max('id') + 1}};
                    notificationsCount += 1;
                    data.created_at=new Date(Date.now());
                    data.is_seen=0;
                    data.id={{$nextId}};
                }
               var res = data.message.substring(0,30);
              //var date= data.created_at === undefined ? new Date(Date.now())  : data.created_at ;
              var newNotificationHtml = `
              <li class="notification active">
                 <div class="media">
                    <div class="media-left">
                        <div class="media-object">
                            <img src="https://api.adorable.io/avatars/71/`+avatar+`.png" class="img-circle" alt="50x50" style="width: 50px; height: 50px;">
                            </div>
                            </div>
                            <div class="media-body">
                                <a notif-no="`+data.id+`" href="/tickets/requests" class="notify-seen"><strong style="color:black;" class="notification-title">`+res+`</strong></a>
                                <p class="notification-desc">`+data.message+`</p>
                                <div class="notification-meta">
                                    <small class="timestamp">`+data.created_at+`</small>
                                    </div>
                                    </div>
                                    </div>
                                    </li>`;
                notifications.html(newNotificationHtml + existingNotifications);
                updateNotificationCount();

      }
      function bindChannel(channel,event) {
          channel.bind(event , function(notify){
            notificationsHtml(notify,1);
            });
}
      $.each(oldNotifications, function( i, val) {
        notificationsHtml(val,0);
    });

     // make all is read
    $(document).on('click','#readall',function(event){
        event.preventDefault();
                $.ajax({
                    type: 'get',
                    url: '/notifications/allread',
                    data:{
                    '_token':'{{csrf_token()}}',
                    },
                    success: function (response) {
                        if(response.res=='success'){
                            notificationsCount=0;
                            updateNotificationCount();
                        }
                    }
                });
           });

    // change notifications status to unseen
    $(document).on('click','.notify-seen',function(event){
        event.preventDefault();
        notif_id=$(this).attr('notif-no');
                $.ajax({
                    type: 'get',
                    url: '/notifications/'+notif_id+'/edit',
                    data:{
                    '_token':'{{csrf_token()}}',
                    },
                    success: function (response) {
                        if(response.res=='unseen'){
                            notificationsCount-=1;
                            updateNotificationCount();
                        }
                    }
                });
           });
    var ticketRequestChannel = pusher.subscribe('ticket-requested_{{ Auth::user()->id }}');
    bindChannel(ticketRequestChannel,'App\\Events\\TicketRequested');
    var ticketReceivedChannel= pusher.subscribe('ticket-received_{{ Auth::user()->id }}');
    bindChannel(ticketReceivedChannel,'App\\Events\\TicketReceived');
    var statusTicketrequested=pusher.subscribe('status-tickedrequest_{{ Auth::user()->id }}')
    bindChannel(statusTicketrequested,'App\\Events\\StatusTicketRequested');
});

    </script>

@endif

</div>
</nav>

