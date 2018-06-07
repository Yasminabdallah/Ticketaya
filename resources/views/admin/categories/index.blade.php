@extends('admin.index')
@section('content')
<a href={{ URL::to('admin/categories/create' )}} >
    <input type="button" class="btn btn-success" value='Create Category '/></a>
  <br/>
  <div class="container">
        <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
        @foreach ($categories as $category )
        <tr>
        <td>{{$category->id}}</td>
        <td>{{$category->name}}</td>
        <td>
            <a href={{ URL::to('categories/' . $category->id ) }} type="button" class="btn btn-success" >View</a>
            <a href={{ URL::to('categories/edit/' . $category->id ) }} type="button" class="btn btn-warning" >Edit</a>
            <form action="{{URL::to('categories/' . $category->id ) }}" onsubmit="return confirm('Do you really want to delete?');" method="post" ><input name="_method" value="delete" type="submit" class="btn btn-danger" />
                          {!! csrf_field() !!}
                          {{method_field('Delete')}}
            </form>
        </td>
        </tr>
        @endforeach
     </tbody>
              </table>
  </div>
@endsection
