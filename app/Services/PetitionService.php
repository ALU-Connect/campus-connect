<?php

namespace App\Services;

use App\Models\Petition;
use App\Models\PetitionVote;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PetitionService
{
    public function createPetition(User $user, array $data)
    {
        DB::beginTransaction();
        
        try {
            // Handle image upload if present
            if (isset($data['image']) && $data['image']) {
                $data['image_path'] = $data['image']->store('petitions', 'public');
            }
            
            // Create petition
            $petition = $user->petitions()->create([
                'title' => $data['title'],
                'description' => $data['description'],
                'category' => $data['category'] ?? 'general',
                'visibility' => $data['visibility'] ?? 'public',
                'status' => 'active',
                'threshold' => $data['threshold'] ?? 100,
                'image_path' => $data['image_path'] ?? null,
            ]);
            
            DB::commit();
            
            return $petition;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function toggleVote(User $user, Petition $petition)
    {
        $existingVote = PetitionVote::where('user_id', $user->id)
            ->where('petition_id', $petition->id)
            ->first();
        
        if ($existingVote) {
            // Remove vote
            $existingVote->delete();
            $petition->decrementVotes();
            return ['voted' => false, 'count' => $petition->upvote_count];
        } else {
            // Add vote
            PetitionVote::create([
                'user_id' => $user->id,
                'petition_id' => $petition->id,
            ]);
            $petition->incrementVotes();
            return ['voted' => true, 'count' => $petition->upvote_count];
        }
    }
    
    public function addComment(User $user, Petition $petition, array $data)
    {
        return $petition->threads()->create([
            'user_id' => $user->id,
            'parent_id' => $data['parent_id'] ?? null,
            'content' => $data['content'],
            'thread_type' => $data['thread_type'] ?? 'discussion',
        ]);
    }
    
    public function updateStatus(Petition $petition, string $status)
    {
        $petition->update(['status' => $status]);
        return $petition;
    }
}
