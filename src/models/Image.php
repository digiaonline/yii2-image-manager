<?php

namespace nord\yii\imagemanager\models;

use nord\yii\filemanager\models\File;
use Yii;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property integer $fileId
 * @property string $width
 * @property string $height
 * @property string $provider
 *
 * @property File $file
 */
class Image extends \yii\db\ActiveRecord
{
    const FORMAT_AUTO = 'auto';
    const FORMAT_JPG = 'jpg';
    const FORMAT_PNG = 'png';
    const FORMAT_GIF = 'gif';
    const FORMAT_BMP = 'bmp';
    const FORMAT_TIFF = 'tiff';
    const FORMAT_ICO = 'ico';
    const FORMAT_PDF = 'pdf';
    const FORMAT_EPS = 'eps';
    const FORMAT_PSD = 'psd';
    const FORMAT_SVG = 'svg';
    const FORMAT_WEBP = 'webp';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fileId', 'provider'], 'required'],
            [['width', 'height', 'fileId'], 'integer'],
            [['width', 'height', 'provider'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fileId' => 'File ID',
            'width' => 'Width',
            'height' => 'Height',
            'provider' => 'Provider',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::className(), ['id' => 'fileId']);
    }
}
