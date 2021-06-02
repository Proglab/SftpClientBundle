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
### Generals :

#### Connection :

You must connect to an SFTP

You need the username, password, host and port (22 by default)

```php
use Proglab\SftpClientBundle\Service\SftpClient;

$client = new SftpClient();
$client->connect('username', 'password', 'host', 22);

```
#### Deconnection :
```php
use Proglab\SftpClientBundle\Service\SftpClient;

$client = new SftpClient();
$client->connect('username', 'password', 'host', 22);
$client->deco();

```

### Listings :

#### Remote dir :

List files on remote directory. the remote directory must be absolute

```php
use Proglab\SftpClientBundle\Service\SftpClient;

$client = new SftpClient();
$client->connect('username', 'password', 'host', 22);
$files = $client->getRemoteListFiles('/var/www/');
```

#### local dir :

List files on local directory. the local directory must be absolute

```php
use Proglab\SftpClientBundle\Service\SftpClient;

$client = new SftpClient();
$files = $client->getLocalListFiles('/var/www/');
```
### Operations :
#### upload :

Upload a file from local to remote dir :

```php
use Proglab\SftpClientBundle\Service\SftpClient;

$client = new SftpClient();
$files = $client->upload($fileLocalPath, $fileRemotePath, $delete = true);
```


#### download :
Donwload a file from remote to local dir :

```php
use Proglab\SftpClientBundle\Service\SftpClient;

$client = new SftpClient();
$files = $client->download($fileRemotePath, $fileLocalPath, $delete = true);
```
#### syncLocalDirToRemote :
Synchronize local files to remote directory :

```php
use Proglab\SftpClientBundle\Service\SftpClient;

$client = new SftpClient();
$files = $client->syncLocalDirToRemote($localDir, $remoteDir, $delete = true);
```
#### syncLocalDirToRemote :
Synchronize remote files to local directory :

```php
use Proglab\SftpClientBundle\Service\SftpClient;

$client = new SftpClient();
$files = $client->syncRemoteDirToLocal($remoteDir, $localDir, $delete = true);
```
