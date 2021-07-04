<?php

namespace jorenvanhee\templateguard;

use craft\web\AssetBundle as CraftAssetBundle;

class AssetBundle extends CraftAssetBundle
{
    public function init()
    {
        $this->sourcePath = '@jorenvanhee/templateguard/resources';

        $this->css = [
            'css/styles.css',
        ];

        parent::init();
    }
}
