<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $user_id
 * @property int $role_id
 * @property string $full_name
 * @property string $email
 * @property string|null $phone
 * @property string $username
 * @property string $password
 * @property string $status
 * @property string|null $avatar
 * @property string|null $avatar_color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assignment> $createdAssignments
 * @property-read int|null $created_assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $createdCourses
 * @property-read int|null $created_courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Enrollment> $enrollments
 * @property-read int|null $enrollments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Feedback> $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Grade> $grades
 * @property-read int|null $grades_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Role $role
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Submission> $submissions
 * @property-read int|null $submissions_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'full_name',
        'email',
        'phone',
        'username',
        'password',
        'status',
        'avatar',
        'avatar_color',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    // Check if user is admin - using role_id directly (more efficient, no extra query)
    public function isAdmin()
    {
        return $this->role_id == 1;
    }

    // Check if user is teacher
    public function isTeacher()
    {
        return $this->role_id == 2;
    }

    // Check if user is student
    public function isStudent()
    {
        return $this->role_id == 3;
    }

    // Alternative: Get role name directly
    public function getRoleNameAttribute()
    {
        return $this->role ? $this->role->role_name : 'student';
    }

    // ==================== AVATAR METHODS ====================

    /**
     * Get avatar URL attribute
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return Storage::disk('public')->url($this->avatar);
        }
        return null;
    }

    /**
     * Get avatar color based on user role or custom color
     */
    public function getAvatarColorAttribute()
    {
        if ($this->attributes['avatar_color'] ?? false) {
            return $this->attributes['avatar_color'];
        }

        // Role-based colors
        $roleColors = [
            1 => '#ef4444', // Admin - Red
            2 => '#10b981', // Teacher - Green
            3 => '#3b82f6', // Student - Blue
        ];

        if (isset($roleColors[$this->role_id])) {
            return $roleColors[$this->role_id];
        }

        // Fallback random colors based on user_id
        $colors = ['#667eea', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'];
        return $colors[$this->user_id % count($colors)];
    }

    /**
     * Get user initials from full name
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', trim($this->full_name));
        $initials = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        return substr($initials, 0, 2);
    }

    /**
     * Get role-based avatar border class
     */
    public function getAvatarBorderClassAttribute()
    {
        return match($this->role_id) {
            1 => 'avatar-border-admin',
            2 => 'avatar-border-teacher',
            3 => 'avatar-border-student',
            default => 'avatar-border-default',
        };
    }

    /**
     * Get role icon for avatar
     */
    public function getRoleIconAttribute()
    {
        return match($this->role_id) {
            1 => '👑',
            2 => '👨‍🏫',
            3 => '🎓',
            default => '👤'
        };
    }

    // ==================== END AVATAR METHODS ====================

    // Student enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id', 'user_id');
    }

    // Student submissions
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id', 'user_id');
    }

    // Courses created by this user (teacher/admin)
    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'created_by', 'user_id');
    }

    // Assignments created by this user (teacher/admin)
    public function createdAssignments()
    {
        return $this->hasMany(Assignment::class, 'created_by', 'user_id');
    }

    // Notifications for this user
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'user_id');
    }

    // Grades received (for students)
    public function grades()
    {
        return $this->hasManyThrough(Grade::class, Submission::class, 'student_id', 'submission_id', 'user_id', 'submission_id');
    }

    // Feedback received (for students)
    public function feedbacks()
    {
        return $this->hasManyThrough(Feedback::class, Submission::class, 'student_id', 'submission_id', 'user_id', 'submission_id');
    }

    // Courses created by this user
    public function courses()
    {
        return $this->hasMany(Course::class, 'created_by', 'user_id');
    }

    // Taught courses (alias for createdCourses)
    public function taughtCourses()
    {
        return $this->hasMany(Course::class, 'created_by', 'user_id');
    }

    // Get full name with role badge
    public function getDisplayNameAttribute()
    {
        return $this->getRoleIconAttribute() . ' ' . $this->full_name;
    }

    // Check if user has a specific permission based on role
    public function canManageCourses()
    {
        return $this->isAdmin() || $this->isTeacher();
    }

    public function canManageUsers()
    {
        return $this->isAdmin();
    }

    public function canGradeSubmissions()
    {
        return $this->isAdmin() || $this->isTeacher();
    }
}
