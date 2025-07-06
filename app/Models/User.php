<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\UserEmailVerification;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasMedia, HasAvatar, MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles,  InteractsWithMedia, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    const ADMIN_ROLE = 'admin';

    const USER_ROLE = 'user';

    const PROFILE = 'user-profile';

    // public function sendEmailVerificationNotification()
    // {
    //     $this->notify(new UserEmailVerification);
    // }


    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function polls(): HasMany
    {
        return $this->hasMany(Poll::class);
    }

    public function getProfileUrlAttribute()
    {
        return $this->getFirstMediaUrl(self::PROFILE) ?? asset('images/avatar/1.png');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->profile_url;
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public static function getForm()
    {
        return [
            Section::make([
                TextInput::make('name')
                    ->label(__('messages.common.name') . ':')
                    ->placeholder(__('messages.common.name'))
                    ->validationAttribute(__('messages.common.name'))
                    ->required()
                    ->maxLength(250),
                TextInput::make('email')
                    ->label(__('messages.user.email') . ':')
                    ->placeholder(__('messages.user.email'))
                    ->validationAttribute(__('messages.user.email'))
                    ->required()
                    ->email()
                    ->unique(
                        'users',
                        'email',
                        null,
                        false,
                        function ($rule, $record) {
                            if ($record) {
                                $rule->whereNot('id', $record->id);
                            }

                            return $rule;
                        }
                    )
                    ->maxLength(255),
                TextInput::make('password')
                    ->label(__('messages.user.password') . ':')
                    ->placeholder(__('messages.user.password'))
                    ->validationAttribute(__('messages.user.password'))
                    ->password()
                    ->required()
                    ->revealable()
                    ->minLength(8)
                    ->visibleOn('create'),
                TextInput::make('password_confirmation')
                    ->label(__('messages.user.confirm_password') . ':')
                    ->placeholder(__('messages.user.confirm_password'))
                    ->validationAttribute(__('messages.user.confirm_password'))
                    ->password()
                    ->same('password')
                    ->required()
                    ->revealable()
                    ->minLength(8)
                    ->visibleOn('create'),
                SpatieMediaLibraryFileUpload::make('profile')
                    ->label(__('messages.user.profile') . ':')
                    ->placeholder(__('messages.user.profile'))
                    ->validationAttribute(__('messages.user.profile'))
                    ->disk(config('app.media_disk'))
                    ->collection(User::PROFILE)
                    ->image()
                    ->avatar(),
                Select::make('plan')
                    ->visibleOn('create')
                    ->label(__('messages.plan.plan') . ':')
                    ->validationAttribute(__('messages.plan.plan'))
                    ->options(Plan::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->native(false),
            ])->columns(2),

        ];
    }
}
