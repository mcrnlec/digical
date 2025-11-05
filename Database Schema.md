# DigiCal – Database Schema Overview

---

## Tables

### `wp_digical_days`
| Column       | Type        | Description |
|--------------|------------|-------------|
| id           | varchar(32) PK | Day UID |
| date         | varchar(8) | Format: DDMMYYYY |
| start_time   | varchar(5) | HH:MM |
| end_time     | varchar(5) | HH:MM |
| day_name     | generated (UI) | Croatian day name |

Indexes:
- PK(id)
- by_date

---

### `wp_digical_venues`
| Column       | Type        | Description |
|-------------|-------------|-------------|
| id          | varchar(32) PK | Unique venue |
| type        | varchar(10) | 'primary' / 'sub-venue' |
| name        | varchar(255) | Venue name |
| address     | text        | Address line |
| parent_id   | varchar(32) FK -> venues.id | Assigned primary venue |

Indexes:
- PK(id)
- by_type_name(type, name)
- by_parent(parent_id)

Rules:
- Primary can exist alone
- Sub-venues require parent
- On parent update: propagate address

---

### `wp_digical_speakers` (*planned v1.2*)
| Column       | Type        | Description |
|-------------|-------------|-------------|
| id          | varchar(32) PK |
| title       | varchar(50) | Prof., Dr., etc. |
| first_name  | varchar(50) |
| last_name   | varchar(50) |
| roles       | set enum | speaker/moderator |
| bio         | text | optional |

---

### `wp_digical_sessions` (*planned v1.3*)
| Column       | Type        | Description |
|-------------|-------------|-------------|
| id          | varchar(32) PK |
| day_id      | varchar(32) FK -> days.id |
| venue_id    | varchar(32) FK -> venues.id |
| title       | varchar(255) |
| start_time  | varchar(5) |
| end_time    | varchar(5) |
| speakers    | JSON | array of IDs |
| type        | varchar(20) | session/workshop/etc. |

---

## Foreign Key Notes
Foreign key constraints will be logical (handled in PHP & AJAX)  
for max compatibility across hosting environments.

---

© 2025 DIGIT
