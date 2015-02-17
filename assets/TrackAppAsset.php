<?php
/**
 * Created by PhpStorm.
 * User: alx
 * Date: 18.02.15
 * Time: 0:21
 */

namespace app\assets;


use yii\web\AssetBundle;

class TrackAppAsset extends AssetBundle
{
    public $baseUrl = '@web';

    public $js = [
        'js/track.js'
    ];

    public $depends = [
        '\app\assets\RequirejsAsset'
    ];
}