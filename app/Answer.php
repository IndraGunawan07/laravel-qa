<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Answer extends Model
{
    use VotableTrait;
    
    protected $fillable = ['body', 'user_id'];
    
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user(Type $var = null)
    {
        return $this->belongsTo(User::class);
    }

    public function getBodyHtmlAttribute()
    {
        return \Parsedown::instance()->text($this->body);
        //return $this->body;
    }

    public static function boot()
    {
        parent::boot();

        static::created(function($answer){
            $answer->question->increment('answers_count');
        });

        static::deleted(function($answer){
            $answer->question->decrement('answers_count');

            // if($question->best_answer_id === $answer->id){
            //     $question->best_answer_id = NULL;
            //     $question->save();
            // }
        });

    }
    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        return $this->isBest() ? 'vote-accepted' : '';
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    public function isBest()
    {
        return $this->id === $this->question->best_answer_id;
    }

}
