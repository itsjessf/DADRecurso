@extends('mail.email')

@section('content')

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-10">
        <p>Dear, {{ $user->name }}</p>
        <p>You have been blocked from our application because of the following reason:</p>
        <p>{{$user->reason_blocked}}</p>       
    </div>
</div>

@endsection

@section('footer')

@endsection