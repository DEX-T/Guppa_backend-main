<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\SoftDelete;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Middleware\TwoFA\TwoFa;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_no',
        'country',
        'email',
        'gender',
        'role',
        'password',
        'two_factor_code',
        'two_factor_expires_at',
         'is_2fa_enabled',
         'facebook_id',
         'google_id',
         'chatId',
         'age_group'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
        'chatId'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_expires_at',
        ];
    }

    //relationships

    /**
     * Get the rate associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rate(): HasOne
    {
        return $this->hasOne(Rating::class);
    }

    /**
     * Get the testimony associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function testimony(): HasOne
    {
        return $this->hasOne(TestimonialCard::class);
    }

    /**
     * Get the twofa associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function twofa(): HasOne
    {
        return $this->hasOne(TwoFaTracker::class);
    }

    /**
     * Get the on_boarded associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function on_boarded(): HasOne
    {
        return $this->hasOne(FreelancerOnBoarding::class);
    }

    /**
     * Get all of the jobs for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(GuppaJob::class);
    }


    /**
     * Get all of the tickets for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Get the bid associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bid(): HasOne
    {
        return $this->hasOne(Bid::class);
    }

    /**
     * Get all of the portfolios for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(FreelancerPortfolio::class);
    }

    /**
     * Get the setting associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function setting(): HasOne
    {
        return $this->hasOne(Setting::class);
    }

    /**
     * Get all of the invites for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class);
    }

    public function receivedRatings()
    {
        return $this->hasMany(FreelancerRating::class, 'freelancer_id');
    }

    public function givenRatings()
    {
        return $this->hasMany(FreelancerRating::class, 'rated_by');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(MyJob::class, 'user_id');
    }

    /**
     * Get all of the notifications for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the kyc associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function kyc(): HasOne
    {
        return $this->hasOne(Verification::class);
    }

    public function audits(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    protected  static  function boot()
    {
        parent::boot();
        if(Auth::check() && Auth::user()->role == "SUPERUSER"){
            static::created(function ($user){
                AuditLog::create([
                    'action' => 'create_user',
                    'user_id' => Auth::id(),
                    'details' => json_encode($user->toArray()),
                ]);
            });

            static::updated(function ($user) {
                AuditLog::create([
                    'action' => 'update_user',
                    'user_id' => Auth::id() ?? $user->id,
                    'details' => json_encode($user->getChanges()),
                ]);
            });

            static::deleted(function ($user) {
                AuditLog::create([
                    'action' => 'delete_user',
                    'user_id' => Auth::id(),
                    'details' => json_encode($user->toArray()),
                ]);
            });
        }
    }

    public function scopeClients($query){
        return $query->where('role', 'CLIENT');
    }

    public function scopeFreelancers($query){
        return $query->where('role', 'FREELANCER');
    }

    public function scopeActive($query){
        return $query->where('status', 'active');
    }

    /**
     * Get all of the pending_payments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pending_payments(): HasMany
    {
        return $this->hasMany(PendingApprovedJobPayment::class);
    }
}
