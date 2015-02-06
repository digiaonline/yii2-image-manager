<?php

namespace nord\yii\imagemanager\providers\cloudinary;

use Imagine\Filter\Transformation;
use yii\base\Component;

class Parameters extends Component
{
    const RESOURCE_TYPE_IMAGE = 'image';
    const RESOURCE_TYPE_RAW = 'raw';
    const RESOURCE_TYPE_AUTO = 'auto';

    const TYPE_UPLOAD = 'upload';
    const TYPE_PRIVATE = 'private';
    const TYPE_AUTHENTICATED = 'authenticated';

    const RAW_CONVERT_ASPOSE = 'aspose';

    const CATEGORIZATION_REKOGNITION_SCENE = 'rekognition_scene';

    const DETECTION_REKOGNITION_FACE = 'rekognition_face';

    const MODERATION_MANUAL = 'manual';
    const MODERATION_WEBPURIFY = 'webpurify';

    /**
     * @var string
     */
    public $publicId;
    /**
     * @var string
     */
    public $resourceType;
    /**
     * @var string
     */
    public $type;
    /**
     * @var array
     */
    public $tags;
    /**
     * @var array
     */
    public $context;
    /**
     * @var Transformation|array
     */
    public $transformation;
    /**
     * @var string
     */
    public $format;
    /**
     * @var string|array
     */
    public $allowedFormats;
    /**
     * @var array
     */
    public $eager;
    /**
     * @var boolean
     */
    public $eagerAsync;
    /**
     * @var string
     */
    public $proxy;
    /**
     * @var string|array
     */
    public $headers;
    /**
     * @var string
     */
    public $callback;
    /**
     * @var string
     */
    public $notificationUrl;
    /**
     * @var string
     */
    public $eagerNotificationUrl;
    /**
     * @var boolean
     */
    public $backup;
    /**
     * @var boolean
     */
    public $returnDeleteToken;
    /**
     * @var boolean
     */
    public $faces;
    /**
     * @var boolean
     */
    public $exif;
    /**
     * @var boolean
     */
    public $colors;
    /**
     * @var boolean
     */
    public $imageMetadata;
    /**
     * @var boolean
     */
    public $pHash;
    /**
     * @var boolean
     */
    public $invalidate;
    /**
     * @var boolean
     */
    public $useFilename;
    /**
     * @var boolean
     */
    public $uniqueFilename;
    /**
     * @var string
     */
    public $folder;
    /**
     * @var boolean
     */
    public $overwrite;
    /**
     * @var boolean
     */
    public $discardOriginalFilename;
    /**
     * @var string
     */
    public $faceCoordinates;
    /**
     * @var string
     */
    public $customCoordinates;
    /**
     * @var string
     */
    public $rawConvert;
    /**
     * @var string
     */
    public $categorization;
    /**
     * @var float
     */
    public $autoTagging;
    /**
     * @var string
     */
    public $detection;
    /**
     * @var string
     */
    public $moderation;
    /**
     * @var string
     */
    public $uploadPreset;
    /**
     * @var array
     */
    public $html;

    public function toArray()
    {
        $array = [];
        foreach (array_keys(get_object_vars($this)) as $property) {
            if ($this->$property === null) {
                continue;
            }
            switch ($property) {
                case 'transformation':
                    if ($this->transformation instanceof Transformation) {
                        $this->transformation = $this->transformation->toArray();
                    }
                    $array[$property] = $this->transformation;
                    break;
                case 'allowedFormats':
                    $array['allowed_formats'] = implode(',', $this->allowedFormats);
                    break;
                default:
                    $array[$this->camelCaseToUnderscore($property)] = $this->$property;
                    break;
            }
        }
        return $array;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function camelCaseToUnderscore($string)
    {
        return strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $string));
    }
}