<?php

namespace app\components;

class StreamDecorator extends Decorator
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
        $this->resourceSidecar = $this->fileType . parent::SDC_EXTENSION;
        $content = ContentHelper::home($this->resource);
        $splitted = str_split($content, '64000');

        $result = '';
        $fp = fopen($this->resourceSidecar, 'a+');
        foreach ($splitted as $chunk) {
            $enc = openssl_encrypt($chunk, 'aes-256-cbc', $this->cryptoArray['cipherKey'], OPENSSL_RAW_DATA, $this->cryptoArray['iv']);
            $mac = substr(hash_hmac($this->algorithm, $this->cryptoArray['iv'] . $enc, $this->cryptoArray['macKey'], true), 0, 10);

            $result .= $mac;
            fwrite($fp, $result);
        }
        fclose($fp);

        return true;
    }
}
