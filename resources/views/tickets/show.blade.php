            <fieldset>
                    <legend style="background-color: gray">Ticket Info </legend>
                    <img src="{{ asset('storage/images/tickets/'. $ticket->photo) }}" style="width:150px; height:150px;">
                    <p>Name : {{ $ticket->name }}</p>
                    <p>Quantity:{{ $ticket->quantity }}</p>
                    <p>Description:{{ $ticket->description }}</p>
                    <p>Price :{{ $ticket->price }}</p>
                    <p>Date :{{ $ticket->expire_date }}</p>
                    <p>Category :{{ $ticket->category_id }}</p>
                    <p>Location:{{ $ticket->region }},{{ $ticket->city }}</p>
                    <p>Created by :{{ $ticket->user->name }} </p>
                    @if($ticket->tags)
                    <p>
                        @foreach($ticket->tags as $tag)
                        <a href={{ URL::to('tags/'.$tag->id.'/tickets') }} type="button" class="btn btn-success" >{{$tag->name}}</a>
                        @endforeach
                    </p>
                    @endif
                   <hr>
                </fieldset>
@foreach ($userSpam as $spam )
     @if($spam->user_id == 1)
     {{-- @if($spam->user_id == Auth::user()->id ) --}}

      You Spamed This Ticket
      @else
      <form method="POST" action="/tickets/spam/{{$ticket->id}}">
        @csrf
      <input type="submit" value="spam">
    </form>
     @endif
@endforeach

  <form method="POST" action="/tickets/request/{{$ticket->id}}">

<h4>{{ $ticket->name }}</h4>
    @csrf
  <input type="number" name="quantity" placeholder="Quantitiy">
  <input type="submit" value="i want this ticket">
</form>
