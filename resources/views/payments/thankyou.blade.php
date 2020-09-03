@extends('master')

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">Thank you for making payment.</h2>
                </div>
                @if (Request::has('receipt_url'))
                    <h4 class="text-center">
                        <a href="{{ Request::get('receipt_url') }}" target="_blank">
                            Click here to download you payment receipt
                        </a>
                    </h4>
                @endif
            </div>
            <br>
        </div>
    </div>
@endsection