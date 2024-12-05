<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Approval\Traits\ApprovesChanges;
use Carbon\Carbon;
use Exception;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;
use Throwable;

class User extends Authenticatable implements FilamentUser, HasMedia
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, InteractsWithMedia, SoftDeletes;
    use ApprovesChanges;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'roles',
    ];

    protected $appends = ['role_list', 'profile_picture_url'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime:Y-m-d H:i:s',
            'phone_verified_at' => 'datetime:Y-m-d H:i:s',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'password' => 'hashed',
        ];
    }
    /**
     * @inheritDoc
     */
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->email == 'puertoprincesapearls@gmail.com';
    }

    // public function registerMediaConversions(?Media $media = null): void
    // {
    //     $this
    //         ->addMediaConversion('preview')
    //         ->fit(Fit::Contain, 500, 500)
    //         ->nonQueued();
    // }

    public function getRoleListAttribute()
    {
        return $this->roles->map(function ($role) {
            return $role->name;
        });
    }

    public function getProfilePictureUrlAttribute()
    {
        try {

            $url = $this->getFirstMediaUrl('image');
            if (empty($url) or is_null($url)) {
                throw new Exception("No profile picture yet");
            }
            return $url;
        } catch (Throwable $t) {
        }
        return 'https://ui-avatars.com/api/?background=random&name=' . urlencode($this->name);
    }


    protected function authorizedToApprove(\Approval\Models\Modification $mod): bool
    {
        return true;
    }

    protected function authorizedToDisapprove(\Approval\Models\Modification $mod): bool
    {
        return true;
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }
}