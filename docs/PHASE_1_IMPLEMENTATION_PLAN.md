# Phase 1 Implementation Plan: Core Engagement Features

This document outlines the detailed plan for implementing the core engagement features of the Campus Connect platform.

## 1. Petition System

**Goal:** A fully functional system for creating, viewing, voting on, and discussing petitions.

### Backend

1.  **Models:**
    *   `Petition`: Update with relationships (`creator`, `votes`, `threads`).
    *   `PetitionVote`: Implement logic for upvoting/downvoting.
    *   `PetitionThread`: Implement nested comments with `parent_id`.
2.  **Service Layer (`app/Services/PetitionService.php`):**
    *   `createPetition(array $data)`: Handle petition creation and image uploads.
    *   `toggleVote(User $user, Petition $petition)`: Add or remove a vote for a petition.
    *   `addComment(User $user, Petition $petition, array $data)`: Add a new comment/thread.
3.  **Controllers:**
    *   `Web/PetitionController.php`: Handle web requests for creating, viewing, and listing petitions.
    *   `Api/PetitionController.php`: Handle API requests for mobile clients.
4.  **Routes (`routes/web.php`, `routes/api.php`):**
    *   `GET /petitions`, `POST /petitions`
    *   `GET /petitions/{petition}`
    *   `POST /petitions/{petition}/vote`
    *   `POST /petitions/{petition}/threads`

### Frontend (`resources/js/Pages/Petitions/`)

1.  **`Index.vue`**: List all active petitions with a search/filter component.
2.  **`Show.vue`**: Display a single petition, its description, vote count, and the comment threads.
3.  **`Create.vue`**: A form for creating a new petition with title, description, category, and image upload.

## 2. Event Management

**Goal:** A system for creating and managing campus events, which can be linked to successful petitions.

### Backend

1.  **Model (`Event`):** Update with relationships (`creator`, `petition`, `rsvps`).
2.  **Service Layer (`app/Services/EventService.php`):**
    *   `createEvent(array $data)`: Handle event creation.
    *   `addRsvp(User $user, Event $event)`: Add a user to the RSVP list.
3.  **Controllers:**
    *   `Web/EventController.php`: Handle web requests for events.
    *   `Api/EventController.php`: Handle API requests for events.

### Frontend (`resources/js/Pages/Events/`)

1.  **`Index.vue`**: A calendar or list view of upcoming events.
2.  **`Show.vue`**: Display event details, location, time, and RSVP list.

## 3. User Profile Management

**Goal:** Allow users to view and manage their profiles.

### Backend

1.  **Model (`User`):** Ensure relationships to petitions, events, etc., are defined.
2.  **Controller (`Web/ProfileController.php`):**
    *   `show(User $user)`: Display a user's public profile.
    *   `edit()`: Show the form for editing the authenticated user's profile.
    *   `update(Request $request)`: Handle profile updates (name, bio, profile picture).

### Frontend (`resources/js/Pages/Profile/`)

1.  **`Show.vue`**: Display a user's profile, including their created petitions and events they're attending.
2.  **`Edit.vue`**: A form for updating profile information.

## 4. Admin Panel (Filament)

**Goal:** A comprehensive admin dashboard for moderation and management.

1.  **Install Filament:** `composer require filament/filament:^3.2 -W`
2.  **Create Admin User:** `php artisan make:filament-user`
3.  **Create Resources:**
    *   `PetitionResource`: Manage petitions (view, edit, delete, change status).
    *   `UserResource`: Manage users (view, edit, delete, assign roles).
    *   `EventResource`: Manage events.
4.  **Dashboard Widgets:**
    *   Stats overview widget (total users, petitions, events).
    *   Chart widget for new users/petitions over time.

## 5. Task Breakdown

1.  **Backend First:** Implement all models, services, and controllers for petitions, events, and profiles.
2.  **API Routes:** Define all API routes and test them with an API client like Postman.
3.  **Frontend Implementation:** Build the Vue.js components and pages for each feature.
4.  **Filament Integration:** Build the admin panel resources and dashboard.
5.  **Testing:** Write feature tests to ensure all components work together correctly.
6.  **Push to GitHub:** Commit and push the completed Phase 1 features.
