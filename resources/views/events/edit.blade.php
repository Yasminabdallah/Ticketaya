@extends('layouts.app')
@section('content')

<form  method="post" action="/events/{{$event->id}}" enctype="multipart/form-data">
{{method_field('PUT')}}
{{csrf_field()}}
<label >Name </label>
<input type="text" name="name"  value={{$event->name}} />
@if ($errors->has('name'))
    <span class="alert alert-danger">
    <strong>{{ $errors->first('name') }}</strong>
    </span>
@endif
<br/>
<label >description</label>
<textarea name="description" value={{$event->description}} >{{$event->description}}</textarea>
@if ($errors->has('description'))
    <span class="alert alert-danger">
    <strong>{{ $errors->first('description') }}</strong>
    </span>
@endif
<br/>
<label >Quantity </label>
<input type="number" name="avaliabletickets" value={{$event->avaliabletickets}} />
@if ($errors->has('avaliabletickets'))
    <span class="alert alert-danger">
    <strong>{{ $errors->first('avaliabletickets') }}</strong>
    </span>
@endif
<br/>
         <label >City </label>
           <select name="city" id="city">
               @foreach($cities as $city)
                   <option value="{{$city->id}}" {{ ($event->city_id == $city->id ) ? "selected" : "" }}>{{$city->name}}</option>
               @endforeach
           </select>
          @if ($errors->has('city'))
                <span class="alert alert-danger">
                    <strong>{{ $errors->first('city') }}</strong>
                </span>
      <br/>
      <label >Region </label>
               <select name="region" id="region">
               @if($event>region_id)
               @foreach($regions as $region)
                   <option value="{{$region->id}}" {{ ($event->region_id == $region->id ) ? "selected" : "" }}>{{$region->name}}</option>
               @endforeach
               @endif
               </select>
           @if ($errors->has('region'))
                <span class="alert alert-danger">
                     <strong>{{ $errors->first('region') }}</strong>
                </span>
           @endif
<label >Start Date </label>
<input type="date" name="startdate" value={{$event->startdate}}/>
<br/>
<label >End Date </label>
<input type="date" name="enddate" value={{$event->enddate}}/>
<br/>
<label for="image">Event Image</label>
<input type="file" class="form-control-file" name="photo" value={{$event->photo}}/>
@if ($errors->has('photo'))
    <span class="alert alert-danger">
    <strong>{{ $errors->first('photo') }}</strong>
    </span>
@endif
<br/>
<label >Category</label>
<select name="category">
        @foreach($categories as $category)
          <option value="{{ $category->id }}" >{{ $category->name }}</option>
        @endforeach
</select>
@if ($errors->has('category'))
    <span class="alert alert-danger">
    <strong>{{ $errors->first('category') }}</strong>
    </span>
@endif
<input type="submit" value="Submit" class="btn btn-primary">
</form>
<script>
$(document).ready(function(){
    $('#city').on('change',function(){

        var cityId=$(this).val();
        console.log(cityId)
        $('#region').empty();
        $.ajax({
            url: '/cities/'+cityId,
            type: 'GET' ,
            data:{
                 '_token':'@csrf'
             },
            success:function(response){
                if(response.res == 'success'){
                $.each(response.cityRegions, function(index,region){
                var option=`<option value="`+region.id+`">`+region.name+`</option>`;
                $('#region').append(option);
            });
            $('#toggleRegion').show();
            }

             }
        })
      

    });
});


</script>
@endsection
