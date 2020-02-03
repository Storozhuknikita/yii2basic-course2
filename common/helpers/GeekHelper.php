<?php

namespace common\helpers;
class GeekHelper
{

    public static function brains($data)
    {
        return \yii\helpers\VarDumper::dumpAsString($data, 10);
    }

    public static function getPTagOptions()
    {
        return ['class' => 'p-tag', 'id' => 'id'];
    }
}