<?php

namespace App\MediaLibrary;

use App\Models\Poll;
use App\Models\Quiz;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Testimonial;
use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

/**
 * Class CustomPathGenerator
 */
class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $path = '{PARENT_DIR}' . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;

        switch ($media->collection_name) {
            case User::PROFILE:
                return str_replace('{PARENT_DIR}', User::PROFILE, $path);
            case Quiz::QUIZ_PATH:
                return str_replace('{PARENT_DIR}', Quiz::QUIZ_PATH, $path);
            case Setting::APP_LOGO:
                return str_replace('{PARENT_DIR}', Setting::APP_LOGO, $path);
            case Setting::FAVICON:
                return str_replace('{PARENT_DIR}', Setting::FAVICON, $path);
            case Setting::LOGIN_PAGE_IMG:
                return str_replace('{PARENT_DIR}', Setting::LOGIN_PAGE_IMG, $path);
            case Subscription::ATTACHMENT:
                return str_replace('{PARENT_DIR}', Subscription::ATTACHMENT, $path);
            case Poll::POLL_IMAGES_1:
                return str_replace('{PARENT_DIR}', Poll::POLL_IMAGES_1, $path);
            case Poll::POLL_IMAGES_2:
                return str_replace('{PARENT_DIR}', Poll::POLL_IMAGES_2, $path);
            case Poll::POLL_IMAGES_3:
                return str_replace('{PARENT_DIR}', Poll::POLL_IMAGES_3, $path);
            case Poll::POLL_IMAGES_4:
                return str_replace('{PARENT_DIR}', Poll::POLL_IMAGES_4, $path);
            case Testimonial::ICON:
                return str_replace('{PARENT_DIR}', Testimonial::ICON, $path);
            default:
                return str_replace('{PARENT_DIR}', 'default', $path);
        }
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'thumbnails/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'rs-images/';
    }
}
