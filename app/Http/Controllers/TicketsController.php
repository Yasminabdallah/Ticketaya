<?php

namespace App\Http\Controllers;
use DB;
use App\Ticket;
use App\User;
use Image;
use Auth;
use App\Category;
use App\RequestedTicket;
use App\SoldTicket;
use App\Tag;
use App\Notification;
use App\Events\TicketRequested;
use App\Events\TicketReceived;
use App\Events\StatusTicketRequested;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Notifications\SpamNotification;

class TicketsController extends Controller
{
    public function index (){
        $tickets=Ticket::paginate(9);
       // $admin=DB::table('roles')->where('name','=','admin')->first();
       // dd($admin->name);
        if(Auth::check()){
        if(Auth::user()->hasRole('admin'))
        {
            return view('admin.tickets.index',compact('tickets'));
        }
    }
        return view('tickets.index',compact('tickets'));
     }

    public function show($id){
        $ticket = Ticket::find($id);
        if($ticket !== null){
        if(Auth::check()){
                $userSpam = DB::table('spam_tickets')->where('user_id' , '=' , Auth::user()->id)->get();
                $requestStatus = RequestedTicket::where([
                ['requester_id' , '=' , Auth::user()->id],
                ['ticket_id' , '=' , $id]
                ])->get();
                $wantStatus = true;
                if(sizeof($requestStatus) == 1){
                  $wantStatus = false;
                }
                $userSavedTicket=Auth::user()->savedTickets->contains($id);
                if(Auth::user()->hasRole('admin'))
                {
                    $numberofspams=$ticket->spammers->count();
                    return view('admin.tickets.show',compact('ticket',  'numberofspams' ));
                }
                return view('tickets.show' , compact('ticket' , 'userSpam' , 'wantStatus','userSavedTicket'));
            }
            return view('tickets.show' , compact('ticket'));
        }
        return view('notfound');
    }
    public function spamTicket($id){
        DB::table('spam_tickets')->insert([
            'ticket_id' => $id,
            'user_id' => Auth::user()->id
        ]);
        $ticket=Ticket::find($id);
        flashy()->error('This Ticket Spammed By You');
        return redirect('/tickets/'.$id );
    }
    public function reportview(Request $request){
        $ticket=Ticket::find($request->id);
        return view('tickets.report' , compact('ticket'));

    }
    public function report(Request $request){
        $message=$request->msg;
        $ticket=Ticket::find($request->ticket_id);
        $admin=DB::table('model_has_roles')->where('role_id','=',1)->first();
        $user=User::find($admin->model_id);
        $user->notify(new SpamNotification($ticket,$message,$user));
        flashy()->error('your message is sent ,Thank uou !');
        return redirect('/tickets/'.$request->ticket_id);
    }

     public function search (Request $request){
        $tickets=Ticket::latest()->paginate(3);
         if($request->search !== null){
             $tickets=Ticket::where('name', 'LIKE', '%'. Str::lower($request->search) .'%')
             ->latest()
             ->paginate(3)
             ->setpath('');
            $tickets->appends(['search'=> $request->search]);
         }
        if(Auth:: check() && Auth::user()->hasRole('admin')){


        return view('admin.search.Ticketsearch',['tickets'=> $tickets] );
        }

        return view('search.Ticketsearch',['tickets'=> $tickets] );
     }

    public function create (){
        $categories=Category::all();
        $view='tickets.create';
        if(Auth::check()){
        if(Auth::user()->hasRole('admin'))
        {
            $view='admin.tickets.create';
        }
        return view($view,compact('categories'));
    }
    else{
        return redirect('login');
    }
    }

    public function saveTicket($id){
        $user=Auth::user();
        if($user !== null){
        if(!$user->savedTickets->contains($id)){
            $user->savedTickets()->attach($id);
        }
        return response()->json(['res' => 'success']);
    }
    }
    public function unsaveTicket($id){
        $user=Auth::user();
        if($user !== null){
        if($user->savedTickets->contains($id)){
            $user->savedTickets()->detach($id);
        }
        return response()->json(['res' => 'success']);
    }
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required|min:3',
            'price'=>'required|numeric',
            'quantity'=>'required|integer|digits_between: 1,10',
            'photo'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'expire_date' => 'required|date|after:'.Carbon::now(),
            'user_id' => 'exists:users,id',
            'category' => 'exists:categories,id',
            'city' => 'exists:cities,id',
            'region' => 'exists:regions,id',

        ]);
        if(Auth::check() || Auth::user()->hasRole('admin') ){
        $ticket=new Ticket;
        if($request->hasFile('photo')){
            $request->file('photo')->store('public/images/tickets');
            $file_name = $request->file('photo')->hashName();
            $ticket->photo= $file_name;
        }
        $ticket->price =$request->price;
        $ticket->name = Str::lower($request->name);
        $ticket->description=$request->description;
        $ticket->user_id= Auth::user()->id;
        $ticket->quantity=$request->quantity;
        $ticket->region_id=$request->region;
        $ticket->city_id=$request->city;
        $ticket->expire_date=$request->expire_date;
        $ticket->category_id=$request->category;
        $ticket->type=1;
        $ticket->is_sold= 0;
        $ticket->save();
        if($ticket)
        {
            $tagNames = explode(',' ,$request->tags);
            $tagIds = [];
            foreach($tagNames as $tagName)
            {
                $tag = Tag::firstOrCreate(['name'=>$tagName]);
                if($tag)
                {
                  $tagIds[] = $tag->id;
                }

            }
            $ticket->tags()->sync($tagIds);
        }
    }
       return redirect('tickets');
    }

    public function edit($id){
        $ticket=Ticket::find($id);
        $categories=Category::all();
        $view='tickets.update';
        if($ticket !== null){
            if(Auth::check()&& Auth::user()->id == $ticket->user_id ){
                if(Auth::user()->hasRole('admin')){
                     $view='admin.tickets.update';
                    }
                return view($view,['ticket'=> $ticket,'categories'=>$categories] );
            }
        }
    return view('notfound');
    }

    public function update($id, Request $request){
        $ticket= Ticket::find($id);
        $user=Auth::user();
        if($ticket !== null && Auth::check() ){
            if($ticket->user_id == $user->id || $user->hasRole('admin') ){
                $ticket->price =$request->price;
                $ticket->name = $request->name;
                $ticket->description=$request->description;
                $ticket->user_id= Auth::user()->id;
                $ticket->quantity=$request->quantity;
                $ticket->region_id=$request->region;
                $ticket->city_id=$request->city;
                $ticket->expire_date=$request->expire_date;
                $ticket->category_id=$request->category;
                if($request->hasFile('photo')){
                    $request->file('photo')->store('public/images/tickets');
                    $file_name = $request->file('photo')->hashName();
                    $ticket->photo= $file_name;
                    }
                $ticket->save();
        }
    }
        return redirect('tickets');
     }

     public function destroy($id){
        $ticket=Ticket::find($id);
        $user=Auth::user();
        if($ticket !== null && Auth::check() ){
            if($ticket->user_id == $user->id || $user->hasRole('admin') ){
                $ticket->delete();
            }
        return redirect('tickets');
        }
        return view('notfound');
    }

}
