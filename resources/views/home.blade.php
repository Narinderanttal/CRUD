@extends('layouts.app')

@section('content')

                    

 @if($role == 'admin')              
<div class="container table-responsive py-5"> 
<div class="text-right">
    <a href="{{url('/')}}/add-user" class="btn btn-primary">add user</a>
</div>
<br>
<table class="table table-bordered table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Firstname</th>
      <th scope="col">Lastname</th>
      <th scope="col">Email</th>
      <th scope="col">Created At</th>
        <th scope="col">Profile Image</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  @if(count($users) > 0)
      
        @foreach($users as $value)
      <tr>
        <td>{{$a++}}</td>
        <td>{{$value->firstname}}</td>
       <td>{{$value->lastname}}</td>
        <td>{{$value->email}}</td>
         <td>{{$value->created_at}}</td>
         <td>
             @if($value->profile_image != '')
                <img src="{{ asset('storage/file/' . $value->profile_image) }}" style="width:50px" />
             
                <form action="" id="contactform" method="post">
                @csrf
                    <div class="form-group">
                        <input id="uploadfilepdf" type="file" name="sortpic" />
                    </div>
                    
                    <div class="form-group">
                      <button type="button" class="btn btn-primary" onclick="return uploadfile('{{$value->id}}')">update Now</button>
                    </div>
                </form>
            @else
                <form action="" id="contactform" method="post">
                @csrf
                    <div class="form-group">
                        <input id="uploadfilepdf" type="file" name="sortpic" />
                    </div>
                    
                    <div class="form-group">
                      <button type="button" class="btn btn-primary" onclick="return uploadfile('{{$value->id}}')">Send Now</button>
                    </div>
                </form>
            @endif    
         </td>
         <td><a href="{{url('/')}}/update-user/{{$value->id}}" class="btn btn-primary">update user</a>
          <a href="{{url('/')}}/delete-user/{{$value->id}}" class="btn btn-primary">delete user</a>
         </td>
        
      </tr>
      @endforeach
    @endif
    
  </tbody>
</table>
</div>
@else
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    function uploadfile(userid)
    {
        var file_data = $('#uploadfilepdf').prop('files')[0];   
        var form_data = new FormData();                  
        form_data.append('file', file_data);
        // console.log(form_data);
        //     alert(form_data);

        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $.ajax({
            type:'POST',
            url: "{{ url('uploadfile') }}/"+userid,
            data: form_data,
            contentType: false,
            processData: false,
            success: (response) => {

                if (response) {
                    alert('File has been uploaded successfully');
                    location.reload();
                }
            },
            error: function(response){
                console.log(response);
                $('#image-input-error').text(response.responseJSON.errors.file);
            }
        });
    }
</script>


@endsection
