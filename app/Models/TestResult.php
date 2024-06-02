<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class TestResult extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = ['student_id', 'test_id', 'score', 'correct_answers', 'incorrect_answers'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'test_name'
            ]
        ];
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
