# php-cas

An implementation of PHP CAS, referenced here http://jasig.github.io/cas/

Followed the spec, and implemented more OOP than the given library found here: https://github.com/Jasig/cas

Currently this implementation is SSO only without any proxy granting functionality for simple SSO only use-cases.


## USAGE GUIDE

1) Set up your config file located at PHPCas/config.php

2) Instantiate the PHPCasProvider in your code

```php

    $provider = PHPCasProvider::make();

```

3) When a login is required use any of the login methods available through the $provider object


```php

    /**
     * Do the basic cas authentication which involves the following steps:
     *
     * 1) Redirect the user to the CAS sso server
     * 2) User authenticates with the CAS sso server if not already logged in
     * 3) CAS server redirects user back to the provided $callbackURL
     */
    $provider->doBasicCasAuthentication('https://' . $_SERVER['http_host'] . '/auth/cas');


    /***
     * Do a basic cas authentication without redirecting back to this app
     *
     * 1) Redirect user to the CAS sso server
     * 2) User authenticates with the CAS sso server if not already logged in
     * 3) User flow is dictated by the CAS server
     */
    $provider->doBasicCasAuthenticationWithoutRedirectingBack();


    /**
     * Go to the cas authentication sso server and force user to input credentials
     * regardless of their current logged in status.
     *
     * 1) Redirect the user to the CAS sso server
     * 2) User authenticates with the CAS sso server regardless of current logged in status
     * 3) CAS server redirects user back to the provided $callbackURL
     */
    $provider->makeUserLoginOrReAuthenticate('https://' . $_SERVER['http_host'] . '/auth/cas');


    /**
     * Go to the CAS authentication sso server and if the user is already logged in return back with
     * the logged in ticket, otherwise return back without one
     *
     */
    $provider->authenticateUserOnlyIfAlreadyLoggedIn('https://' . $_SERVER['http_host'] . '/auth/cas');

```


4) Validate the login ticket provided by the cas user and log the user in to your application

```php

    if (isset($_REQUEST['ticket')) {

        $serviceCallbackUrl = 'https://' . $_SERVER['http_host'] . '/auth/cas';

        try {

            $provider->handleLoginResponse($serviceCallbackUrl, $_REQUEST['ticket']);

            $authenticatedUser = $provider->getAuthenticatedUser();

            // do something with your newly authenticated user!

        } catch (JCrowe\PHPCas\Exceptions\InvalidTicketException $e) {

            // invalid ticket, we need to have the user re-authenticate

            $provider->doBasicCasAuthentication($serviceCallbackUrl);
        }
    }

```