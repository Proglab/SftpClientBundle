<?php

namespace Proglab\SftpClientBundle\Exception;

use Psr\Container\ContainerExceptionInterface;

class FileException extends \InvalidArgumentException implements ContainerExceptionInterface
{
}
