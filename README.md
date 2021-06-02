# SftpClientBundle
An SFTP client

Installation
============

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
composer require proglab/sftp_client_bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
composer require proglab/sftp_client_bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Proglab\SftpClientBundle\SftpClientBundle::class => ['all' => true],
];
```

Usage
=====

### Connection :

Connect to a SFTP

```php
use Proglab\SftpClientBundle\Service\SftpClient;

$client = new SftpClient();
$client->connect('username', 'password', 'ip_server', 22);

```