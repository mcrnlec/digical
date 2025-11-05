# DigiCal – Database Schema

---

## Overview
DigiCal uses custom WordPress tables for conference management. All tables are prefixed with the WordPress database prefix (e.g., `wp_` in standard installations).

---

## Tables

### `wp_digical_days`
**Purpose:** Store conference day definitions (dates and time slots).

| Column       | Type        | Constraints | Description |
|--------------|------------|-------------|-------------|
| id           | varchar(32) | PRIMARY KEY | Unique day identifier (UUID-like) |
| date         | varchar(8)  | NOT NULL | Date in format DDMMYYYY |
| start_time   | varchar(5)  | NOT NULL | Start time in format HH:MM |
| end_time     | varchar(5)  | NOT NULL | End time in format HH:MM |
| created_at   | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |
| updated_at   | timestamp   | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Timestamp of last update |

**Indexes:**
- PRIMARY KEY (id)
- INDEX by_date (date)

**Notes:**
- Time format accepts flexible input (8 or 8:00) but is normalized to HH:MM
- Date locale display (e.g., 01.10.2025) is handled in PHP layer

---

### `wp_digical_venues`
**Purpose:** Store venue information with parent-child hierarchy.

| Column       | Type        | Constraints | Description |
|-------------|-------------|-------------|-------------|
| id          | varchar(32) | PRIMARY KEY | Unique venue identifier |
| type        | varchar(10) | NOT NULL | 'primary' or 'sub-venue' |
| name        | varchar(255)| NOT NULL | Venue name |
| address     | text        | | Full address (street, city, etc.) |
| parent_id   | varchar(32) | FOREIGN KEY | Reference to parent venue (NULL if primary) |
| created_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |
| updated_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Timestamp of last update |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX by_type_name (type, name)
- INDEX by_parent_id (parent_id)

**Foreign Keys (Logical):**
- parent_id → venues.id (handled in PHP for max compatibility)

**Hierarchy Rules:**
- Primary venue: type = 'primary', parent_id = NULL
- Sub-venue: type = 'sub-venue', parent_id points to primary
- **Address propagation:** When primary address is updated, all sub-venue addresses are updated automatically
- Reverse propagation does NOT occur: editing a sub-venue address does not affect primary or siblings

---

### `wp_digical_speakers`
**Purpose:** Store speaker information and roles.

| Column       | Type        | Constraints | Description |
|-------------|-------------|-------------|-------------|
| id          | varchar(32) | PRIMARY KEY | Unique speaker identifier |
| title       | varchar(50) | | Title prefix (Prof., Dr., Ing., etc.) |
| first_name  | varchar(100)| NOT NULL | Speaker first name(s) |
| last_name   | varchar(100)| NOT NULL | Speaker last name(s) |
| bio         | longtext    | | Biography or short description |
| email       | varchar(255)| UNIQUE | Speaker email (for future notifications) |
| created_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |
| updated_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Timestamp of last update |

**Indexes:**
- PRIMARY KEY (id)
- INDEX by_name (last_name, first_name)
- UNIQUE INDEX email (email)

**Notes:**
- Speaker roles are stored in junction table (`wp_digical_speakers_roles`)
- Email field reserved for future notifications module

---

### `wp_digical_titles`
**Purpose:** Store configurable speaker title options (Prof., Dr., etc.).

| Column       | Type        | Constraints | Description |
|-------------|-------------|-------------|-------------|
| id          | int         | PRIMARY KEY AUTO_INCREMENT | Unique title ID |
| title       | varchar(50) | UNIQUE NOT NULL | Title text (e.g., "Prof.") |
| created_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX title (title)

**Default Values:**
- Prof.
- Dr.
- Ing.
- M.Sc.
- Ph.D.

---

### `wp_digical_roles`
**Purpose:** Store speaker role types (Speaker, Moderator, etc.).

| Column       | Type        | Constraints | Description |
|-------------|-------------|-------------|-------------|
| id          | int         | PRIMARY KEY AUTO_INCREMENT | Unique role ID |
| role_name   | varchar(50) | UNIQUE NOT NULL | Role identifier (e.g., "speaker") |
| display_name| varchar(100)| NOT NULL | Display label (e.g., "Speaker") |
| color_code  | varchar(7)  | | Hex color for badge styling (e.g., #2271b1) |
| created_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX role_name (role_name)

**Default Values:**
- speaker / Speaker / #2271b1
- moderator / Moderator / #6f42c1

---

### `wp_digical_speakers_roles` (Junction Table)
**Purpose:** Link speakers to roles (many-to-many).

| Column       | Type        | Constraints | Description |
|-------------|-------------|-------------|-------------|
| id          | int         | PRIMARY KEY AUTO_INCREMENT | Junction record ID |
| speaker_id  | varchar(32) | FOREIGN KEY | Reference to speaker |
| role_id     | int         | FOREIGN KEY | Reference to role |
| created_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX speaker_role (speaker_id, role_id)
- INDEX by_speaker (speaker_id)
- INDEX by_role (role_id)

**Foreign Keys (Logical):**
- speaker_id → speakers.id
- role_id → roles.id

**Notes:**
- A speaker can have multiple roles (e.g., both "Speaker" and "Moderator")
- Unique constraint prevents duplicate assignments

---

## Planned Tables (Future Versions)

### `wp_digical_sessions` (v1.6+)
```
id (varchar 32 PK)
day_id (varchar 32 FK → days.id)
venue_id (varchar 32 FK → venues.id)
title (varchar 255)
description (longtext)
start_time (varchar 5)
end_time (varchar 5)
track_id (int FK → tracks.id) [optional]
session_type (varchar 50) [session/workshop/keynote]
speakers (JSON array of speaker IDs)
created_at (timestamp)
updated_at (timestamp)
```

### `wp_digical_tracks` (v1.6+)
```
id (int PK AUTO_INCREMENT)
day_id (varchar 32 FK → days.id)
track_name (varchar 100)
track_color (varchar 7)
sort_order (int)
created_at (timestamp)
```

---

## Data Integrity Rules

1. **Venue Hierarchy:** Sub-venues must always have a valid parent_id referencing a primary venue.
2. **Address Propagation:** Changes to primary venue address must cascade to all sub-venues (implemented in PHP).
3. **Speaker Uniqueness:** Speaker email is unique (reserved for future use).
4. **Role Assignment:** A speaker can have multiple roles through the junction table.
5. **Cascading Deletes:** Deleting a primary venue should orphan sub-venues (currently not deleting sub-venues—future refinement).

---

## Migration & Activation

- Tables are created automatically on plugin activation via `register_activation_hook()`
- Schema updates are applied via `digical_update_*_table_schema()` functions called on plugin load
- Existing data is preserved during updates
- Table prefix is dynamically determined by WordPress `$wpdb->prefix`

---

## Performance Notes

- All PKs and FKs use varchar(32) for UUID compatibility
- Indexed by name, date, parent, and type for fast queries
- Junction table prevents N+N queries for speaker roles
- Future sessions table will index day/venue for fast lookups

---

© 2025 DIGIT
