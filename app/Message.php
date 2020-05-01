<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Message extends Model
{
    protected $fillable = [
        'text',
        'is_from_manager'
    ];

    public function attachFile(UploadedFile $file)
    {
        return $this->attachments()->create([
            'filename' => str_replace('public/', '', $file->store('public'))
        ]);
    }

    public function attachments()
    {
        return $this->hasMany('App\MessageAttachment');
    }
}
