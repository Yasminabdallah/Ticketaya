@extends('layouts.app')
@section('content')
<div class="container">
    <section id="search-page">
        <div class="row">
                <div class="col-md-12 col-xs-12">
                   <div class="search-content">
                       <form method="get" action="/tickets/search" enctype="multipart/form-data" class="text-center">
                              {{ csrf_field() }}
                        <input class="search pgs-search" type="search" placeholder="Search Tickets, events or more..." aria-label="Search" name="search">
                        <button class="btn btn btn-secondary search-btn pgs-search-btn" type="submit">Search</button>
                       </form>
                   </div>
                </div>
        </div>
    </section>
    <section>
        <div class="row">
            <div class="col-md-3">
              <form method="GET" action="/tickets/filter">
                  <div class="list-group panel">
                    <a class="list-group-item list-group-item strong text-center" style="background: #009ce0; color: white;" data-toggle="collapse"> Personalize Your Search</a>
                    <a href="#demo1" class="list-group-item list-group-item-success strong" style="background: #f7f7f7;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-photo"></i> Price <i class="fa fa-caret-down"></i></a>
                    <div class="collapse list-group-submenu" id="demo1">
                     <ul class="p-0 mb-0">
                         <li class="list-group-item"><input type="checkbox" name="price" value="50" > 50-100</li>
                         <li class="list-group-item"><input type="checkbox" name="price" value="150" > 150-200</li>
                         <li class="list-group-item"><input type="checkbox" name="price" value="250"> 250-300</li>
                         <li class="list-group-item"><input type="checkbox" name="highprice" value="300"> 300 or more</li>

                     </ul>
                    </div>
                    <a href="#demo2" class="list-group-item list-group-item strong" style="background: #f7f7f7;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-book"></i> Category <i class="fa fa-caret-down"></i></a>
                    <div class="collapse list-group-submenu" id="demo2">
                      <ul class="p-0 mb-0">
                             <li class="list-group-item"><input type="checkbox" name="category[]" value="sport"> Sport</li>
                             <li class="list-group-item"><input type="checkbox" name="category[]" value="train"> Train</li>
                             <li class="list-group-item"><input type="checkbox" name="category[]" value="concert"> Concert</li>
                             <li class="list-group-item"><input type="checkbox" name="category[]" value="Travel"> Travel</li>

                         </ul>
                    </div>
                    <a href="#demo5" class="list-group-item list-group-item strong" style="background: #f7f7f7;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-cubes"></i>  Location <i class="fa fa-caret-down"></i></a>
                    <div class="collapse list-group-submenu" id="demo5">
                      <ul class="p-0 mb-0">
                             <li class="list-group-item"><input type="checkbox" name="city" value="cairo"> Cairo</li>
                             <li class="list-group-item"><input type="checkbox" name="city" value="alexandria"> Alexandria</li>
                         </ul>
                    </div>

                      <input type="submit" class="list-group-item list-group-item strong text-center" value="Apply">
                  </div>
              </form>
            </div> <!-- filter  -->
            <div class="col-md-9">
                <div class="row">
                        @foreach($tickets as $ticket)

                        <div class="col-md-4 col-xs-12 tick-search ticket-card-parent">
                            <div class="card ticket-card">
                                <div class="card-img"  style=" background-image: url({{ asset('storage/images/tickets/'. $ticket->photo) }});">

                                </div>

                                <div class="card-body">
                                    <h3 class="card-title">{{ucwords($ticket->name)}} <span class="ticket-price">{{$ticket->price}} L.E</span></h3>
                                    <p class="ticket-des">{{substr($ticket->description,0,70)}}</p>
                                    <div class="ticket-qty d-flex">
                                        <h4 class="">Available Quantity</h4>
                                        <div class="ticket-qty-num d-flex align-items-center"><span>{{$ticket->quantity}}</span></div>
                                    </div>
                                    <div class="ticket-btn text-center">
                                        <a type="button" href="/tickets/{{$ticket->id}}" type="button" class="btn btn-primary">Request This Ticket</a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        @endforeach

                </div>
            </div>
        {{ $tickets->links() }}
        </div>
    </section>
</div>
@endsection
