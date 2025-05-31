<?php

namespace app\components;

/**
 * Class EncryptionDecorator
 * @package app\components
 */
class EncryptionDecorator extends Decorator
{
    public function __construct($fileName)
    {
        $this->fileType = substr($fileName, '0', strpos($fileName, '.'));
        $this->resource = $this->fileType . self::ORIG_EXTENSION;

        $this->mediaKey = $this->getMediaKey();
        $this->cryptoArray = $this->getCryptoArrayValues();

        $this->encryptFile();
    }

    private function encryptFile(): bool
    {
        $this->resourceEncrypted = $this->fileType . self::ENC_EXTENSION;
        $content = ContentHelper::home($this->resource);
        $this->enc = openssl_encrypt($content, 'aes-256-cbc', $this->cryptoArray['cipherKey'], OPENSSL_RAW_DATA, $this->cryptoArray['iv']);
        $this->mac = substr(hash_hmac($this->algorithm, $this->cryptoArray['iv'] . $this->enc, $this->cryptoArray['macKey'], true), 0, 10);

        $handler = fopen($this->resourceEncrypted, 'w+');
        fwrite($handler, $this->enc . $this->mac);
        fclose($handler);

        return true;
    }
}
