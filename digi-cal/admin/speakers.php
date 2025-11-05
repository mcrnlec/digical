<?php
if (!defined('ABSPATH')) exit;
?>
<style>
/* ===== Speakers Management - Modern Professional Styling ===== */

.digical-section { 
  margin-bottom: 20px; 
  border: 1px solid #e0e6ed;
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.digical-section-header { 
  background: linear-gradient(135deg, #2271b1 0%, #1a5a8e 100%);
  color: #fff; 
  padding: 16px 20px; 
  cursor: pointer; 
  font-weight: 700;
  display: flex;
  justify-content: space-between;
  align-items: center;
  user-select: none;
  transition: all 0.3s ease;
}

.digical-section-header:hover { 
  background: linear-gradient(135deg, #1a5a8e 0%, #144266 100%);
  box-shadow: 0 4px 12px rgba(34, 113, 177, 0.2);
}

.digical-section-header .toggle-icon { 
  font-size: 18px; 
  transition: transform 0.3s ease;
}

.digical-section-header.collapsed .toggle-icon { 
  transform: rotate(-90deg);
}

.digical-section-content { 
  padding: 24px; 
  display: block;
  animation: slideDown 0.3s ease;
}

.digical-section-content.hidden { 
  display: none;
}

@keyframes slideDown {
  from { opacity: 0; max-height: 0; }
  to { opacity: 1; max-height: 1000px; }
}

.config-subsection { 
  margin-bottom: 25px; 
  padding: 24px; 
  background: linear-gradient(135deg, #f8fafc 0%, #f0f4f8 100%);
  border: 1px solid #e0e6ed;
  border-radius: 12px;
}

.config-subsection h3 { 
  margin-top: 0; 
  color: #2271b1;
  font-size: 16px; 
  font-weight: 700;
  padding-bottom: 12px;
  border-bottom: 3px solid #2271b1;
}

.config-subsection-desc { 
  color: #7f8c8d; 
  font-size: 13px; 
  margin-bottom: 10px;
  font-weight: 500;
}

.config-entry-form { 
  background: linear-gradient(135deg, #f8fafc 0%, #f0f4f8 100%);
  padding: 20px; 
  border: 2px solid #e0e6ed;
  border-radius: 10px;
  margin-bottom: 15px;
  display: none;
}

.config-entry-form.show { 
  display: block;
  animation: slideDown 0.3s ease;
}

.config-textarea { 
  width: 100%; 
  min-height: 150px; 
  padding: 12px; 
  border: 2px solid #d1d8e0;
  border-radius: 8px;
  font-family: monospace; 
  font-size: 13px; 
  margin-bottom: 10px;
  box-sizing: border-box;
  transition: all 0.3s ease;
}

.config-textarea:focus {
  outline: none;
  border-color: #2271b1;
  box-shadow: 0 0 0 3px rgba(34, 113, 177, 0.1);
}

.config-controls { 
  display: flex; 
  gap: 12px;
}

.config-controls button { 
  background: linear-gradient(135deg, #2271b1 0%, #1a5a8e 100%);
  color: #fff; 
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  cursor: pointer; 
  font-size: 13px;
  font-weight: 700;
  transition: all 0.3s ease;
  box-shadow: 0 2px 6px rgba(34, 113, 177, 0.15);
}

.config-controls button:hover { 
  box-shadow: 0 4px 12px rgba(34, 113, 177, 0.25);
  transform: translateY(-2px);
}

.config-controls .cancel-btn { 
  background: linear-gradient(135deg, #bdc3c7 0%, #95a5a6 100%);
}

.config-controls .cancel-btn:hover {
  box-shadow: 0 4px 12px rgba(157, 163, 171, 0.25);
}

.add-btn { 
  background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
  color: #fff; 
  border: none;
  border-radius: 8px;
  padding: 12px 24px;
  cursor: pointer; 
  font-size: 14px;
  font-weight: 700;
  margin-bottom: 15px;
  transition: all 0.3s ease;
  box-shadow: 0 2px 6px rgba(39, 174, 96, 0.15);
}

.add-btn:hover { 
  box-shadow: 0 4px 12px rgba(39, 174, 96, 0.25);
  transform: translateY(-2px);
}

.empty-state { 
  padding: 20px; 
  text-align: center; 
  color: #7f8c8d;
  font-style: italic;
  font-size: 14px;
}

.config-masonry { 
  display: grid; 
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
  gap: 16px; 
  margin-top: 15px;
}

.config-card { 
  background: white; 
  border: 1px solid #e0e6ed;
  border-radius: 10px;
  padding: 16px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
}

.config-card:hover { 
  box-shadow: 0 6px 16px rgba(34, 113, 177, 0.12);
  transform: translateY(-4px);
  border-color: #2271b1;
}

.config-card-name { 
  font-size: 15px; 
  font-weight: 700; 
  color: #2271b1;
  margin: 0 0 12px 0; 
  word-break: break-word;
  flex-grow: 1;
  line-height: 1.4;
}

.config-card-actions { 
  display: flex; 
  gap: 8px; 
  margin-top: 12px;
}

.config-card-actions button { 
  flex: 1; 
  padding: 8px 12px; 
  border: none;
  border-radius: 6px;
  cursor: pointer; 
  font-size: 12px; 
  font-weight: 700;
  transition: all 0.3s ease;
}

.config-card-edit { 
  background: linear-gradient(135deg, #2271b1 0%, #1a5a8e 100%);
  color: #fff;
  box-shadow: 0 2px 6px rgba(34, 113, 177, 0.15);
}

.config-card-edit:hover { 
  box-shadow: 0 4px 12px rgba(34, 113, 177, 0.25);
  transform: translateY(-2px);
}

.config-card-delete { 
  background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
  color: #fff;
  box-shadow: 0 2px 6px rgba(231, 76, 60, 0.15);
}

.config-card-delete:hover { 
  box-shadow: 0 4px 12px rgba(231, 76, 60, 0.25);
  transform: translateY(-2px);
}

.config-card-edit-mode { 
  background: linear-gradient(135deg, #f8fafc 0%, #f0f4f8 100%);
  padding: 12px;
  border-radius: 8px;
}

.edit-field { 
  width: 100%; 
  padding: 10px 12px;
  border: 2px solid #d1d8e0;
  border-radius: 8px;
  margin-bottom: 10px; 
  font-family: inherit;
  font-size: 14px;
  box-sizing: border-box;
  transition: all 0.3s ease;
}

.edit-field:focus {
  outline: none;
  border-color: #2271b1;
  box-shadow: 0 0 0 3px rgba(34, 113, 177, 0.1);
}

.success-message { 
  background: #d4edda; 
  color: #155724; 
  padding: 12px; 
  border-radius: 8px; 
  margin-bottom: 12px;
  display: none;
  border-left: 4px solid #28a745;
}

.digical-controls { 
  display: flex; 
  gap: 12px; 
  align-items: center; 
  margin: 0 0 20px 0; 
  flex-wrap: wrap;
}

.digical-controls .wide { 
  min-width: 200px; 
}

.digical-btn { 
  background: linear-gradient(135deg, #2271b1 0%, #1a5a8e 100%);
  color: #fff; 
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  cursor: pointer; 
  font-size: 14px;
  font-weight: 700;
  transition: all 0.3s ease;
  box-shadow: 0 2px 6px rgba(34, 113, 177, 0.15);
}

.digical-btn:hover { 
  box-shadow: 0 4px 12px rgba(34, 113, 177, 0.25);
  transform: translateY(-2px);
}

.digical-btn:disabled { 
  opacity: 0.5; 
  cursor: default;
  transform: none;
}

.digical-btn-red { 
  background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
  color: #fff;
  box-shadow: 0 2px 6px rgba(231, 76, 60, 0.15);
}

.digical-btn-red:hover {
  box-shadow: 0 4px 12px rgba(231, 76, 60, 0.25);
}

.digical-btn-green { 
  background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
  color: #fff;
  border: none;
  box-shadow: 0 2px 6px rgba(39, 174, 96, 0.15);
}

.digical-btn-green:hover { 
  background: linear-gradient(135deg, #229954 0%, #1e8449 100%);
  box-shadow: 0 4px 12px rgba(39, 174, 96, 0.25);
  transform: translateY(-2px);
}

.bulkbar { 
  display: none; 
  margin: 20px 0;
  padding: 16px;
  background: linear-gradient(135deg, #f8fafc 0%, #f0f4f8 100%);
  border: 1px solid #e0e6ed;
  border-radius: 10px;
}

.bulkbar.show { 
  display: flex;
  gap: 12px;
  align-items: center;
}

.table-wrap { 
  margin-top: 20px; 
  overflow-x: auto;
}

.digical-table { 
  width: 100%; 
  border-collapse: collapse; 
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  border: 1px solid #e0e6ed;
}

.digical-table th { 
  background: linear-gradient(135deg, #2271b1 0%, #1a5a8e 100%);
  color: #fff; 
  text-align: left; 
  padding: 14px 16px;
  font-weight: 700;
  text-transform: uppercase;
  font-size: 12px;
  letter-spacing: 0.5px;
  border: none;
}

.digical-table td { 
  padding: 14px 16px; 
  border-bottom: 1px solid #ecf0f1;
  font-weight: 600;
  color: #2c3e50;
  font-size: 14px;
}

.digical-table tbody tr:last-child td {
  border-bottom: none;
}

.digical-table tbody tr:hover td {
  background: linear-gradient(135deg, #f8fafc 0%, #f0f4f8 100%);
}

.digical-actions .digical-btn { 
  margin-right: 8px;
}

.inline-input, .inline-select, .inline-textarea { 
  width: 100%; 
  box-sizing: border-box; 
  font-family: inherit;
  padding: 8px 12px;
  border: 2px solid #d1d8e0;
  border-radius: 6px;
  font-size: 14px;
}

.inline-textarea { 
  min-height: 80px; 
  resize: vertical;
}

.speaker-card { 
  background: white; 
  border: 1px solid #e0e6ed;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  min-height: 100px;
}
.speaker-card:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.15); }

.digical-view-toggle {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.digical-view-btn {
    background: linear-gradient(135deg, #ecf0f1 0%, #d5dbdb 100%);
    color: #2c3e50;
    border: 2px solid #bdc3c7;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.digical-view-btn:hover {
    border-color: #2271b1;
    background: linear-gradient(135deg, #ecf0f1 0%, #d5dbdb 100%);
}

.digical-view-btn.active {
    background: linear-gradient(135deg, #2271b1 0%, #1a5a8e 100%);
    color: #fff;
    border-color: #2271b1;
    box-shadow: 0 2px 8px rgba(34, 113, 177, 0.2);
}
</style>

<!-- CONFIGURATION SECTION (Collapsible) -->
<div class="digical-section">
  <div class="digical-section-header" id="config_header">
    <span>⚙️ Configuration</span>
    <span class="toggle-icon">▶</span>
  </div>
  <div class="digical-section-content" id="config_content">
    
    <!-- TITLES SUBSECTION -->
    <div class="config-subsection">
      <h3>Titles (Dr., Prof., etc.)</h3>
      
      <button id="add_titles_btn" class="add-btn">+ Add Titles</button>
      
      <div class="config-entry-form" id="titles_entry_form">
        <div class="success-message" id="titles_success">Titles saved successfully!</div>
        <p class="config-subsection-desc">Enter one title per line. Click "Save Titles" to add all at once.</p>
        <textarea id="titles_textarea" class="config-textarea" placeholder="Dr.&#10;Prof.&#10;M.D.&#10;Ph.D.&#10;Eng."></textarea>
        <div class="config-controls">
          <button id="save_titles_btn" class="digical-btn digical-btn-green">Save Titles</button>
          <button id="cancel_titles_btn" class="digical-btn cancel-btn">Cancel</button>
        </div>
      </div>

      <div class="config-masonry" id="titles_masonry">
        <div class="empty-state">No titles defined yet</div>
      </div>
    </div>

    <!-- ROLES SUBSECTION -->
    <div class="config-subsection">
      <h3>Roles (Speaker, Moderator, etc.)</h3>
      
      <button id="add_roles_btn" class="add-btn">+ Add Roles</button>
      
      <div class="config-entry-form" id="roles_entry_form">
        <div class="success-message" id="roles_success">Roles saved successfully!</div>
        <p class="config-subsection-desc">Enter one role per line. Click "Save Roles" to add all at once.</p>
        <textarea id="roles_textarea" class="config-textarea" placeholder="Speaker&#10;Moderator&#10;Workshop Lead&#10;Keynote&#10;Panelist"></textarea>
        <div class="config-controls">
          <button id="save_roles_btn" class="digical-btn digical-btn-green">Save Roles</button>
          <button id="cancel_roles_btn" class="digical-btn cancel-btn">Cancel</button>
        </div>
      </div>

      <div class="config-masonry" id="roles_masonry">
        <div class="empty-state">No roles defined yet</div>
      </div>
    </div>

  </div>
</div>

<!-- SPEAKERS SECTION -->
<div class="digical-section">
  <div class="digical-section-header" id="speakers_header">
    <span>👥 Speakers</span>
    <span class="toggle-icon">▶</span>
  </div>
  <div class="digical-section-content" id="speakers_content">
    
    <!-- Entry Form Collapsible Header -->
    <div style="background: #f5f5f5; padding: 12px; border-radius: 4px; margin-bottom: 15px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; border: 1px solid #ddd;" id="entry_form_header">
      <div style="font-weight: 600; color: #0b6ea6; font-size: 13px;">➕ Add New Speaker</div>
      <span style="font-size: 14px; color: #666; font-weight: bold;" id="entry_form_toggle">▼</span>
    </div>

    <!-- Entry Form Content (Collapsed by Default) -->
    <div id="entry_form_content" style="display: none; margin-bottom: 20px;">
    
    <div class="digical-controls" style="flex-direction: column; align-items: flex-start;">
      <div style="width: 100%; display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 15px;">
        <select id="speaker_title" style="padding: 6px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; font-size: 14px; min-width: 200px;" tabindex="1">
          <option value="">Select Title (optional)</option>
        </select>
        <input type="text" id="speaker_first_name" placeholder="First name *" required style="padding: 6px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; flex: 1; min-width: 200px;" tabindex="2">
        <input type="text" id="speaker_last_name" placeholder="Last name *" required style="padding: 6px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; flex: 1; min-width: 200px;" tabindex="3">
      </div>

      <div style="width: 100%; padding: 15px; background: #f5f5f5; border-radius: 4px; box-sizing: border-box;">
        <div style="font-weight: 600; margin-bottom: 10px; color: #0b6ea6;">Roles (select at least one) *</div>
        <div id="roles_checkboxes" style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
          <!-- Roles checkboxes will load here -->
        </div>
        <div id="roles_error" style="color: #dc3232; font-size: 12px; margin-bottom: 12px; display: none;">Please select at least one role</div>

        <div style="border-top: 1px solid #ddd; padding-top: 15px; margin-top: 15px;">
          <div style="font-weight: 600; margin-bottom: 10px; color: #0b6ea6;">Speaker Photo (optional)</div>
          <div style="display: flex; gap: 12px; align-items: flex-start;">
            <div style="display: flex; gap: 12px; align-items: flex-start;">
              <input type="file" id="speaker_photo" accept="image/*" style="display: none;">
              <button type="button" id="browse_photo_btn" class="digical-btn" style="padding: 8px 24px; border: 0; white-space: nowrap;">Browse Photo</button>
              <div id="photo_preview" style="display: none;">
                <img id="photo_preview_img" src="" alt="Preview" style="max-width: 100px; max-height: 100px; border-radius: 4px; border: 1px solid #ddd;">
              </div>
            </div>
            <button id="add_speaker" class="digical-btn digical-btn-green" style="padding: 8px 24px; border: 0;">Save Speaker</button>
          </div>
          <input type="hidden" id="speaker_photo_id" value="">
        </div>
      </div>
    </div>

    </div><!-- End entry_form_content -->

    <div style="margin-top: 20px;">
      <!-- Filter Header Collapsible -->
      <div style="background: #f5f5f5; padding: 12px; border-radius: 4px; margin-bottom: 15px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; border: 1px solid #ddd;" id="filter_header">
        <div style="font-weight: 600; color: #0b6ea6; font-size: 13px;">🔍 Filter Speakers</div>
        <span style="font-size: 14px; color: #666; font-weight: bold;" id="filter_toggle">▶</span>
      </div>

      <!-- Filter Content (Collapsed by Default) -->
      <div id="filter_content" style="display: none; margin-bottom: 15px; padding: 12px; background: #f8f9fa; border-radius: 4px; border: 1px solid #e0e0e0; box-sizing: border-box;">
        <div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: center;">
          <input type="text" id="filter_first_name" placeholder="Filter by first name..." style="padding: 6px 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; width: calc(20% - 10px); min-width: 120px; box-sizing: border-box;">
          <input type="text" id="filter_last_name" placeholder="Filter by last name..." style="padding: 6px 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; width: calc(20% - 10px); min-width: 120px; box-sizing: border-box;">
          
          <div style="position: relative; display: inline-block; z-index: 100;">
            <button id="filter_roles_btn" class="digical-btn" style="padding: 6px 12px; border: 0; font-size: 13px; white-space: nowrap;">Select Roles ▼</button>
            <div id="filter_roles_dropdown" style="display: none; position: absolute; top: 100%; left: 0; background: #fff; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 4px 8px rgba(0,0,0,0.15); z-index: 1001; min-width: 200px; margin-top: 4px; padding: 8px;">
              <div id="filter_roles_checkboxes" style="display: flex; flex-direction: column; gap: 6px; max-height: 250px; overflow-y: auto;">
                <!-- Role checkboxes will load here -->
              </div>
            </div>
          </div>
          
          <button id="filter_reset" class="digical-btn" style="padding: 6px 12px; border: 0; font-size: 13px; white-space: nowrap;">Reset Filters</button>
        </div>
      </div>

      <!-- View Toggle Buttons -->
      <div class="digical-view-toggle" style="margin-bottom: 15px;">
        <button id="view-table-btn" class="digical-view-btn">📊 Table View</button>
        <button id="view-masonry-btn" class="digical-view-btn active">📋 Masonry View</button>
      </div>

      <div id="speakers_masonry" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
        <div style="grid-column: 1 / -1; text-align: center; color: #999; padding: 40px;">No speakers yet</div>
      </div>
      <div id="speakers_table_container" style="display: none; margin-top: 20px;">
        <div class="table-wrap">
          <table class="digical-table" id="speakers_table">
            <thead>
              <tr>
                <th style="width: 40px;"><input type="checkbox" id="table-select-all"></th>
                <th>Title</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Roles</th>
                <th>Bio</th>
                <th>Photo</th>
                <th style="width: 150px;">Actions</th>
              </tr>
            </thead>
            <tbody id="speakers_table_body">
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
(function($){
  const AURL = window.ajaxurl || '/wp-admin/admin-ajax.php';
  
  // ============ COLLAPSIBLE SECTIONS ============
  function initCollapsible() {
    $('.digical-section-header').on('click', function() {
      const $header = $(this);
      const $content = $header.next('.digical-section-content');
      
      $header.toggleClass('collapsed');
      $content.toggleClass('hidden');
      
      const sectionId = $header.attr('id');
      const isCollapsed = $header.hasClass('collapsed');
      localStorage.setItem('digical_' + sectionId, isCollapsed ? 'collapsed' : 'expanded');
    });

    $('.digical-section-header').each(function() {
      const $header = $(this);
      const sectionId = $header.attr('id');
      const savedState = localStorage.getItem('digical_' + sectionId);
      
      if (savedState === 'collapsed') {
        $header.addClass('collapsed');
        $header.next('.digical-section-content').addClass('hidden');
      }
    });

    // Toggle entry form collapse
    $('#entry_form_header').on('click', function() {
      $('#entry_form_content').slideToggle(300);
      $('#entry_form_toggle').text(function(i, text) {
        return text === '▼' ? '▶' : '▼';
      });
    });

    // Toggle filter collapse
    $('#filter_header').on('click', function() {
      $('#filter_content').slideToggle(300);
      $('#filter_toggle').text(function(i, text) {
        return text === '▼' ? '▶' : '▼';
      });
    });
  }

  // ============ CONFIGURATION - TITLES ============
  let TITLES = [];

  function loadTitles() {
    $.post(AURL, { action: 'digical_get_titles' }, function(resp) {
      if (!resp || !resp.success) {
        console.error('Failed to load titles:', resp?.data?.message);
        return;
      }
      TITLES = resp.data.titles || [];
      TITLES.sort((a, b) => a.title.localeCompare(b.title));
      renderTitlesMasonry();
      updateTitleDropdown();
    });
  }

  function updateTitleDropdown() {
    const $select = $('#speaker_title');
    const currentValue = $select.val();
    $select.find('option:not(:first)').remove();
    
    TITLES.forEach(title => {
      $select.append($('<option>').attr('value', title.title).text(title.title));
    });
    
    $select.val(currentValue);
  }

  function updateRolesCheckboxes() {
    const $container = $('#roles_checkboxes');
    $container.empty();
    
    if (ROLES.length === 0) {
      $container.html('<div style="color: #999; font-size: 13px;">No roles defined yet. Add roles in Configuration section.</div>');
      return;
    }
    
    let tabindex = 4;
    ROLES.forEach(role => {
      const checkboxHtml = $(`
        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
          <input type="checkbox" class="speaker-role-chk" value="${escapeHtml(role.role)}" style="cursor: pointer;" tabindex="${tabindex}">
          <span>${escapeHtml(role.role)}</span>
        </label>
      `);
      $container.append(checkboxHtml);
      tabindex++;
    });
    
    // Set browse button tab index to be after last checkbox
    const numRoles = ROLES.length;
    $('#browse_photo_btn').attr('tabindex', 4 + numRoles);
    
    // Update Save button tab index to be last
    $('#add_speaker').attr('tabindex', 4 + numRoles + 1);
  }

  function renderTitlesMasonry() {
    const $masonry = $('#titles_masonry');
    $masonry.empty();

    if (TITLES.length === 0) {
      $masonry.html('<div class="empty-state">No titles defined yet</div>');
      return;
    }

    TITLES.forEach(title => {
      const card = $(`
        <div class="config-card" data-id="${title.id}">
          <div class="config-card-name">${escapeHtml(title.title)}</div>
          <div class="config-card-actions">
            <button class="config-card-edit btn-edit">Edit</button>
            <button class="config-card-delete btn-delete">Delete</button>
          </div>
        </div>
      `);

      card.find('.btn-delete').on('click', function() {
        if (!confirm('Delete this title?')) return;
        deleteTitle(title.id);
      });

      card.find('.btn-edit').on('click', function() {
        enterEditMode(card, title, 'title');
      });

      $masonry.append(card);
    });
  }

  $('#add_titles_btn').on('click', function() {
    $('#titles_entry_form').toggleClass('show');
    if ($('#titles_entry_form').hasClass('show')) {
      $('#titles_textarea').focus();
    }
  });

  $('#cancel_titles_btn').on('click', function() {
    $('#titles_entry_form').removeClass('show');
    $('#titles_textarea').val('');
  });

  $('#save_titles_btn').on('click', function() {
    const text = $('#titles_textarea').val().trim();
    if (text === '') {
      alert('Please enter at least one title');
      return;
    }

    const titles = text.split('\n').map(t => t.trim()).filter(t => t !== '');
    let completed = 0;

    if (titles.length === 0) {
      alert('Please enter at least one title');
      return;
    }

    titles.forEach(title => {
      $.post(AURL, { action: 'digical_add_title', title: title }, function(resp) {
        completed++;
        if (completed === titles.length) {
          $('#titles_textarea').val('');
          $('#titles_success').show().delay(3000).fadeOut();
          $('#titles_entry_form').removeClass('show');
          loadTitles();
        }
      });
    });
  });

  function deleteTitle(id) {
    $.post(AURL, { action: 'digical_delete_title', id: id }, function(resp) {
      if (!resp || !resp.success) {
        alert(resp?.data?.message || 'Failed to delete title');
        return;
      }
      TITLES = resp.data.titles || [];
      TITLES.sort((a, b) => a.title.localeCompare(b.title));
      renderTitlesMasonry();
      updateTitleDropdown();
    });
  }

  // ============ CONFIGURATION - ROLES ============
  let ROLES = [];

  function loadRoles() {
    $.post(AURL, { action: 'digical_get_roles' }, function(resp) {
      if (!resp || !resp.success) {
        console.error('Failed to load roles:', resp?.data?.message);
        return;
      }
      ROLES = resp.data.roles || [];
      ROLES.sort((a, b) => a.role.localeCompare(b.role));
      renderRolesMasonry();
      updateRolesCheckboxes();
      updateRoleFilter();
    });
  }

  function renderRolesMasonry() {
    const $masonry = $('#roles_masonry');
    $masonry.empty();

    if (ROLES.length === 0) {
      $masonry.html('<div class="empty-state">No roles defined yet</div>');
      return;
    }

    ROLES.forEach(role => {
      const card = $(`
        <div class="config-card" data-id="${role.id}">
          <div class="config-card-name">${escapeHtml(role.role)}</div>
          <div class="config-card-actions">
            <button class="config-card-edit btn-edit">Edit</button>
            <button class="config-card-delete btn-delete">Delete</button>
          </div>
        </div>
      `);

      card.find('.btn-delete').on('click', function() {
        if (!confirm('Delete this role?')) return;
        deleteRole(role.id);
      });

      card.find('.btn-edit').on('click', function() {
        enterEditMode(card, role, 'role');
      });

      $masonry.append(card);
    });
  }

  function enterEditMode($card, item, type) {
    $card.addClass('config-card-edit-mode');

    const fieldName = type === 'title' ? 'title' : 'role';
    const value = item[fieldName];

    const editField = $(`<input type="text" class="edit-field" value="${escapeHtml(value)}">`);

    $card.find('.config-card-name').replaceWith(editField);
    $card.find('.config-card-actions').html(`
      <button class="config-card-edit btn-save" style="background: #1a8f5d;">Save</button>
      <button class="config-card-delete btn-cancel" style="background: #999;">Cancel</button>
    `);

    $card.find('.btn-cancel').on('click', function() {
      if (type === 'title') {
        renderTitlesMasonry();
      } else {
        renderRolesMasonry();
      }
    });

    $card.find('.btn-save').on('click', function() {
      const newValue = editField.val().trim();
      if (newValue === '') {
        alert('Please enter a value');
        return;
      }

      const action = type === 'title' ? 'digical_edit_title' : 'digical_edit_role';
      const data = { action: action, id: item.id };
      data[fieldName] = newValue;

      $.post(AURL, data, function(resp) {
        if (!resp || !resp.success) {
          alert(resp?.data?.message || 'Update failed');
          return;
        }
        if (type === 'title') {
          TITLES = resp.data.titles || [];
          TITLES.sort((a, b) => a.title.localeCompare(b.title));
          renderTitlesMasonry();
          updateTitleDropdown();
        } else {
          ROLES = resp.data.roles || [];
          ROLES.sort((a, b) => a.role.localeCompare(b.role));
          renderRolesMasonry();
          updateRolesCheckboxes();
        }
      });
    });
  }

  $('#add_roles_btn').on('click', function() {
    $('#roles_entry_form').toggleClass('show');
    if ($('#roles_entry_form').hasClass('show')) {
      $('#roles_textarea').focus();
    }
  });

  $('#cancel_roles_btn').on('click', function() {
    $('#roles_entry_form').removeClass('show');
    $('#roles_textarea').val('');
  });

  $('#save_roles_btn').on('click', function() {
    const text = $('#roles_textarea').val().trim();
    if (text === '') {
      alert('Please enter at least one role');
      return;
    }

    const roles = text.split('\n').map(r => r.trim()).filter(r => r !== '');
    let completed = 0;

    if (roles.length === 0) {
      alert('Please enter at least one role');
      return;
    }

    roles.forEach(role => {
      $.post(AURL, { action: 'digical_add_role', role: role }, function(resp) {
        completed++;
        if (completed === roles.length) {
          $('#roles_textarea').val('');
          $('#roles_success').show().delay(3000).fadeOut();
          $('#roles_entry_form').removeClass('show');
          loadRoles();
        }
      });
    });
  });

  function deleteRole(id) {
    $.post(AURL, { action: 'digical_delete_role', id: id }, function(resp) {
      if (!resp || !resp.success) {
        alert(resp?.data?.message || 'Failed to delete role');
        return;
      }
      ROLES = resp.data.roles || [];
      ROLES.sort((a, b) => a.role.localeCompare(b.role));
      renderRolesMasonry();
      updateRolesCheckboxes();
    });
  }

  // ============ PHOTO UPLOAD ============
  let SPEAKER_PHOTO_ID = '';

  $('#browse_photo_btn').on('click', function() {
    $('#speaker_photo').click();
  });

  $('#speaker_photo').on('change', function() {
    const file = this.files[0];
    if (!file) return;

    // Show preview immediately
    const reader = new FileReader();
    reader.onload = function(e) {
      $('#photo_preview_img').attr('src', e.target.result);
      $('#photo_preview').show();
    };
    reader.readAsDataURL(file);
  });

  function uploadPhotoIfSelected() {
    return new Promise(function(resolve) {
      const fileInput = $('#speaker_photo')[0];
      
      // If no file selected, resolve immediately
      if (!fileInput.files || !fileInput.files[0]) {
        resolve(true);
        return;
      }

      const file = fileInput.files[0];
      const formData = new FormData();
      formData.append('action', 'digical_upload_speaker_photo');
      formData.append('file', file);

      $.ajax({
        url: AURL,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(resp) {
          if (!resp.success) {
            alert(resp.data?.message || 'Photo upload failed');
            resolve(false);
            return;
          }

          SPEAKER_PHOTO_ID = resp.data.id;
          $('#speaker_photo_id').val(resp.data.id);
          resolve(true);
        },
        error: function() {
          alert('Photo upload error');
          resolve(false);
        }
      });
    });
  }

  // ============ SPEAKERS VIEW ============
  let CURRENT_VIEW = 'masonry'; // masonry or table
  let SPEAKERS = [];
  let FILTERED_SPEAKERS = []; // For filtered display

  function getFilteredSpeakers() {
    const firstName = $('#filter_first_name').val().toLowerCase().trim();
    const lastName = $('#filter_last_name').val().toLowerCase().trim();
    
    // Get selected roles
    const selectedRoles = [];
    $('#filter_roles_checkboxes input[type="checkbox"]:checked').each(function() {
      selectedRoles.push($(this).val());
    });

    return SPEAKERS.filter(speaker => {
      // Check first name
      if (firstName && !speaker.first_name.toLowerCase().includes(firstName)) {
        return false;
      }

      // Check last name
      if (lastName && !speaker.last_name.toLowerCase().includes(lastName)) {
        return false;
      }

      // Check roles - speaker must have at least one of the selected roles
      if (selectedRoles.length > 0) {
        if (!speaker.roles || !Array.isArray(speaker.roles)) {
          return false;
        }
        // Check if speaker has any of the selected roles
        const hasRole = selectedRoles.some(role => speaker.roles.includes(role));
        if (!hasRole) {
          return false;
        }
      }

      return true;
    });
  }

  function applyFilter() {
    FILTERED_SPEAKERS = getFilteredSpeakers();
    if (CURRENT_VIEW === 'masonry') {
      renderMasonry(FILTERED_SPEAKERS);
    } else {
      renderTable(FILTERED_SPEAKERS);
    }
  }

  // Filter event listeners
  $('#filter_first_name, #filter_last_name').on('change keyup', function() {
    applyFilter();
  });

  // Toggle roles dropdown
  $('#filter_roles_btn').on('click', function(e) {
    e.preventDefault();
    $('#filter_roles_dropdown').toggle();
  });

  // Close dropdown when clicking outside
  $(document).on('click', function(e) {
    if (!$(e.target).closest('#filter_roles_btn, #filter_roles_dropdown').length) {
      $('#filter_roles_dropdown').hide();
    }
  });

  $('#filter_roles_checkboxes').on('change', 'input[type="checkbox"]', function() {
    applyFilter();
  });

  $('#filter_reset').on('click', function() {
    $('#filter_first_name').val('');
    $('#filter_last_name').val('');
    $('#filter_roles_checkboxes input[type="checkbox"]').prop('checked', false);
    $('#filter_roles_dropdown').hide();
    FILTERED_SPEAKERS = SPEAKERS;
    applyFilter();
  });

  // Update role filter checkboxes when roles change
  function updateRoleFilter() {
    const $container = $('#filter_roles_checkboxes');
    $container.empty();
    
    if (ROLES.length === 0) {
      $container.html('<div style="color: #999; font-size: 12px; padding: 4px;">No roles defined</div>');
      return;
    }
    
    ROLES.forEach(role => {
      const checkbox = $(`
        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px; font-size: 12px;">
          <input type="checkbox" value="${escapeHtml(role.role)}" style="cursor: pointer; width: 14px; height: 14px;">
          <span>${escapeHtml(role.role)}</span>
        </label>
      `);
      $container.append(checkbox);
    });
  }

  function loadSpeakers() {
    $.post(AURL, { action:'digical_get_speakers' }, function(resp){
      if (!resp || !resp.success) { 
        alert(resp?.data?.message || 'Load failed'); 
        return; 
      }
      SPEAKERS = resp.data.speakers || [];
      if (CURRENT_VIEW === 'masonry') {
        renderMasonry();
      } else {
        renderTable();
      }
    });
  }

  $('#add_speaker').on('click', function(e){
    e.preventDefault();
    const title = $('#speaker_title').val().trim();
    const first_name = $('#speaker_first_name').val().trim();
    const last_name = $('#speaker_last_name').val().trim();
    const photo_id = $('#speaker_photo_id').val();
    
    const selectedRoles = [];
    $('#roles_checkboxes input[type="checkbox"]:checked').each(function() {
      selectedRoles.push($(this).val());
    });

    if (first_name === '' || last_name === '') {
      alert('First name and last name are required.');
      return;
    }

    if (selectedRoles.length === 0) {
      $('#roles_error').show();
      return;
    }

    $('#roles_error').hide();

    // Upload photo first if selected, then save speaker
    uploadPhotoIfSelected().then(function(uploadSuccess) {
      if (!uploadSuccess && $('#speaker_photo')[0].files && $('#speaker_photo')[0].files[0]) {
        return; // Photo upload failed and user selected a file
      }

      const finalPhotoId = $('#speaker_photo_id').val();

      $.post(AURL, {
        action: 'digical_add_speaker',
        title: title,
        first_name: first_name,
        last_name: last_name,
        bio: '',
        roles: selectedRoles,
        photo_id: finalPhotoId
      }, function(resp){
        if (!resp || !resp.success) {
          alert(resp?.data?.message || 'Save failed');
          return;
        }
        $('#speaker_title').val('');
        $('#speaker_first_name').val('');
        $('#speaker_last_name').val('');
        $('#speaker_photo_id').val('');
        $('#roles_checkboxes input[type="checkbox"]').prop('checked', false);
        $('#photo_preview').hide();
        SPEAKER_PHOTO_ID = '';
        loadSpeakers();
      });
    });
  });

  function switchView(view) {
    CURRENT_VIEW = view;
    
    if (view === 'masonry') {
      $('#speakers_masonry').show();
      $('#speakers_table_container').hide();
      $('#view-masonry-btn').addClass('active');
      $('#view-table-btn').removeClass('active');
      renderMasonry();
    } else {
      $('#speakers_masonry').hide();
      $('#speakers_table_container').show();
      $('#view-masonry-btn').removeClass('active');
      $('#view-table-btn').addClass('active');
      renderTable();
    }
  }

  $('#view-masonry-btn').on('click', function() {
    switchView('masonry');
  });

  $('#view-table-btn').on('click', function() {
    switchView('table');
  });

  function renderTable(speakers = null) {
    const speakersToRender = speakers !== null ? speakers : SPEAKERS;
    const $tbody = $('#speakers_table_body');
    $tbody.empty();

    if (speakersToRender.length === 0) {
      $tbody.html('<tr><td colspan="8" style="text-align: center; padding: 40px; color: #999;">No speakers found</td></tr>');
      return;
    }

    speakersToRender.forEach(speaker => {
      const rolesText = speaker.roles && Array.isArray(speaker.roles) ? speaker.roles.join(', ') : '—';
      const photoHtml = speaker.photo_url && speaker.photo_url.trim()
        ? `<img src="${speaker.photo_url}" alt="Photo" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">` 
        : `<div style="width: 40px; height: 40px; border-radius: 4px; background: #e5e7eb; border: 1px solid #d1d5db; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #9ca3af;">👤</div>`;

      const row = $(`
        <tr data-id="${speaker.id}">
          <td style="text-align: center;"><input type="checkbox" class="table-row-select" value="${speaker.id}"></td>
          <td>${escapeHtml(speaker.title || '')}</td>
          <td>${escapeHtml(speaker.first_name)}</td>
          <td>${escapeHtml(speaker.last_name)}</td>
          <td style="font-size: 12px;">${rolesText}</td>
          <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 12px;">${escapeHtml(speaker.bio || '')}</td>
          <td>${photoHtml}</td>
          <td>
            <button class="digical-btn btn-table-edit" style="margin-right: 4px; padding: 4px 8px; font-size: 12px;">Edit</button>
            <button class="digical-btn digical-btn-red btn-table-delete" style="padding: 4px 8px; font-size: 12px;">Delete</button>
          </td>
        </tr>
      `);

      row.find('.btn-table-delete').on('click', function() {
        if (!confirm('Delete this speaker?')) return;
        $.post(AURL, { action: 'digical_delete_speaker', id: speaker.id }, function(resp) {
          if (!resp || !resp.success) {
            alert(resp?.data?.message || 'Delete failed');
            return;
          }
          SPEAKERS = resp.data.speakers || [];
          renderTable();
        });
      });

      row.find('.btn-table-edit').on('click', function() {
        // Edit inline in table
        const $row = $(this).closest('tr');
        const title = $row.find('td:eq(1)').text();
        const firstName = $row.find('td:eq(2)').text();
        const lastName = $row.find('td:eq(3)').text();
        const rolesText = $row.find('td:eq(4)').text();
        const bio = speaker.bio || '';

        // Get current roles
        const currentRoles = speaker.roles && Array.isArray(speaker.roles) ? speaker.roles : [];

        // Initialize bio modal if needed
        initBioModal();

        // Create edit fields
        const titleInput = $(`<input type="text" value="${escapeHtml(title)}" style="width: 100%; padding: 4px; border: 1px solid #ddd; border-radius: 3px;">`);
        const firstNameInput = $(`<input type="text" value="${escapeHtml(firstName)}" style="width: 100%; padding: 4px; border: 1px solid #ddd; border-radius: 3px;">`);
        const lastNameInput = $(`<input type="text" value="${escapeHtml(lastName)}" style="width: 100%; padding: 4px; border: 1px solid #ddd; border-radius: 3px;">`);

        // Replace cells with inputs
        $row.find('td:eq(1)').html(titleInput);
        $row.find('td:eq(2)').html(firstNameInput);
        $row.find('td:eq(3)').html(lastNameInput);

        // Create roles selector
        let rolesHtml = '<div style="display: flex; flex-direction: column; gap: 4px; font-size: 11px;">';
        ROLES.forEach(role => {
          const checked = currentRoles.includes(role.role) ? ' checked' : '';
          rolesHtml += `<label style="display: flex; align-items: center; gap: 4px;"><input type="checkbox" class="edit-role-chk" value="${escapeHtml(role.role)}"${checked}> ${escapeHtml(role.role)}</label>`;
        });
        rolesHtml += '</div>';
        $row.find('td:eq(4)').html(rolesHtml);

        // Bio button to open modal
        $row.find('td:eq(5)').html(`
          <button class="digical-btn" style="padding: 4px 8px; font-size: 11px;">📝 Edit</button>
        `);

        // Set bio in modal
        $('#bio-modal-field').val(bio);

        $row.find('td:eq(5) button').on('click', function(e) {
          e.preventDefault();
          $('#bio-modal-overlay').css('display', 'flex');
        });

        // Replace buttons with Save/Cancel
        $row.find('td:eq(7)').html(`
          <button class="digical-btn digical-btn-green btn-table-save" style="margin-right: 4px; padding: 4px 8px; font-size: 12px;">Save</button>
          <button class="digical-btn btn-table-cancel" style="padding: 4px 8px; font-size: 12px;">Cancel</button>
        `);

        $row.find('.btn-table-save').on('click', function() {
          const title = titleInput.val().trim();
          const firstName = firstNameInput.val().trim();
          const lastName = lastNameInput.val().trim();
          const bio = $('#bio-modal-field').val().trim();

          if (firstName === '' || lastName === '') {
            alert('First name and last name are required.');
            return;
          }

          const selectedRoles = [];
          $row.find('.edit-role-chk:checked').each(function() {
            selectedRoles.push($(this).val());
          });

          if (selectedRoles.length === 0) {
            alert('Please select at least one role.');
            return;
          }

          $.post(AURL, {
            action: 'digical_edit_speaker',
            id: speaker.id,
            title: title,
            first_name: firstName,
            last_name: lastName,
            bio: bio,
            roles: selectedRoles,
            photo_id: speaker.photo_id || ''
          }, function(resp) {
            if (!resp || !resp.success) {
              alert(resp?.data?.message || 'Update failed');
              return;
            }
            SPEAKERS = resp.data.speakers || [];
            $('#bio-modal-overlay').hide();
            renderTable();
          });
        });

        $row.find('.btn-table-cancel').on('click', function() {
          $('#bio-modal-overlay').hide();
          renderTable();
        });
      });

      $tbody.append(row);
    });
  }

  function renderMasonry(speakers = null) {
    const speakersToRender = speakers !== null ? speakers : SPEAKERS;
    const $masonry = $('#speakers_masonry');
    $masonry.empty();

    if (speakersToRender.length === 0) {
      $masonry.html('<div style="grid-column: 1 / -1; text-align: center; color: #999; padding: 40px;">No speakers found</div>');
      return;
    }

    speakersToRender.forEach(speaker => {
      const rolesHtml = speaker.roles && Array.isArray(speaker.roles) && speaker.roles.length > 0
        ? '<div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px;">' + speaker.roles.map(r => '<div style="background: #e7f3ff; color: #0073aa; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 500; white-space: nowrap;">' + escapeHtml(r) + '</div>').join('') + '</div>'
        : '<span style="color:#999;">—</span>';

      const photoHtml = speaker.photo_url && speaker.photo_url.trim()
        ? `<img src="${speaker.photo_url}" alt="Photo" style="position: absolute; top: 12px; right: 12px; width: 80px; height: 80px; border-radius: 6px; object-fit: cover; border: 2px solid #0b6ea6;">`
        : `<div style="position: absolute; top: 12px; right: 12px; width: 80px; height: 80px; border-radius: 6px; background: #e5e7eb; border: 2px solid #d1d5db; display: flex; align-items: center; justify-content: center; font-size: 40px; color: #9ca3af;">👤</div>`;

      const card = $(`
        <div class="speaker-card" data-id="${speaker.id}" style="position: relative;">
          ${photoHtml}
          
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0; padding-right: 100px; min-height: 80px;">
            <div style="flex: 1;">
              <div style="font-size: 18px; font-weight: 700; color: #000; margin-bottom: 4px;">${escapeHtml(speaker.title || '')} ${escapeHtml(speaker.first_name)} ${escapeHtml(speaker.last_name)}</div>
            </div>
          </div>
          
          <div style="margin: 20px 0 12px 0;">${rolesHtml}</div>
          
          <div style="font-size: 13px; color: #555; line-height: 1.5; margin: 12px 0; flex-grow: 1;">${escapeHtml(speaker.bio || '')}</div>
          
          <div style="display: flex; gap: 8px; margin-top: auto;">
            <button class="digical-btn btn-edit" style="flex: 1;">Edit</button>
            <button class="digical-btn digical-btn-red btn-delete" style="flex: 1;">Delete</button>
          </div>
        </div>
      `);

      card.find('.btn-delete').on('click', function() {
        if (!confirm('Delete this speaker?')) return;
        $.post(AURL, { action: 'digical_delete_speaker', id: speaker.id }, function(resp) {
          if (!resp || !resp.success) {
            alert(resp?.data?.message || 'Delete failed');
            return;
          }
          SPEAKERS = resp.data.speakers || [];
          renderMasonry();
        });
      });

      card.find('.btn-edit').on('click', function() {
        enterEditMode(card, speaker);
      });

      $masonry.append(card);
    });
  }

  // Create bio modal once globally
  function initBioModal() {
    if ($('#bio-modal-overlay').length) return;
    
    const bioModal = $(`
      <div id="bio-modal-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: #fff; border-radius: 8px; padding: 24px; width: 90%; max-width: 500px; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
          <div style="font-size: 18px; font-weight: 600; margin-bottom: 16px; color: #000;">Edit Bio</div>
          <textarea id="bio-modal-field" class="edit-field" placeholder="Speaker biography..." style="width: 100%; min-height: 200px; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; font-family: inherit; box-sizing: border-box; resize: vertical;"></textarea>
          <div style="display: flex; gap: 8px; margin-top: 16px;">
            <button class="digical-btn digical-btn-green" id="bio-modal-close" style="flex: 1;">Done</button>
            <button class="digical-btn digical-btn-red" id="bio-modal-cancel" style="flex: 1;">Cancel</button>
          </div>
        </div>
      </div>
    `);
    $('body').append(bioModal);
    
    $('#bio-modal-close').on('click', function() {
      $('#bio-modal-overlay').hide();
    });
    
    $('#bio-modal-cancel').on('click', function() {
      $('#bio-modal-overlay').hide();
    });
  }

  function enterEditMode($card, speaker) {
    console.log('Edit clicked for:', speaker);
    
    initBioModal();
    
    const $cardContent = $card;
    
    // Create edit fields with modern styling
    const titleField = $(`<input type="text" class="edit-field" placeholder="Title (optional)" value="${escapeHtml(speaker.title || '')}" style="width: 100%; box-sizing: border-box; margin-bottom: 12px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; font-family: inherit;">`);
    const firstField = $(`<input type="text" class="edit-field" placeholder="First name *" value="${escapeHtml(speaker.first_name)}" required style="width: 100%; box-sizing: border-box; margin-bottom: 12px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; font-family: inherit;">`);
    const lastField = $(`<input type="text" class="edit-field" placeholder="Last name *" value="${escapeHtml(speaker.last_name)}" required style="width: 100%; box-sizing: border-box; margin-bottom: 12px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; font-family: inherit;">`);

    // Create roles checkboxes with modern styling
    let rolesEditHtml = '<div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; padding: 12px; background: #f8f9fa; border-radius: 4px;">';
    const currentRoles = speaker.roles && Array.isArray(speaker.roles) ? speaker.roles : [];
    ROLES.forEach(role => {
      const checked = currentRoles.includes(role.role) ? ' checked' : '';
      rolesEditHtml += `<label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
        <input type="checkbox" class="edit-role-chk" value="${escapeHtml(role.role)}"${checked} style="cursor: pointer; width: 16px; height: 16px;"> 
        <span style="font-size: 14px;">${escapeHtml(role.role)}</span>
      </label>`;
    });
    rolesEditHtml += '</div>';

    // Store photo HTML
    const photoImg = $cardContent.find('img').first();
    const photoHtml = photoImg.length ? photoImg[0].outerHTML : '';

    // Clear card content with modern layout
    $cardContent.html(`
      <div style="position: relative;">
        ${photoHtml ? `<div style="position: absolute; top: 0; right: 0;">${photoHtml}</div>` : ''}
        <div style="padding-right: ${photoHtml ? '100px' : '0'};">
        </div>
      </div>
    `);

    // Append fields to card
    const $editArea = $cardContent.find('div:last');
    $editArea.append(titleField);
    $editArea.append(firstField);
    $editArea.append(lastField);
    $editArea.append($(rolesEditHtml));

    // Bio button instead of field
    const bioButton = $(`
      <button type="button" class="digical-btn" style="width: 100%; padding: 10px; margin-bottom: 12px; border: 0; font-size: 14px;">
        📝 Edit Bio
      </button>
    `);
    $editArea.append(bioButton);

    // Photo upload/browse section
    const photoSection = $(`
      <div style="display: flex; gap: 12px; align-items: flex-start; margin-bottom: 12px;">
        <div style="display: flex; gap: 12px; align-items: flex-start;">
          <input type="file" id="edit_speaker_photo" accept="image/*" style="display: none;">
          <button type="button" id="edit_browse_photo_btn" class="digical-btn" style="padding: 8px 24px; border: 0; white-space: nowrap;">Browse Photo</button>
          <div id="edit_photo_preview" style="display: ${speaker.photo_id ? 'block' : 'none'};">
            <img id="edit_photo_preview_img" src="${speaker.photo_url || ''}" alt="Preview" style="max-width: 100px; max-height: 100px; border-radius: 4px; border: 1px solid #ddd;">
          </div>
        </div>
      </div>
    `);
    $editArea.append(photoSection);

    // Handle photo browse
    $('#edit_browse_photo_btn').on('click', function(e) {
      e.preventDefault();
      $('#edit_speaker_photo').click();
    });

    // Handle photo selection
    $('#edit_speaker_photo').on('change', function() {
      const file = this.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = function(e) {
        $('#edit_photo_preview_img').attr('src', e.target.result);
        $('#edit_photo_preview').show();
      };
      reader.readAsDataURL(file);
    });

    // Set current bio in modal
    $('#bio-modal-field').val(speaker.bio || '');

    bioButton.on('click', function(e) {
      e.preventDefault();
      $('#bio-modal-overlay').css('display', 'flex');
    });

    // Add save/cancel buttons for main form
    const editActions = $(`
      <div style="display: flex; gap: 8px; margin-top: 12px;">
        <button class="digical-btn digical-btn-green btn-save-edit" style="flex: 1; padding: 10px; font-size: 14px; font-weight: 600;">✓ Save Speaker</button>
        <button class="digical-btn digical-btn-red btn-cancel-edit" style="flex: 1; padding: 10px; font-size: 14px; font-weight: 600;">✕ Cancel</button>
      </div>
    `);
    $cardContent.append(editActions);

    editActions.find('.btn-cancel-edit').on('click', function(e) {
      e.preventDefault();
      $('#bio-modal-overlay').hide();
      renderMasonry();
    });

    editActions.find('.btn-save-edit').on('click', function(e) {
      e.preventDefault();
      
      const title = titleField.val().trim();
      const firstName = firstField.val().trim();
      const lastName = lastField.val().trim();
      const bio = $('#bio-modal-field').val().trim();
      let photoId = speaker.photo_id || '';

      console.log('Saving:', { title, firstName, lastName, bio });

      if (firstName === '' || lastName === '') {
        alert('First name and last name are required.');
        return;
      }

      const selectedRoles = [];
      $cardContent.find('.edit-role-chk:checked').each(function() {
        selectedRoles.push($(this).val());
      });

      if (selectedRoles.length === 0) {
        alert('Please select at least one role.');
        return;
      }

      // Check if new photo was selected
      const fileInput = $('#edit_speaker_photo')[0];
      if (fileInput.files && fileInput.files[0]) {
        // Upload new photo first
        const file = fileInput.files[0];
        const formData = new FormData();
        formData.append('action', 'digical_upload_speaker_photo');
        formData.append('file', file);
        formData.append('folder', 'ConfCal Speakers');

        $.ajax({
          url: AURL,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(resp) {
            if (!resp.success) {
              alert(resp.data?.message || 'Photo upload failed');
              return;
            }

            photoId = resp.data.id;

            // Save speaker with new photo
            saveSpeaker(title, firstName, lastName, bio, selectedRoles, photoId, speaker.id);
          },
          error: function() {
            alert('Photo upload error');
          }
        });
      } else {
        // Save speaker without photo change
        saveSpeaker(title, firstName, lastName, bio, selectedRoles, photoId, speaker.id);
      }

      function saveSpeaker(title, firstName, lastName, bio, roles, photoId, speakerId) {
        console.log('Sending AJAX:', {
          action: 'digical_edit_speaker',
          id: speakerId,
          title: title,
          first_name: firstName,
          last_name: lastName,
          bio: bio,
          roles: roles,
          photo_id: photoId
        });

        $.post(AURL, {
          action: 'digical_edit_speaker',
          id: speakerId,
          title: title,
          first_name: firstName,
          last_name: lastName,
          bio: bio,
          roles: roles,
          photo_id: photoId
        }, function(resp) {
          console.log('Save response:', resp);
          if (!resp || !resp.success) {
            alert(resp?.data?.message || 'Update failed');
            return;
          }
          SPEAKERS = resp.data.speakers || [];
          $('#bio-modal-overlay').hide();
          renderMasonry();
        }).fail(function(xhr, status, error) {
          console.error('AJAX error:', error, xhr);
          alert('Save failed: ' + error);
        });
      }
    });
  }

  function escapeHtml(s){ 
    if (!s) return '';
    return (s + '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m])); 
  }
  function escAttr(s){ return escapeHtml(s); }

  $(document).ready(function() {
    initCollapsible();
    loadTitles();
    loadRoles();
    loadSpeakers();
  });
})(jQuery);
</script>