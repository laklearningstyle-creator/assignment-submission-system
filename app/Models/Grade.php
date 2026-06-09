<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $grade_id
 * @property int $submission_id
 * @property numeric $marks_obtained
 * @property string $grade
 * @property int $graded_by
 * @property \Illuminate\Support\Carbon $graded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $grader
 * @property-read \App\Models\Submission $submission
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereGradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereGradedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereGradedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereMarksObtained($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Grade extends Model
{
    protected $table = 'grades';
    protected $primaryKey = 'grade_id';

    protected $fillable = [
        'submission_id',
        'marks_obtained',
        'grade',
        'graded_by',
        'graded_at'
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'graded_at' => 'datetime'
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id', 'submission_id');
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by', 'user_id');
    }

    // Alias for grader - allows using 'gradedBy' in eager loading
    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by', 'user_id');
    }

    public static function calculateGrade($marksObtained, $totalMarks)
    {
        $percentage = ($marksObtained / $totalMarks) * 100;

        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 60) return 'C';
        if ($percentage >= 50) return 'D';
        return 'F';
    }
}
