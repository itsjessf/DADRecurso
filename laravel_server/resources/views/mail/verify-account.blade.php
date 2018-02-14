@extends('mail.email')

@section('content')

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <p>Dear, {{ $user->name }}</p>
            <p>You are receiving this email because we received a request to create your account.</p>
            <p>In order to finalize your request click in the link bellow</p>
            <a href="{{ route('account.confirmation', $user->nickname) }} " class="btn btn-info">Click Here</a>
        
            </div>
    </div>

@endsection

@section('footer')

@endsection