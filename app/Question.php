<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
//use League\CommanMark\CommanMarkConverter;

class Question extends Model
{
    use VotableTrait;

    protected $fillable = ['title', 'body'];

    protected $appends = ['created_date', 'favorites_count', 'is_favorited', 'body_html'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setTitleAttribute($value){
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getUrlAttribute()
    {
        return route("questions.show", $this->slug);
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        if($this->answers_count > 0){
            if($this->best_answer_id ){
                return "answered-accepted";
            }
            else{
                return "answered";
            }
        }
        else{
            return "unanswered";
        }
    }

    public function getBodyHtmlAttribute()
    {
        return clean($this->bodyHtml());
        //return \Parsedown::instance()->text($this->body);
        //return $this->body;
    }

    public function answers()
    {
        // sort answer by count desc in the model 
        return $this->hasMany(Answer::class)->orderBy('votes_count', 'DESC');

    }

    public function acceptBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id;
        $this->save();
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimeStamps();
    }

    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->count() > 0;
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function getExcerptAttribute()
    {
        return $this->excerpt(250);
    }

    public function excerpt($length)
    {
        return Str::of(strip_tags($this->bodyHtml()))->limit($length);
    }

    public function bodyHtml()
    {
        return \Parsedown::instance()->text($this->body);
    }
}