<?php

namespace nord\yii\imagemanager\providers\cloudinary;

use Cloudinary;
use Cloudinary\Uploader;
use nord\yii\imagemanager\providers\ProviderInterface;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class Provider extends Component implements ProviderInterface
{
    private $_cloudName;
    private $_apiKey;
    private $_apiSecret;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Cloudinary::config([
            'cloud_name' => $this->_cloudName,
            'api_key' => $this->_apiKey,
            'api_secret' => $this->_apiSecret,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function saveImage($path, array $options = [])
    {
        $name = ArrayHelper::getValue($options, 'name');
        if (isset($options['upload'])) {
            $config = ArrayHelper::remove($options, 'upload', []);
            $parameters = $this->createParameters($config);
            $options = ArrayHelper::merge($options, $parameters->toArray());
        }
        if (!isset($options['public_id'])) {
            $options['public_id'] = substr($name, 0, strrpos($name, '.'));
        }
        $array = Uploader::upload($path, $options);
        return !isset($array['error']);
    }

    /**
     * @inheritdoc
     */
    public function getImageUrl($name, array $options = [])
    {
        if (isset($options['transformation'])) {
            $options = $this->applyTransformation($options);
        }
        return cloudinary_url($name, $options);
    }

    /**
     * @inheritdoc
     */
    public function renderImage($name, array $options = [])
    {
        if (isset($options['transformation'])) {
            $options = $this->applyTransformation($options);
        }
        return cl_image_tag($name, $options);
    }

    /**
     * @param string $name
     * @param array $options
     * @return boolean
     */
    public function deleteImage($name, array $options = [])
    {
        $array = \Cloudinary\Uploader::destroy($name, $options);
        return !isset($array['error']);
    }

    /**
     * @param mixed $cloudName
     */
    public function setCloudName($cloudName)
    {
        $this->_cloudName = $cloudName;
    }

    /**
     * @param mixed $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
    }

    /**
     * @param mixed $apiSecret
     */
    public function setApiSecret($apiSecret)
    {
        $this->_apiSecret = $apiSecret;
    }

    /**
     * @param array $config
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    protected function createParameters(array $config)
    {
        if (!isset($config['class'])) {
            $config['class'] = Parameters::className();
        }
        return Yii::createObject($config);
    }

    /**
     * @param $options
     * @return array
     */
    protected function applyTransformation(array $options)
    {
        if (is_string($options['transformation'])) {
            return $options;
        }
        $config = ArrayHelper::remove($options, 'transformation');
        $transformation = $this->createTransformation($config);
        $options = ArrayHelper::merge($options, $transformation->toArray());
        return $options;
    }

    /**
     * @param array $config
     * @return object
     */
    protected function createTransformation(array $config)
    {
        if (!isset($config['class'])) {
            $config['class'] = Transformation::className();
        }
        return Yii::createObject($config);
    }
}