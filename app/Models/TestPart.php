<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPart extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'test_skill_id',
    ];

    public function studentTest()
    {
        return $this->belongsTo(Student::class);
    }

    public function testSkill()
    {
        return $this->belongsTo(TestSkill::class);
    }
}
