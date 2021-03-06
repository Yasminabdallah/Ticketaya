@extends('layouts.app')
@section('content')
<section>
<div class="container">
 <div class="row mt-3 mb-3">
                <div class="col-md-10 col-xs-12">
                   <div class="search-content  d-flex">
                       <form method="get" action="/events/search" enctype="multipart/form-data" class="text-right">
                              {{ csrf_field() }}
                        <input class="search pgs-search" type="search" placeholder="Search for upcomming events or more..." aria-label="Search" name="search">
                        <button class="btn btn btn-secondary search-btn pgs-search-btn" type="submit">Search</button>
                       </form>
                       <a href={{ URL::to('events/create' )}} ><input type="button" class="btn btn-primary ml-5" value='Add New Event'/></a>
                   </div>
                </div>
        </div>
    <div class="row category-tabs events-tabs">
        <div class="col-md-2 col-sm-4 col-4 mb-2">
        <a href="{{URL::route('allevents')}}">
               <div class="catg-tab align-items-center d-flex" style="background-image: url({{ asset('storage/images/categories/events.jpg')}});">
                    <div class="overlay"></div>
                    <h3 class="m-auto">ALL EVENTS</h3>
               </div>
            </a>
        </div>
        @foreach($categories as $category)
        <div class="col-md-2 col-sm-4 col-4 mb-2">
        <a href="/events/filter/{{$category->id}}">
               <div class=" catg-tab align-items-center d-flex"  style="background-image: url({{ asset('storage/images/categories/'.$category->photo) }});">
                    <div class="overlay"></div>
                    <h3 class="m-auto">{{$category->name}}</h3>
               </div>
            </a>
        </div>
        @endforeach
    </div>
    <div class="row  mt-5 mb-5">
@if($events !== null)
@foreach($events as $event)

        <div class="col-md-4 col-12 mb-4"><!--event card starts here-->
           <a href="{{ URL::to('events/' . $event->id ) }}" class="event-card-click">
                <div class="event-card">
                    <div href="{{ URL::to('events/' . $event->id ) }}" class="event-img" style="background-image: url({{ asset('storage/images/events/'. $event->photo) }});">
                    </div>
                    <div class="event-content">
                        <h3>{{ucwords($event->name)}}</h3>
                        <p>{{substr($event->description,0,150)}}.</p>
                    </div>
                    <div class="follow text-center">
                        @if(Auth::user() && Auth::user()->id == $event->user_id)
                        <a class="btn btn-primary" href="{{ URL::to('events/' . $event->id ) }}">View</a>
                        <a type="submit"  event-id="{{$event->id}}" class="btn ctrl-btn  deletebtn"><i class="far fa-trash-alt"></i></a>
                        <a href="{{ URL::to('events/edit/' . $event->id ) }}" class="btn ctrl-btn edit-btn"><i class="far fa-edit"></i></a>


                        @endif
                    </div>
                </div>
           </a>
        </div><!--event card starts here-->
@endforeach
</div>
<div class="text-center">
{{ $events->links() }}
</div>

@else
     <h2> There are Not Events For This Category Yet ! </h2>
@endif
</div>
<script>
     $('.deletebtn').on('click',function(){
            console.log('iam here');
            var event_id = $(this).attr('event-id');
            var resp = confirm("Do you really want to delete this event?");
            if (resp == true) {
                $.ajax({
                    type: 'POST',
                    url: '/events/delete/'+event_id ,
                    data:{
                    '_token':'{{csrf_token()}}',
                    '_method':'DELETE',
                    },
                    success: function (response) {
                        if(response.response=='success'){
                           $('#'+event_id).remove();

                        }
                    }
                });

            }
        });
</script>
</section>
@endsection





