<?php

namespace App\Event\Subscriber;

use App\Entity\ComplaintPicture;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ComplaintPictureSerializeSubscriber implements EventSubscriberInterface
{

    private static $sourceFilterMap = [
        [
            'source' => 'previewNormal',
            'filter' => 'complaint_picture_normal',
        ],
        [
            'source' => 'previewMiddle',
            'filter' => 'complaint_picture_preview_middle',
        ],
        [
            'source' => 'previewSmall',
            'filter' => 'complaint_picture_preview_small',
        ]

    ];

    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;


    /**
     * @var CacheManager
     */
    private $pictureManager;


    public function __construct(UploaderHelper $uploaderHelper, CacheManager $pictureManager)
    {
        $this->uploaderHelper = $uploaderHelper;
        $this->pictureManager = $pictureManager;
    }

    /**
     * Returns the events to which this class has subscribed.
     *
     * Return format:
     *     array(
     *         array('event' => 'the-event-name', 'method' => 'onEventName', 'class' => 'some-class', 'format' => 'json'),
     *         array(...),
     *     )
     *
     * The class may be omitted if the class wants to subscribe to events of all classes.
     * Same goes for the format key.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerializeHandler',
                'class' => ComplaintPicture::class,
                'format' => 'json',
                'priority' => 0
            ]
        ];
    }

    public function onPostSerializeHandler(ObjectEvent $event)
    {

        /** @var ComplaintPicture $attachment */
        $attachment = $event->getObject();

        $originalAsset = $this->uploaderHelper->asset($attachment, 'imageFile');
        $sources = [];

        $sources['original'] = $originalAsset;

        if (!empty($originalAsset))
        {
            foreach (self::$sourceFilterMap as $config)
            {
                $sources[$config['source']] = $this->pictureManager->getBrowserPath($originalAsset,  $config['filter']);
            }
        }

        $event->getVisitor()->addData('sources', $sources);
    }
}
