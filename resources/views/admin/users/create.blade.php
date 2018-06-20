@extends('admin.index')
@section('content')
<div class="container">
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card">
<div class="card-body">

<form  method="post" action="/users" enctype="multipart/form-data">
{{method_field('POST')}}
{{csrf_field()}}
<div class="form-group row">
  <label class="col-md-4 col-form-label text-md-right" >Name </label>
    <div class="col-md-6">
        <input type="text" name="name"   />
        @if ($errors->has('name'))
             <span class="alert alert-danger">
                <strong>{{ $errors->first('name') }}</strong>
             </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-md-4 col-form-label text-md-right" >Email </label>
        <div class="col-md-6">
            <input type="text" name=email  />
            @if ($errors->has('email'))
                 <span class="alert alert-danger">
                    <strong>{{ $errors->first('email') }}</strong>
                 </span>
            @endif
        </div>
</div>
<div class="form-group row">
    <label class="col-md-4 col-form-label text-md-right" >Phone Number </label>
        <div class="col-md-6">
            <input type="text" name=phone  />
            @if ($errors->has('phone'))
                 <span class="alert alert-danger">
                    <strong>{{ $errors->first('phone') }}</strong>
                 </span>
            @endif
        </div>
</div>



<div class="form-group row">
     <label class="col-md-4 col-form-label text-md-right">City </label>
        <div class="col-md-6">
            <select name="city" id="city">
                @foreach($cities as $city)
                    <option value={{$city->id}}>{{$city->name}}</option>
                @endforeach
            </select>
           @if ($errors->has('city'))
                 <span class="alert alert-danger">
                     <strong>{{ $errors->first('city') }}</strong>
                 </span>
            @endif
        </div>
</div>
<br/>

<div class="form-group row" id="toggleRegion" style="display: none;" >
       <label class="col-md-4 col-form-label text-md-right">Region </label>
            <div class="col-md-6" >
                <select name="region" id="region"></select>
            @if ($errors->has('region'))
                 <span class="alert alert-danger">
                      <strong>{{ $errors->first('region') }}</strong>
                 </span>
            @endif
            </div>
</div>
<br/>


<div class="form-group row">
<label for="image" class="col-md-4 col-form-label text-md-right">Avatar</label>
<div class="col-md-6">
<input type="file" class="form-control-file" name="avatar" />
@if ($errors->has('avatar'))
    <span class="alert alert-danger">
    <strong>{{ $errors->first('avatar') }}</strong>
    </span>
@endif
</div>

</div>

<div class="form-group row">
<label for="role" class="col-md-4 col-form-label text-md-right">Admin Role</label>
<div class="col-md-6">
<input type="checkbox" name="role" value="1">
@if ($errors->has('role'))
    <span class="alert alert-danger">
    <strong>{{ $errors->first('role') }}</strong>
    </span>
@endif
</div>

</div>



 <div class="form-group row">
     <label for="password" class="col-md-4 col-form-label text-md-right" >{{ __('Password') }}</label>

    <div class="col-md-6">
       <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"  required>

        @if ($errors->has('password'))
        <span class="invalid-feedback">
          <strong>{{ $errors->first('password') }}</strong>
        </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

        <div class="col-md-6">
           <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  required>
        </div>
</div>




<input type="submit" value="Submit" class="btn btn-primary">
</form>
</div>
</div>
</div>
</div>
</div>

@endsection

