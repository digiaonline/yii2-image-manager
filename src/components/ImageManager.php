<?php

namespace nord\yii\imagemanager\components;

use nord\yii\filemanager\components\FileManager;
use nord\yii\filemanager\resources\ResourceInterface;
use nord\yii\imagemanager\models\Image;
use nord\yii\imagemanager\providers\cloudinary\Provider;
use nord\yii\imagemanager\providers\ProviderInterface;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;

class ImageManager extends Component
{
    // Canonical component ID for this component.
    const COMPONENT_ID = 'imageManager';

    // Name of the default provider.
    const DEFAULT_PROVIDER = 'cloudinary';

    /**
     * @var array
     */
    public $providers = [];
    /**
     * @var string
     */
    public $modelClass;

    private $_providers;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!isset($this->modelClass)) {
            $this->modelClass = Image::className();
        }

        $this->initProviders();
    }

    /**
     * @param array $config
     * @return Image
     */
    public function createImage(array $config = [])
    {
        $modelClass = $this->modelClass;
        return new $modelClass($config);
    }

    /**
     * @param ResourceInterface $resource
     * @param array $config
     * @return Image
     * @throws Exception
     */
    public function saveImage(ResourceInterface $resource, array $config = [])
    {
        $modelConfig = ArrayHelper::remove($config, 'image', []);
        $providerConfig = ArrayHelper::remove($config, 'provider', []);

        $fileManager = $this->getFileManager();
        $file = $fileManager->saveFile($resource, $config);

        $model = $this->createImage($modelConfig);
        $model->setAttributes([
            'fileId' => $file->id,
            'provider' => ArrayHelper::remove($providerConfig, 'name', self::DEFAULT_PROVIDER),
        ]);
        if (!$model->save()) {
            throw new Exception('Failed to save image model.');
        }

        $filename = $fileManager->getFilePath($model->file);
        $providerConfig['name'] = $model->file->getFilePath();
        if (!$this->getProvider($model->provider)->saveImage($filename, $providerConfig)) {
            throw new Exception("Failed to save image to provider '{$model->provider}'.");
        }
        return $model;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function findImage()
    {
        /** @var Image $modelClass */
        $modelClass = $this->modelClass;
        return $modelClass::find();
    }

    /**
     * @param integer $id
     * @param array $options
     * @return string
     */
    public function getImageUrl($id, array $options = [])
    {
        $model = $this->findImage()->where(['id' => $id])->one();
        $name = $model->file->getFilePath();
        return $this->getProvider($model->provider)->getImageUrl($name, $options);
    }

    /**
     * @param integer $id
     * @param array $options
     * @return string
     */
    public function renderImage($id, array $options = [])
    {
        $model = $this->findImage()->where(['id' => $id])->one();
        $name = $model->file->getFilePath();
        return $this->getProvider($model->provider)->renderImage($name, $options);
    }

    /**
     * @param integer $id
     * @return boolean
     * @throws Exception
     */
    public function deleteImage($id)
    {
        $model = $this->findImage()->where(['id' => $id])->one();
        if (!$model) {
            throw new Exception('Failed to find image model to delete.');
        }
        $name = $this->getFileManager()->getFilePath($model->file);
        if (!$this->getProvider($model->provider)->deleteImage($name)) {
            throw new Exception("Failed to delete image from provider '{$model->provider}'.");
        }
        if (!$model->delete()) {
            throw new Exception('Failed to delete image model.');
        }
        return true;
    }

    /**
     *
     */
    protected function initProviders()
    {
        $this->providers = ArrayHelper::merge(
            [
                'cloudinary' => ['class' => Provider::className()],
            ],
            $this->providers
        );

        $this->_providers = [];
        foreach ($this->providers as $name => $config) {
            $this->_providers[$name] = Yii::createObject($config);
        }
    }

    /**
     * @param string $name
     * @return ProviderInterface
     */
    protected function getProvider($name)
    {
        if (!isset($this->_providers[$name])) {
            throw new InvalidParamException("Trying to get unknown provider '$name'.");
        }
        return $this->_providers[$name];
    }

    /**
     * @return null|FileManager
     * @throws \yii\base\InvalidConfigException
     */
    protected function getFileManager()
    {
        return Yii::$app->get(FileManager::COMPONENT_ID);
    }
}
