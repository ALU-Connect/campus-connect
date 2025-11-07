# Campus Connect - Implementation Guide

This guide provides detailed instructions for implementing the remaining features in the Campus Connect platform. The database schema is already in place; you just need to add the business logic and frontend components.

## Feature Implementation Workflow

For each feature, follow this workflow:

1. **Model Relationships:** Update the Eloquent model with relationships and helper methods.
2. **Service Layer:** Create a service class to handle business logic.
3. **Controller:** Create API and/or web controllers.
4. **Routes:** Add routes in `routes/api.php` or `routes/web.php`.
5. **Frontend:** Create Vue.js pages and components in `resources/js/Pages/`.
6. **Admin Panel:** Add Filament resources for admin management.

---

## 1. Campus Marketplace

### Model: `MarketplaceItem`

**File:** `app/Models/MarketplaceItem.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceItem extends Model
{
    protected $fillable = [
        'seller_id', 'title', 'description', 'price',
        'category', 'condition', 'status', 'images'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'images' => 'array',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
```

### Controller: `MarketplaceController`

**Command:** `php artisan make:controller Api/MarketplaceController --api`

**File:** `app/Http/Controllers/Api/MarketplaceController.php`

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceItem;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index()
    {
        $items = MarketplaceItem::with('seller')
            ->available()
            ->latest()
            ->paginate(20);

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string',
            'condition' => 'nullable|string',
        ]);

        $item = auth()->user()->marketplaceItems()->create($validated);

        return response()->json($item, 201);
    }

    public function show(MarketplaceItem $item)
    {
        return response()->json($item->load('seller'));
    }

    public function update(Request $request, MarketplaceItem $item)
    {
        $this->authorize('update', $item);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'status' => 'in:available,sold,reserved',
        ]);

        $item->update($validated);

        return response()->json($item);
    }

    public function destroy(MarketplaceItem $item)
    {
        $this->authorize('delete', $item);

        $item->delete();

        return response()->json(null, 204);
    }
}
```

### Routes

**File:** `routes/api.php`

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('marketplace', MarketplaceController::class);
});
```

### Frontend Page

**File:** `resources/js/Pages/Marketplace/Index.vue`

```vue
<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-6">Campus Marketplace</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div v-for="item in items" :key="item.id" class="bg-white rounded-lg shadow p-4">
        <h3 class="font-semibold text-lg">{{ item.title }}</h3>
        <p class="text-gray-600 text-sm mt-2">{{ item.description }}</p>
        <p class="text-xl font-bold mt-4">${{ item.price }}</p>
        <p class="text-sm text-gray-500">Sold by: {{ item.seller.name }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const items = ref([]);

onMounted(async () => {
  const response = await axios.get('/api/marketplace');
  items.value = response.data.data;
});
</script>
```

---

## 2. Roommate Finder

### Model: `RoommateProfile`

**File:** `app/Models/RoommateProfile.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoommateProfile extends Model
{
    protected $fillable = [
        'user_id', 'bio', 'cleanliness_level', 'noise_tolerance',
        'sleep_schedule', 'study_preference', 'guest_policy',
        'smoking', 'preferences', 'status'
    ];

    protected $casts = [
        'preferences' => 'array',
        'smoking' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matches()
    {
        return $this->hasMany(RoommateMatch::class, 'user1_id');
    }
}
```

### Service: `RoommateMatchingService`

**File:** `app/Services/RoommateMatchingService.php`

```php
<?php

namespace App\Services;

use App\Models\RoommateProfile;
use App\Models\RoommateMatch;

class RoommateMatchingService
{
    public function findMatches($userId)
    {
        $userProfile = RoommateProfile::where('user_id', $userId)->firstOrFail();
        $otherProfiles = RoommateProfile::where('user_id', '!=', $userId)
            ->where('status', 'looking')
            ->get();

        $matches = [];

        foreach ($otherProfiles as $profile) {
            $score = $this->calculateCompatibility($userProfile, $profile);
            
            if ($score > 50) {
                $matches[] = [
                    'profile' => $profile,
                    'score' => $score
                ];
            }
        }

        usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);

        return $matches;
    }

    private function calculateCompatibility($profile1, $profile2)
    {
        $score = 0;

        // Cleanliness compatibility
        $cleanDiff = abs($profile1->cleanliness_level - $profile2->cleanliness_level);
        $score += (10 - $cleanDiff) * 10;

        // Noise tolerance
        $noiseDiff = abs($profile1->noise_tolerance - $profile2->noise_tolerance);
        $score += (10 - $noiseDiff) * 10;

        // Sleep schedule match
        if ($profile1->sleep_schedule === $profile2->sleep_schedule) {
            $score += 20;
        }

        // Smoking preference
        if ($profile1->smoking === $profile2->smoking) {
            $score += 20;
        }

        return min($score, 100);
    }
}
```

---

## 3. Study Group Hub

### Model: `StudyGroup`

**File:** `app/Models/StudyGroup.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyGroup extends Model
{
    protected $fillable = [
        'course_code', 'creator_id', 'title', 'description',
        'meeting_time', 'location', 'max_members', 'status'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'study_group_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function isFull()
    {
        return $this->members()->count() >= $this->max_members;
    }
}
```

---

## 4. Meal Swipe Exchange

### Model: `MealSwipe`

**File:** `app/Models/MealSwipe.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealSwipe extends Model
{
    protected $fillable = [
        'donor_id', 'recipient_id', 'status', 'claimed_at', 'used_at'
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
```

---

## 5. Lost & Found

### Model: `LostFoundItem`

**File:** `app/Models/LostFoundItem.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostFoundItem extends Model
{
    protected $fillable = [
        'reporter_id', 'item_type', 'title', 'description',
        'location', 'image_path', 'status'
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
```

---

## 6. Stories (24-hour ephemeral content)

### Model: `Story`

**File:** `app/Models/Story.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = [
        'user_id', 'content_type', 'content_path', 'caption', 'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function views()
    {
        return $this->hasMany(StoryView::class);
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
```

---

## General Tips

1. **Use Laravel Policies** for authorization (`php artisan make:policy MarketplaceItemPolicy --model=MarketplaceItem`).
2. **Validate Requests** using Form Request classes (`php artisan make:request StoreMarketplaceItemRequest`).
3. **Add API Resources** for consistent JSON responses (`php artisan make:resource MarketplaceItemResource`).
4. **Write Tests** for critical features (`php artisan make:test MarketplaceTest`).
5. **Use Queues** for heavy operations like sending notifications (`php artisan queue:work`).

---

## Next Steps

Choose a feature from the list above and implement it following the workflow. Start with the marketplace or study groups, as they are relatively straightforward and provide immediate value to users.
