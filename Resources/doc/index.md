Atom Authentication Bundle
==========================

Installation
------------

1. Add this bundle to your project in composer.json:

    ```json
    {
        "require": {
            "atom/authentication-bundle": "dev-master",
        }
    }
    ```

2. Add this bundle to your app/AppKernel.php:

    ``` php
    // application/ApplicationKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Atom\AuthenticationBundle\AtomAuthenticationBundle(),
            // ...
        );
    }
    ```
