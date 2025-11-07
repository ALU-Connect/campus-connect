# Campus Connect - API Documentation

This document outlines the API endpoints available for the Campus Connect mobile application.

**Base URL:** `https://your-domain.com/api`

## Authentication

Authentication is handled using Laravel Sanctum. To authenticate, the mobile app should:

1. **Login:** Send a `POST` request to `/api/login` with `email` and `password`.
2. **Receive Token:** The API will return a bearer token.
3. **Send Token:** Include the token in the `Authorization` header for all subsequent requests:
   `Authorization: Bearer <token>`

## Endpoints

### Petitions

- **`GET /api/petitions`**
  - **Description:** Get a paginated list of all active petitions.
  - **Response:** `200 OK` with petition data.

- **`POST /api/petitions`**
  - **Description:** Create a new petition.
  - **Body:** `title`, `description`, `category`
  - **Response:** `201 CREATED` with the new petition data.

- **`GET /api/petitions/{id}`**
  - **Description:** Get a single petition by ID.
  - **Response:** `200 OK` with petition data.

- **`POST /api/petitions/{id}/vote`**
  - **Description:** Upvote a petition.
  - **Response:** `200 OK` with updated vote count.

### Threads

- **`GET /api/petitions/{id}/threads`**
  - **Description:** Get all threads for a petition.
  - **Response:** `200 OK` with thread data.

- **`POST /api/petitions/{id}/threads`**
  - **Description:** Create a new thread on a petition.
  - **Body:** `content`, `parent_id` (optional)
  - **Response:** `201 CREATED` with the new thread data.

### Events

- **`GET /api/events`**
  - **Description:** Get a list of upcoming events.
  - **Response:** `200 OK` with event data.

- **`POST /api/events/{id}/rsvp`**
  - **Description:** RSVP to an event.
  - **Response:** `200 OK`.

## Error Responses

- **`401 Unauthorized`**: Missing or invalid authentication token.
- **`404 Not Found`**: The requested resource does not exist.
- **`422 Unprocessable Entity`**: Validation error. The response body will contain details.
- **`500 Internal Server Error`**: A server-side error occurred.
