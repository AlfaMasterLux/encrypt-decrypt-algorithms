<?php

namespace app\components;

/**
 * Class DecryptionDecorator
 * @package app\components
 */
class DecryptionDecorator extends Decorator
{
    public function __construct($fileName)
    {
        $this->fileType = substr($fileName, '0', strpos($fileName, '.'));
        $this->resourceEncrypted = $this->fileType . self::ENC_EXTENSION;
        $this->resource = $this->fileType . self::ORIG_EXTENSION;
        $this->mediaKey = $this->getMediaKey();
        $this->cryptoArray = $this->getCryptoArrayValues();
        $this->decryptFile();
    }

    private function decryptFile(): bool
    {
        $handle = fopen($this->resourceEncrypted, 'r');
        $file = fread($handle, filesize($this->resourceEncrypted));
        fclose($handle);

        $this->mac = substr($file, -10);
        $this->enc = explode($this->mac, $file)[0];
        $test = substr(hash_hmac($this->algorithm, $this->cryptoArray['iv'] . $this->enc, $this->cryptoArray['macKey'], true), 0, 10);

        if (hash_equals($test, $this->mac)) {
            $result = openssl_decrypt($this->enc, 'aes-256-cbc', $this->cryptoArray['cipherKey'], OPENSSL_RAW_DATA, $this->cryptoArray['iv']);
            $handler = fopen($this->resource, 'w+');
            $write = fwrite($handler, $result);
            fclose($handler);
        } else
            die("Data is Incorrect!");

        return true;
    }
}
