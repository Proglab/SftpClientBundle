<?php

namespace Proglab\SftpClientBundle\Exception;

use Psr\Container\ContainerExceptionInterface;

class InvalidArgumentException extends \InvalidArgumentException implements ContainerExceptionInterface
{
}
