<?php

namespace Proglab\SftpClientBundle\Service;

use Proglab\SftpClientBundle\Exception\FileDownloadException;
use Proglab\SftpClientBundle\Exception\FileException;
use Proglab\SftpClientBundle\Exception\FileUploadException;
use Proglab\SftpClientBundle\Exception\InvalidArgumentException;
use Psr\Log\LoggerInterface;

/**
 * Sftp Client.
 */
class SftpClient
{
    /** @var resource|null */
    private $sftp;
    private LoggerInterface $logger;
    /** @var false|resource */
    private $connection;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Connect to an SFTP.
     *
     * @param string $serverUsername server username
     * @param string $serverPassword server password
     * @param string $host server host
     * @param int $serverPort server port (22 by default)
     * @return self
     */
    public function connect(string $serverUsername, string $serverPassword, string $host, int $serverPort = 22): self
    {
        $this->connection = ssh2_connect($host, $serverPort);
        if (!ssh2_auth_password($this->connection, $serverUsername, $serverPassword)) {
            $this->logger->emergency('Cannot connect to server '.$host.':'.$serverPort);
            throw new InvalidArgumentException('Cannot connect to server '.$host.':'.$serverPort);
        }
        $this->sftp = ssh2_sftp($this->connection);

        return $this;
    }

    /**
     * list remote directory.
     *
     * @param string $dir The dir (begin by /)
     */
    public function getRemoteListFiles(string $dir): array
    {
        $return = [];
        $dirs = scandir('ssh2.sftp://'.(int) $this->sftp.$dir);
        foreach ($dirs as $d) {
            if ('.' !== $d && '..' !== $d) {
                $return[] = $d;
            }
        }

        return $return;
    }

    /**
     * list local directory.
     */
    public function getLocalListFiles(string $localDir): array
    {
        $localFiles = glob($localDir.'*.*');
        foreach ($localFiles as $key => $file) {
            $localFiles[$key] = basename($file);
        }

        return $localFiles;
    }


    /**
     * Download a file.
     */
    public function download(string $fileRemotePath, string $fileLocalPath, bool $delete = false): bool
    {
        $this->logger->debug('download '.$fileRemotePath.' to '.$fileLocalPath);
        $stream = fopen('ssh2.sftp://'.(int) $this->sftp.$fileRemotePath, 'rb');
        if (!$stream) {
            $this->logger->emergency('Could not open file: '.$fileRemotePath);
            throw new FileException('Could not open file: ');
        }
        $contents = stream_get_contents($stream);
        @fclose($stream);
        file_put_contents($fileLocalPath, $contents);
        if (filesize('ssh2.sftp://'.(int) $this->sftp.$fileRemotePath) === filesize($fileLocalPath)) {
            if (true === $delete) {
                unlink('ssh2.sftp://'.(int) $this->sftp.$fileRemotePath);
            }

            return true;
        }

        throw new FileDownloadException($fileLocalPath.' and '.$fileRemotePath.' d\'ont have the same size');
    }


    /**
     * upload a file.
     */
    public function upload(string $fileLocalPath, string $fileRemotePath, bool $delete = false): bool
    {
        $this->logger->debug('upload '.$fileLocalPath.' to '.$fileRemotePath);
        $stream = fopen('ssh2.sftp://'.(int) $this->sftp.$fileRemotePath, 'wb+');
        $dataToSend = file_get_contents($fileLocalPath);
        fwrite($stream, $dataToSend);
        fclose($stream);
        if (filesize('ssh2.sftp://'.(int) $this->sftp.$fileRemotePath) === filesize($fileLocalPath)) {
            if (true === $delete) {
                unlink($fileLocalPath);
            }

            return true;
        }
        throw new FileUploadException($fileLocalPath.' and '.$fileRemotePath.' d\'ont have the same size');
    }

    /**
     * Synchronise Local files to remote directory.
     */
    public function syncLocalDirToRemote(string $localDir, string $remoteDir, bool $delete = true): array
    {
        $localFiles = $this->getLocalListFiles($localDir);
        $remoteFiles = $this->getRemoteListFiles($remoteDir);
        $file_to_upload = array_diff($localFiles, $remoteFiles);
        foreach ($file_to_upload as $file) {
            $this->upload($localDir.$file, $remoteDir.$file, $delete);
        }

        return $file_to_upload;
    }

    /**
     * Synchronise remote directory to local files.
     */
    public function syncRemoteDirToLocal(string $remoteDir, string $localDir, bool $delete = true): array
    {
        $localFiles = $this->getLocalListFiles($localDir);
        $remoteFiles = $this->getRemoteListFiles($remoteDir);
        $file_to_download = array_diff($remoteFiles, $localFiles);
        foreach ($file_to_download as $file) {
            $this->download($remoteDir.$file, $localDir.$file, $delete);
        }

        return $file_to_download;
    }

    /**
     * stop connection to the SFTP.
     */
    public function deco(): void
    {
        $this->connection = false;
    }

    public function __destruct()
    {
        $this->deco();
    }
}
