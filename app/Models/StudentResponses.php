<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResponses extends Model
{
    use HasFactory;
    protected $fillable = ['test_id', 'skill_id', 'student_id', 'question_id', 'text_response'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
