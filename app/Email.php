<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_queue';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['to', 'subject', 'message'];

   /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}