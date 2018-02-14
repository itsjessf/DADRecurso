@extends('mail.email')

@section('content')

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-10">
        <p>Dear, {{ $user->name }}</p>
        <p>Your account has been reactivated because of the following reason:</p>
        <p>{{$user->reason_reactivated}}</p>        
    </div>
</div>

@endsection

@section('footer')

@endsection