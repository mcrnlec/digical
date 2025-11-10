# DigiCal – Product Roadmap

---

## ✅ Phase 1 — Admin Infrastructure (Complete in v1.1)
- Days module (DB + inline editing + sidebar submenu)
- Venues module (DB + parent/child, grouping)
- Common UX (bulk delete, sorting, real-time refresh)
- Per-day admin subpages generated dynamically

---

## ✅ Phase 2 — Speakers Module (Complete in v1.4)
- DB table: speakers (id, title, first_name, last_name, bio)
- Inline CRUD table with bulk delete
- Speaker type: Speaker / Moderator (configurable chips)
- Assign speakers to sessions (ready for v1.6)
- Configuration panel for titles and roles
- Link speakers → Days for planning UI

---

## ✅ Phase 3 — Session Types & Day Plan (Complete in v1.6)
- Session type categories with 20 solid silhouette icons
- DB table: sessions (linked to day + venue + speaker)
- DB junction: session_roles (session_id + role_id + speaker_id)
- 3-step modal for session creation/editing
- Role-based speaker filtering in modal
- Participant badge system with role counts
- Dynamic Day Plan pages per conference day
- Edit/Delete sessions inline

---

## 🚧 Phase 4 — Frontend & Export (v1.7)
- Excel (.xlsx) import templates (Days / Venues / Speakers / Sessions)
- Export full conference structure as XLSX + JSON
- Shortcode generator panel
- Basic agenda display shortcode
- Filterable by day, venue, speaker
- Responsive mobile layout

---

## 📅 Phase 5 — Advanced Scheduling (v1.8)
- Multi-session tracks support
- Overlap detection and conflict warnings
- Drag & resize session blocks on timeline
- Speaker → Session assignment refinement
- Conflict resolution tools
- Per-track filtering and views

---

## 🌐 Phase 6 — Advanced Frontend (v1.9+)
**Modern Breakdance-ready display:**
- Filterable agenda (by day, track, venue, speaker)
- Accordion / timeline responsive layouts
- Session detail modal with speaker cards
- Speaker bio display with photos
- SEO fields per session/speaker
- iCal feed export

---

## 💳 Phase 7 — Commercial Add-ons (v2.0+)
- Tickets & registration integration
- Paid sessions / rooms / VIP access
- Multiple conference instances (CPT events)
- Email notifications to speakers
- Attendee check-in system

---

## 📋 Backlog / Future Considerations
- Multi-language support (currently Croatian)
- Sponsor/partner management
- Social media integration
- Mobile app companion
- Analytics & reporting dashboard
- API endpoints for third-party integrations

---

### Roadmap Status Legend
✅ Done  
🚧 In progress  
📅 Planned (next 2 quarters)  
🕓 Future / not started  

---

### Version Timeline (Estimated)
- v1.6 — Session management & Day Plan ✅ **Released 10 Nov 2025**
- v1.7 — Frontend & Import/Export 📅 (December 2025)
- v1.8 — Advanced scheduling & tracks 📅 (January 2026)
- v1.9+ — Advanced frontend features 📅 (Q1 2026)
- v2.0+ — Commercial features 🕓 (Q2 2026+)

---

© 2025 DIGIT
