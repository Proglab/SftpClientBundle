SftpClientBundle
================

A SFTP client

Installation
------------

Open a command console, enter your project directory and execute:

```console
composer require proglab/sftp-client-bundle
```

If you're not using symfony/flex, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Proglab\SftpClientBundle\SftpClientBundle::class => ['all' => true],
];
```

Usage
-----

### Generals

#### Getting a SftpClient

You have two choices:

1. Create the client manually, and pass it a logger:

```php
use Proglab\SftpClientBundle\Service\SftpClient;
use Psr\Log\NullLogger;

$logger = new NullLogger();
$client = new SftpClient($logger);
```

2. Get the client from Dependency Injection:

```php
use Proglab\SftpClientBundle\Service\SftpClient;

class Service
{
    public function __construct(private SftpClient $client)
    {
    }
}
```

#### Connection

You must connect to a SFTP server.

You need the username, password, host and port (22 by default).

```php
$client->connect('username', 'password', 'host', 22);
```

#### Deconnection

```php
$client->deco();
```

### List files

#### From remote directoty

List files in remote directory. The remote directory path must be absolute.

```php
$files = $client->getRemoteListFiles('/var/www/');
```

#### From local directory

List files in local directory. The local directory path must be absolute.

```php
$files = $client->getLocalListFiles('/var/www/');
```

### Operations

#### Upload

Upload a file from local to remote dir:

```php
$files = $client->upload($fileLocalPath, $fileRemotePath, $delete = true);
```

#### Download

Download a file from remote to local dir:

```php
$files = $client->download($fileRemotePath, $fileLocalPath, $delete = true);
```

#### Sync local dir to remote

Synchronize local files to remote directory:

```php
$files = $client->syncLocalDirToRemote($localDir, $remoteDir, $delete = true);
```

#### Sync remote dir to local

Synchronize remote files to local directory:

```php
$files = $client->syncRemoteDirToLocal($remoteDir, $localDir, $delete = true);
```

Thanks
------

Many thanks to [jmsche](https://github.com/jmsche) for his help