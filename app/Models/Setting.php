<?php

namespace App\Models;

use Filament\Forms\Get;
use Spatie\MediaLibrary\HasMedia;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Spatie\MediaLibrary\InteractsWithMedia;
use Filament\Forms\Components\ToggleButtons;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class Setting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_name',
        'email',
        'logo',
        'favicon',
        'contact',
        'prefix_code',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'instagram_url',
        'pinterest_url',
        'terms_and_condition',
        'privacy_policy',
        'cookie_policy',
        'open_api_key',
        'open_ai_model',
        'hero_sub_title',
        'hero_title',
        'hero_description',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'default_language',
        'currency_before_amount',
        'send_mail_verification',
        'captcha_site_key',
        'captcha_secret_key',
        'enable_captcha',
        'enabled_captcha_in_login',
        'enabled_captcha_in_register',
        'enabled_captcha_in_quiz',
        'gemini_api_key',
        'gemini_ai_model',
        'ai_type',
        'enable_landing_page',
        'new_participant_mail_to_creator',
        'quiz_complete_mail_to_participant',
        'quiz_complete_mail_to_creator',
    ];

    const APP_LOGO = 'app_logo';

    const FAVICON = 'favicon';

    const LOGIN_PAGE_IMG = 'login_page_img';

    const HERO_SECTION_IMG = 'hero-section-img';

    public static function getForm()
    {
        return [
            TextInput::make('app_name')
                ->label(__('messages.setting.app_name') . ':')
                ->placeholder(__('messages.setting.app_name'))
                ->validationAttribute(__('messages.setting.app_name'))
                ->required(),
            TextInput::make('email')
                ->email()
                ->required()
                ->label(__('messages.user.email') . ':')
                ->placeholder(__('messages.user.email'))
                ->validationAttribute(__('messages.user.email')),
            PhoneInput::make('contact')
                ->defaultCountry('IN')
                ->countryStatePath('prefix_code')
                ->label(__('messages.user.phone_number') . ':')
                ->placeholder(__('messages.user.phone_number'))
                ->validationAttribute(__('messages.user.phone_number'))
                ->rules(function (Get $get) {
                    return [
                        'required',
                        'phone:AUTO,' . strtoupper($get('prefix_code')),
                    ];
                })
                ->validationMessages([
                    'phone' => __('messages.user.phone_number_validation'),
                ])
                ->required(),

            Select::make('default_language')
                ->label(__('messages.setting.default_language') . ':')
                ->options(Language::where('is_active', 1)->get()->pluck('name', 'code'))
                ->placeholder(__('messages.setting.default_language'))
                ->validationAttribute(__('messages.setting.default_language'))
                ->preload()
                ->searchable()
                ->required(),
            Group::make([
                ToggleButtons::make('currency_before_amount')
                    ->label(__('messages.setting.currency_before_amount') . ':')
                    ->options([
                        '1' => __('messages.common.yes'),
                        '0' => __('messages.common.no'),
                    ])
                    ->inline()
                    ->required(),

                ToggleButtons::make('enable_landing_page')
                    ->label(__('messages.setting.enable_landing_page') . ':')
                    ->options([
                        '1' => __('messages.common.yes'),
                        '0' => __('messages.common.no'),
                    ])
                    ->inline()
                    ->required(),

                ToggleButtons::make('send_mail_verification')
                    ->label(__('messages.setting.send_mail_verification') . ':')
                    ->options([
                        '1' => __('messages.common.yes'),
                        '0' => __('messages.common.no'),
                    ])
                    ->inline()
                    ->required(),

                ToggleButtons::make('new_participant_mail_to_creator')
                    ->label(__('messages.setting.new_participant_mail_to_creator') . ':')
                    ->options([
                        '1' => __('messages.common.yes'),
                        '0' => __('messages.common.no'),
                    ])->inline()
                    ->required(),

                ToggleButtons::make('quiz_complete_mail_to_participant')
                    ->label(__('messages.setting.quiz_complete_mail_to_participant') . ':')
                    ->options([
                        '1' => __('messages.common.yes'),
                        '0' => __('messages.common.no'),
                    ])->inline()
                    ->required(),

                ToggleButtons::make('quiz_complete_mail_to_creator')
                    ->label(__('messages.setting.quiz_complete_mail_to_creator') . ':')
                    ->options([
                        '1' => __('messages.common.yes'),
                        '0' => __('messages.common.no'),
                    ])->inline()
                    ->required(),
            ])->columns(4)->columnSpanFull(),
            Group::make([
                SpatieMediaLibraryFileUpload::make('app_logo')
                    ->label(__('messages.setting.app_logo') . ':')
                    ->validationAttribute(__('messages.setting.app_logo'))
                    ->required()
                    ->image()
                    ->disk(config('app.media_disk'))
                    ->collection(self::APP_LOGO),
                SpatieMediaLibraryFileUpload::make('favicon')
                    ->label(__('messages.setting.app_favicon') . ':')
                    ->validationAttribute(__('messages.setting.app_favicon'))
                    ->required()
                    ->image()
                    ->disk(config('app.media_disk'))
                    ->collection(self::FAVICON),
            ])->columns(2)->columnSpanFull(),
        ];
    }

    public static function getSeoSettingsForm(): array
    {
        return [
            TextInput::make('seo_title')
                ->label(__('messages.setting.seo_title') . ':')
                ->placeholder(__('messages.setting.seo_title'))
                ->validationAttribute(__('messages.setting.seo_title'))
                ->required(),
            TextInput::make('seo_keywords')
                ->label(__('messages.setting.seo_keywords') . ':')
                ->placeholder(__('messages.setting.seo_keywords'))
                ->validationAttribute(__('messages.setting.seo_keywords'))
                ->required(),
            Textarea::make('seo_description')
                ->label(__('messages.setting.seo_description') . ':')
                ->placeholder(__('messages.setting.seo_description'))
                ->validationAttribute(__('messages.setting.seo_description'))
                ->required()
                ->columnSpanFull(),
        ];
    }

    public function getFaviconAttribute()
    {
        $url = $this->getFirstMediaUrl(self::FAVICON);

        return ! empty($url) ? $url : asset('images/logo-ai.png');
    }

    public function getAppLogoAttribute()
    {
        $url = $this->getFirstMediaUrl(self::APP_LOGO);

        return ! empty($url) ? $url : asset('images/logo-ai.png');
    }

    public function getLoginPageImgAttribute()
    {
        $url = $this->getFirstMediaUrl(self::LOGIN_PAGE_IMG);

        return ! empty($url) ? $url : asset('images/login-page-bg.jpg');
    }

    public function getHeroSectionImgAttribute()
    {
        $url = $this->getFirstMediaUrl(self::HERO_SECTION_IMG);

        return ! empty($url) ? $url : asset('images/hero-img.png');
    }
}
