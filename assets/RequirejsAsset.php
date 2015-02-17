<?php
/**
 * Created by PhpStorm.
 * User: alx
 * Date: 17.02.15
 * Time: 23:13
 */

namespace app\assets;

use yii\web\AssetBundle;

class RequirejsAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $js = [
        'underscore/underscore.js',
        'backbone/backbone.js',
        'requirejs-bower/require.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}