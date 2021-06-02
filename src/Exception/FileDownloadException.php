<?php

namespace Proglab\SftpClientBundle\Exception;

use Psr\Container\ContainerExceptionInterface;

class FileDownloadException extends \InvalidArgumentException implements ContainerExceptionInterface
{
}
