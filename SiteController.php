<?php

namespace app\controllers;

use app\components\DecryptionDecorator;
use app\components\EncryptionDecorator;
use yii\web\Controller;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{

    public function actionIndex()
    {
        $path = $_SERVER['DOCUMENT_ROOT'];
        $originalImage = $path . '/samples/IMAGE.original';
        $encryptedImage = $path . '/samples/IMAGE.encrypted';
        $image = new EncryptionDecorator($originalImage);
        $image = new DecryptionDecorator($encryptedImage);

        return $encryptedImage;
    }
}
