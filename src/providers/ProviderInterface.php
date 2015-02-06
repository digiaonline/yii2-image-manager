<?php

namespace nord\yii\imagemanager\providers;

interface ProviderInterface
{
    /**
     * @param string $path
     * @param array $options
     * @return boolean
     */
    public function saveImage($path, array $options = []);

    /**
     * @param string $name
     * @param array $options
     * @return string
     */
    public function getImageUrl($name, array $options = []);

    /**
     * @param string $name
     * @param array $options
     * @return string
     */
    public function renderImage($name, array $options = []);

    /**
     * @param string $name
     * @param array $options
     * @return boolean
     */
    public function deleteImage($name, array $options = []);
}