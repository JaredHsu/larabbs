<?php

namespace App\Observers;

use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function created(Topic $topic)
    {
        $topic->excerpt = make_excerpt($topic->body);
        $topic->save();
    }

    public function updating(Topic $topic)
    {
        //
    }
}