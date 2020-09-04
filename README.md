## About This Project

Stripe Integration With Laravel 7.X

Installation Steps

- Clone the project
- Add the STRIPE keys in .env file of your project
- Stripe package installation

    If you have cloned this project then just run the following command
    
    ```
    composer install
    ```

    If you are directly integrating in your project then use the following command

    ```
    composer require stripe/stripe-php
    ```

- Now add the stripe keys in .env file with same key name and your own stripe values
    
    ```
    STRIPE_PUBLISH_KEY=pk_test...
    STRIPE_SECRET_KEY=sk_test...
    ```

## Read More On

I have written whole article on this - <a href="https://stackcoder.in/posts/stripe-payment-integration-with-laravel">Stripe Integration With Laravel</a>