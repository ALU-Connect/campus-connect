<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventRsvp;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function createEvent(User $user, array $data)
    {
        DB::beginTransaction();
        
        try {
            $event = $user->createdEvents()->create([
                'title' => $data['title'],
                'description' => $data['description'],
                'location' => $data['location'],
                'event_date' => $data['event_date'],
                'petition_id' => $data['petition_id'] ?? null,
                'max_attendees' => $data['max_attendees'] ?? null,
                'status' => 'upcoming',
            ]);
            
            DB::commit();
            
            return $event;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function toggleRsvp(User $user, Event $event)
    {
        $existingRsvp = EventRsvp::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();
        
        if ($existingRsvp) {
            // Cancel RSVP
            $existingRsvp->delete();
            return ['rsvped' => false, 'count' => $event->rsvps()->count()];
        } else {
            // Add RSVP
            EventRsvp::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'status' => 'confirmed',
            ]);
            return ['rsvped' => true, 'count' => $event->rsvps()->count()];
        }
    }
    
    public function updateStatus(Event $event, string $status)
    {
        $event->update(['status' => $status]);
        return $event;
    }
}
