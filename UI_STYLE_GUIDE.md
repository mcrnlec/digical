# DigiCal – Admin UI Style Guide (v1.6)

---

## Global Look & Feel
- Primary color: **#2271b1** (WordPress admin blue)
- Secondary color: **#135e96** (darker blue for hover states)
- Accent color: **#dc3545** (red for delete/danger actions)
- Success color: **#28a745** (green for save/confirm actions)
- Buttons:
  - Primary: Blue background (#2271b1) / white text / hover scale 1.05
  - Delete: Red background (#dc3545) / white text / hover opacity 0.8
  - Save: Green background (#28a745) / white text / hover scale 1.05
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

## Modal Interface (NEW v1.6)

### Structure
- Modal container with dark overlay (opacity 0.6)
- Header: title + close button (X icon, top right)
- Body: form sections with proper spacing
- Footer: action buttons (Save, Cancel)
- Max width: 600px; centered on screen
- Smooth fade-in/out transitions (0.2s ease)

### 3-Step Modal (Sessions)

#### Step 1: Basic Info
- **Title field:** text input, min 5 chars, required
- **Description field:** textarea, min-height 100px, optional
- **Session Type selection:** checkbox grid
  - Display session type icons with labels
  - Multi-select allowed
  - Icons: 40x40px, centered
  - Hover: slight scale (1.05) + shadow
  - Selected: border highlight (#2271b1, 2px)
- **Navigation:** Next button (blue) enabled after title filled

#### Step 2: Times & Location
- **Start Time:** time input (HH:MM format) or text field
- **End Time:** time input, must be > start_time
- **Primary Venue dropdown:** required, blue select styling
  - Placeholder: "Select a venue..."
  - Labels: bold for primary venues, normal for sub-venues
  - Nested display optional but aligned left
- **Sub-Venue dropdown:** secondary, dependent on primary selection
  - Shows only sub-venues of selected primary
  - Auto-populated child list
  - Optional (user can leave empty)
- **Navigation:** Previous & Next buttons; Next disabled if times invalid

#### Step 3: Speaker & Roles
- **Role selection:** checkboxes (one per role)
  - Display: "Speaker", "Moderator", etc.
  - Colors from `wp_digical_roles` color_code field
  - Multi-select allowed
  - Updates available speakers dynamically
- **Speaker dropdowns:** appears for each selected role
  - Label: role name
  - Options: speakers with that role (filtered from DB)
  - Multi-select or repeating dropdowns (UX decision: single per role or multiple)
  - Placeholder: "Select a speaker..."
- **Participants display:** below role/speaker section
  - Pill-shaped badges
  - Format: "3 Speakers" (count + role name)
  - Colors: from role color_code
  - Remove button (X) on each badge (triggers speaker removal)
  - **Min-height: 200px** to prevent screen jumping when roles added/removed
- **Navigation:** Previous, Save buttons

### Modal Interactions
- Escape key closes modal (confirm if unsaved changes)
- Click overlay closes modal (confirm if unsaved changes)
- Close button (X) closes modal (confirm if unsaved changes)
- Form validation on Save (show error messages in red)
- On successful save: modal closes, table refreshes, success message displays

### Modal Error Handling
- Inline error messages below fields (red text, #dc3545)
- Required field validation (show red border + error text)
- Time validation: end_time must be > start_time
- Speaker validation: at least one speaker required if roles selected
- Network errors: display alert in modal footer

---

## Session Types UI (NEW v1.6)

### Masonry Grid Layout
- 5 columns on desktop (responsive to 3 on tablet, 1 on mobile)
- Gap: 20px between cards
- Card size: 120x150px
- CSS Grid with auto layout

### Session Type Cards
- **Unchecked state:**
  - Icon centered, 40x40px
  - Label below icon (12px, centered)
  - Border: light grey (#ccc), 1px
  - Background: white
  - Cursor: pointer

- **Hovered state:**
  - Scale: 1.05
  - Box-shadow: 0 2px 8px rgba(0,0,0,0.1)
  - Transition: 0.2s ease

- **Checked state:**
  - Border: blue (#2271b1), 2px
  - Checkbox: visible in top-left corner (blue fill)
  - Background: light blue (#f0f5ff)

- **Icon colors:**
  - Keynote: gradient from #FF6B6B to #FF8E8E (red)
  - Workshop: gradient from #4ECDC4 to #6EE7E7 (teal)
  - Panel: gradient from #FFE66D to #FFF39E (yellow)
  - Breakout: gradient from #95E1D3 to #B8E4D8 (mint)
  - Social: gradient from #A8D8EA to #C9E4F4 (blue)

### Edit/Save Mode
- **Default:** Browse mode (checkbox selection + bulk delete button)
- **Edit button:** small blue button above grid
  - Triggers edit mode
  - Button text changes to "Save"
  - Color changes to green (#28a745)
- **Save mode:**
  - Each card becomes editable (title/color fields appear inline)
  - Icon selector dropdown (choose from 5 per category)
  - Color picker for gradient
  - Delete button per card (red X)
  - Cancel button (revert changes)
- **No confirmation dialogs:** deletes happen immediately with optional undo

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
  - Nested options (sub-venues) indented with 20px left padding
  - Bold font for primary options
  - Regular font for child options
- Textarea: min-height 100px, same border/padding as inputs
- Error messages: red text (#dc3545), below field
- Success messages: green text (#28a745)
- Required field indicator: **red asterisk** after label

---

## Buttons
- Primary action button: blue (#2271b1), white text, 8px padding, 4px border-radius
- Secondary button: light grey background, grey text
- Delete button: red (#dc3545), white text
- Save button: green (#28a745), white text
- Button hover: scale(1.05) + opacity change or color shift
- Button active/pressed: darkened color
- Button disabled: grey background, opacity 0.5, cursor not-allowed
- **Modal buttons:**
  - Previous: secondary (grey)
  - Next/Save: primary (blue/green)
  - Cancel: secondary or close icon

---

## Icons & Labels (NEW v1.6)
- **Session Type Icons:** 40x40px solid silhouette conference scenes
  - 5 categories × 4 icons each = 20 total unique icons
  - NOT Material Design; custom SVG with gradients
  - Examples: podium speakers, workshop hands, panel discussion, breakout circles, networking figures
- **Participant Badges:** pill-shaped with role color
  - Format: "3 Speakers" (count + role name)
  - Padding: 6px 12px
  - Font-size: 12px
  - Colors: from `wp_digical_roles.color_code`
  - Remove (X) icon right-aligned, cursor pointer
- **Role Chips (speakers):** badge styling with background color
  - Speaker: blue chip (#2271b1)
  - Moderator: purple chip (#6f42c1)
- **Checkboxes:** native WordPress style
- **Chevrons/Toggles:** using CSS or Unicode (▼ / ▶)

---

## Colors Reference
```
Primary Blue:      #2271b1
Dark Blue:         #135e96
Light Blue:        #f0f5ff (for selection)
Light Grey:        #f9f9f9 (backgrounds)
Border Grey:       #ccc
Text Dark:         #333
Success Green:     #28a745
Danger Red:        #dc3545
Keynote Red:       #FF6B6B → #FF8E8E
Workshop Teal:     #4ECDC4 → #6EE7E7
Panel Yellow:      #FFE66D → #FFE39E
Breakout Mint:     #95E1D3 → #B8E4D8
Social Blue:       #A8D8EA → #C9E4F4
```

---

## Typography
- Font: inherit WordPress system font stack (Segoe UI, Roboto, etc.)
- Base size: 14px
- Headings:
  - H1: 32px, bold
  - H2: 24px, bold
  - H3: 18px, bold
  - H4: 16px, bold
- Form labels: 13px, bold, #333
- Helper text: 12px, grey, italic
- Badge/pill text: 12px, medium weight

---

## Accessibility
- Keyboard navigation enabled (Tab, Enter, Escape)
- ARIA labels on toggle buttons (`aria-expanded`, `aria-label`)
- ARIA labels on modals (`role="dialog"`, `aria-modal="true"`, `aria-labelledby`)
- Focus states: visible ring (#2271b1, 2px)
- Color contrast: WCAG AA compliant (4.5:1 minimum)
- Screen reader friendly: semantic HTML
- Modal focus trap: focus stays within modal when open
- Required field indicators: aria-required="true" on inputs

---

## Responsive Breakpoints
- Desktop (default): full layout, modal max-width 600px
- Tablet (< 1024px): minor column adjustments, modal 90vw
- Mobile (< 768px): stack forms vertically, table scrolls horizontally, modal 100vw

---

## Loading States
- AJAX operations: disable buttons, show spinner (or change opacity)
- Success feedback: green checkmark + "Saved" message (2s auto-dismiss)
- Error feedback: red alert box with error details
- Modal loading: greyed overlay with spinner during save

---

## Modal-Free Philosophy (Updated v1.6)
- **Inline editing** for all data operations:
  - Click a cell → activates edit mode (input field)
  - Enter key to save
  - Escape key to cancel
- **Modal popups ONLY for:**
  - Adding new sessions (3-step guided form)
  - Editing sessions (pre-populated 3-step form)
  - Complex multi-step workflows
- **No confirmation dialogs** for destructive actions in edit mode
- Inline delete buttons (red X) for quick removal
- Success messages confirm action after completion

---

## Screen Jump Prevention (NEW v1.6)
- Participant badge container: **min-height: 200px** to prevent jumping
- Role dropdown section: sticky positioning when scrolling
- Modal body: fixed max-height with overflow-y auto
- Form sections: consistent padding/margins

---

© 2025 DIGIT
