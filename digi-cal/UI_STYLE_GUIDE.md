# DigiCal – Admin UI Style Guide

---

## Global Look & Feel
- Primary color: **#2271b1** (WordPress admin blue)
- Secondary color: **#135e96** (darker blue for hover states)
- Accent color: **#dc3545** (red for delete/danger actions)
- Buttons:
  - Primary: Blue background (#2271b1) / white text / hover scale 1.05
  - Delete: Red background (#dc3545) / white text / hover opacity 0.8
  - Secondary: Light grey background / grey text
- Forms: compact height (32-36px), light grey border, 4–8px padding & spacing

---

## Tables
- Responsive, auto-fit; wrap before 80% width
- Sticky header (white text / blue background #2271b1)
- First column: checkbox selection for bulk actions
- Hover row highlight (light grey background)
- Primary rows: **bold text** (for primary venues, main entries)
- Child rows: slight indent (4px left margin for sub-entries)
- Column widths: auto-fit to content, min 100px

### Table Actions
- Edit column: click cell to activate inline edit mode
- Delete button (per-row): red background, hover scales
- Bulk delete: appears only when 2+ rows selected
- Cancel Selection: clears bulk selection state

---

## Sections & Collapsibles
- Collapsible containers (accordion-style):
  - Start **expanded by default** (v1.4+)
  - Header click toggles expand/collapse
  - Chevron icon indicates state (▼ expanded, ▶ collapsed)
- **Expand All** / **Collapse All** button globally above sections
- Section header counts: true total row count (not only visible rows)
- Smooth CSS transitions (0.3s ease)

---

## Forms & Inputs
- Label styling: bold, dark grey (#333)
- Input fields: light grey border (#ccc), 4px padding, focus ring (#2271b1)
- Dropdowns/Select: same styling as inputs
- Textarea: min-height 100px, same border/padding as inputs
- Error messages: red text (#dc3545)
- Success messages: green text (#28a745)

---

## Buttons
- Primary action button: blue (#2271b1), white text, 8px padding, 4px border-radius
- Secondary button: light grey background, grey text
- Delete button: red (#dc3545), white text
- Button hover: scale(1.05) + opacity change
- Button active/pressed: darkened color

---

## Icons & Labels
- **Configure button:** small green badge, white text, icon-based
- **Role chips** (speakers): badge styling with background color
  - Speaker: blue chip
  - Moderator: purple chip
- **Checkboxes:** native WordPress style
- **Chevrons/Toggles:** using CSS or Unicode (▼ / ▶)

---

## Colors Reference
```
Primary Blue:      #2271b1
Dark Blue:         #135e96
Light Grey:        #f9f9f9 (backgrounds)
Border Grey:       #ccc
Text Dark:         #333
Success Green:     #28a745
Danger Red:        #dc3545
```

---

## Typography
- Font: inherit WordPress system font stack (Segoe UI, Roboto, etc.)
- Base size: 14px
- Headings:
  - H1: 32px, bold
  - H2: 24px, bold
  - H3: 18px, bold
- Form labels: 13px, bold, #333
- Helper text: 12px, grey, italic

---

## Accessibility
- Keyboard navigation enabled (Tab, Enter, Escape)
- ARIA labels on toggle buttons (`aria-expanded`, `aria-label`)
- Focus states: visible ring (#2271b1, 2px)
- Color contrast: WCAG AA compliant (4.5:1 minimum)
- Screen reader friendly: semantic HTML

---

## Responsive Breakpoints
- Desktop (default): full table layout
- Tablet (< 1024px): minor column adjustments
- Mobile (< 768px): stack forms vertically, table scrolls horizontally (future feature)

---

## Loading States
- AJAX operations: disable buttons, show spinner (or change opacity)
- Success feedback: green checkmark + "Saved" message
- Error feedback: red alert box with error details

---

## Modal-Free Philosophy
- **Inline editing** for all data operations:
  - Click a cell → activates edit mode (input field)
  - Enter key to save
  - Escape key to cancel
- No modal popups for CRUD operations (keeps UX lightweight)
- Confirmation dialogs: only for destructive actions (bulk delete)

---

© 2025 DIGIT
