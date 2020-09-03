@extends('master')

@section('javascripts')
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Create an instance of the Stripe object
    // Set your publishable API key
    var stripe = Stripe('{{ env("STRIPE_PUBLISH_KEY") }}');

    // Create an instance of elements
    var elements = stripe.elements();

    var style = {
        base: {
            fontWeight: 400,
            fontFamily: '"DM Sans", Roboto, Open Sans, Segoe UI, sans-serif',
            fontSize: '16px',
            lineHeight: '1.4',
            color: '#1b1642',
            padding: '.75rem 1.25rem',
            '::placeholder': {
                color: '#ccc',
            },
        },
        invalid: {
            color: '#dc3545',
        }
    };

    var cardElement = elements.create('cardNumber', {
        style: style
    });
    cardElement.mount('#card_number');

    var exp = elements.create('cardExpiry', {
        'style': style
    });
    exp.mount('#card_expiry');

    var cvc = elements.create('cardCvc', {
        'style': style
    });
    cvc.mount('#card_cvc');

    // Validate input of the card elements
    var resultContainer = document.getElementById('paymentResponse');
    cardElement.addEventListener('change', function (event) {
        if (event.error) {
            resultContainer.innerHTML = '<p>' + event.error.message + '</p>';
        } else {
            resultContainer.innerHTML = '';
        }
    });

    // Get payment form element
    var form = document.getElementById('payment-form');

    // Create a token when the form is submitted.
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        createToken();
    });

    // Create single-use token to charge the user
    function createToken() {
        stripe.createToken(cardElement).then(function (result) {
            if (result.error) {
                // Inform the user if there was an error
                resultContainer.innerHTML = '<p>' + result.error.message + '</p>';
            } else {
                // Send the token to your server
                stripeTokenHandler(result.token);
            }
        });
    }

    // Callback to handle the response from stripe
    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }

    $('.pay-via-stripe-btn').on('click', function () {
        var payButton   = $(this);
        var name        = $('#name').val();
        var email       = $('#email').val();

        if (name == '' || name == 'undefined') {
            $('.generic-errors').html('Name field required.');
            return false;
        }
        if (email == '' || email == 'undefined') {
            $('.generic-errors').html('Email field required.');
            return false;
        }

        if(!$('#terms_conditions').prop('checked')){
            $('.generic-errors').html('The terms conditions must be accepted.');
            return false;
        }
    });

</script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h1 class="text-center">Stripe Payment Demo</h1>
            <hr>
            @if (session()->has('error'))
                <div class="text-danger font-italic">{{ session()->get('error') }}</div>
            @endif
            <form action="{{ url('payments') }}" method="post" id="payment-form">
                @csrf
                <div class="row form-group">
                    <div class="col-md-12">
                        <label for="name">Name</label>
                        @error('name')
                        <div class="text-danger font-italic">{{ $message }}</div>
                        @enderror
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <label for="email">Email</label>
                        @error('email')
                        <div class="text-danger font-italic">{{ $message }}</div>
                        @enderror
                        <input type="text" name="email" id="email" class="form-control" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <label>Billing Amount in US Dollars</label> <br>
                        <h2 class="text-muted">$1</h2>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <!-- Display errors returned by createToken -->
                        <label>Card Number</label>
                        <div id="paymentResponse" class="text-danger font-italic"></div>
                        <div id="card_number" class="field form-control"></div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label>Expiry Date</label>
                        <div id="card_expiry" class="field form-control"></div>
                    </div>
                    <div class="col-md-3">
                        <label>CVC Code</label>
                        <div id="card_cvc" class="field form-control"></div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="form-check form-check-inline custom-control custom-checkbox">
                            <input type="checkbox" name="terms_conditions" id="terms_conditions" class="custom-control-input">
                            <label for="terms_conditions" class="custom-control-label">
                                I agree to terms & conditions
                            </label>
                        </div>
                        @error('terms_conditions')
                        <div class="text-danger font-italic">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12 small text-muted">
                        <div class="alert alert-warning">
                            <strong>NOTE: </strong> All the payments are handled by <a target="_blank"
                                href="https://stripe.com">STRIPE</a>. We don't store any of your data.
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="text-danger font-italic generic-errors"></div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <input type="submit" value="Pay via Stripe" class="btn btn-primary pay-via-stripe-btn">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection