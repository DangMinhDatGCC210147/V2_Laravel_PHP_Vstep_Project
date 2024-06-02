<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResponses extends Model
{
    use HasFactory;
    protected $fillable = ['skill_id', 'student_id', 'question_id', 'text_response'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
