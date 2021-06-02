<?php

namespace Proglab\SftpClientBundle\Exception;

use Psr\Container\ContainerExceptionInterface;

class FileUploadException extends \InvalidArgumentException implements ContainerExceptionInterface
{
}
