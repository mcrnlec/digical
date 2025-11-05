<?php
if (!defined('ABSPATH')) exit;

/**
 * Venues admin UI — modern professional styling with Table & Masonry views.
 * Matches days.php and speakers.php design system.
 * Depends on AJAX handlers from admin/venues-ajax-db.php.
 */

global $wpdb;

$nonce   = wp_create_nonce('digical_nonce');
$ajaxurl = admin_url('admin-ajax.php');
?>

<style>
/* ===== Venues Management - Modern Professional Styling ===== */

.digical-venues-container {
    background: transparent;
    padding: 0;
}

/* ===== Form Section ===== */
#venue-form {
    background: linear-gradient(135deg, #f8fafc 0%, #f0f4f8 100%);
    border: 1px solid #e0e6ed;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 30px;
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    align-items: flex-end;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

#venue-form select,
#venue-form input[type="text"] {
    padding: 12px 16px;
    font-size: 14px;
    border: 2px solid #d1d8e0;
    border-radius: 8px;
    box-sizing: border-box;
    transition: all 0.3s ease;
    flex: 1;
    min-width: 160px;
    font-weight: 500;
    color: #2c3e50;
    background: white;
}

#venue-form select {
    padding: 12px 14px;
    cursor: pointer;
}

#venue-form input[type="text"]:focus,
#venue-form select:focus {
    outline: none;
    border-color: #2271b1;
    box-shadow: 0 0 0 3px rgba(34, 113, 177, 0.1);
    background: white;
}

#venue-form input[type="text"]::placeholder {
    color: #7f8c8d;
    font-weight: 500;
}

#save_venue_btn {
    background: linear-gradient(135deg, #2271b1 0%, #1a5a8e 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 12px 32px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 2px 8px rgba(34, 113, 177, 0.2);
    white-space: nowrap;
}

#save_venue_btn:hover {
    box-shadow: 0 4px 16px rgba(34, 113, 177, 0.3);
    transform: translateY(-2px);
}

#save_venue_btn:active {
    transform: translateY(0);
}

/* ===== View Toggle Controls ===== */
.view-toggle-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 20px 0 24px 0;
}

.view-toggle {
    display: flex;
    gap: 12px;
    margin-bottom: 0;
}

.view-toggle-btn {
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

.view-toggle-btn:hover {
    border-color: #2271b1;
    background: linear-gradient(135deg, #ecf0f1 0%, #d5dbdb 100%);
}

.view-toggle-btn.active {
    background: linear-gradient(135deg, #2271b1 0%, #1a5a8e 100%);
    color: #fff;
    border-color: #2271b1;
    box-shadow: 0 2px 8px rgba(34, 113, 177, 0.2);
}

/* ===== Bulk Delete Button ===== */
.digical-bulk {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 2px 8px rgba(231, 76, 60, 0.2);
    display: none;
    margin-left: auto;
}

.digical-bulk.show {
    display: inline-block;
}

.digical-bulk:hover {
    box-shadow: 0 4px 16px rgba(231, 76, 60, 0.3);
    transform: translateY(-2px);
}

.digical-bulk:active {
    transform: translateY(0);
}

/* ===== Table Styles ===== */
.digical-venues-table {
    border-collapse: collapse;
    margin-top: 0;
    font-size: 15px;
    table-layout: auto;
    width: 100%;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid #e0e6ed;
}

.digical-venues-table th {
    background: linear-gradient(135deg, #2271b1 0%, #1a5a8e 100%);
    color: #fff;
    text-align: left;
    font-weight: 700;
    font-size: 12px;
    border: none;
    padding: 14px 16px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.digical-venues-table th:first-child {
    padding-left: 16px;
    text-transform: none;
    letter-spacing: 0;
}

.digical-venues-table th:last-child {
    text-align: center;
    width: 200px;
}

.digical-venues-table td {
    background: #fff;
    padding: 14px 16px;
    border: none;
    border-bottom: 1px solid #ecf0f1;
    vertical-align: middle;
    font-size: 14px;
    color: #2c3e50;
    font-weight: 500;
}

.digical-venues-table tbody tr:last-child td {
    border-bottom: none;
}

.digical-venues-table tbody tr:hover td {
    background: #f8fafc;
}

.digical-venues-table input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #2271b1;
}

/* Primary venue indicator */
.venue-primary {
    font-weight: 700;
    color: #2271b1;
}

.venue-sub {
    font-weight: 500;
    color: #7f8c8d;
    padding-left: 12px;
    position: relative;
}

/* Type badge */
.venue-type-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.venue-type-badge.primary {
    background: #ecf3f9;
    color: #2271b1;
}

.venue-type-badge.sub {
    background: #f0f4f8;
    color: #5b7a97;
}

/* Address cell */
.venue-address {
    color: #7f8c8d;
    font-weight: 400;
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Action Buttons */
.digical-btn-action {
    background: linear-gradient(135deg, #2271b1 0%, #1a5a8e 100%);
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 12px;
    font-weight: 700;
    margin-right: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-block;
    box-shadow: 0 2px 6px rgba(34, 113, 177, 0.15);
    line-height: 1.4;
    white-space: nowrap;
}

.digical-btn-action:last-of-type {
    margin-right: 0;
}

.digical-btn-action:hover {
    box-shadow: 0 4px 12px rgba(34, 113, 177, 0.25);
    transform: translateY(-2px);
}

.digical-btn-action:active {
    transform: translateY(0);
}

.digical-btn-action.delete {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    box-shadow: 0 2px 6px rgba(231, 76, 60, 0.15);
}

.digical-btn-action.delete:hover {
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.25);
}

/* Inline Edit Inputs */
.inline-input,
.inline-select {
    width: 100%;
    padding: 8px 12px;
    font-size: 14px;
    border: 2px solid #d1d8e0;
    border-radius: 6px;
    box-sizing: border-box;
    transition: all 0.3s ease;
    font-weight: 500;
    color: #2c3e50;
}

.inline-input:focus,
.inline-select:focus {
    outline: none;
    border-color: #2271b1;
    box-shadow: 0 0 0 3px rgba(34, 113, 177, 0.1);
}

/* ===== Masonry View ===== */
.digical-venues-masonry {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.venue-card {
    background: white;
    border: 1px solid #e0e6ed;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    min-height: 200px;
    position: relative;
}

.venue-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
    border-color: #2271b1;
}

.venue-card-checkbox {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: #2271b1;
}

.venue-card-header {
    margin-bottom: 12px;
    padding-right: 0;
}

.venue-card-title {
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0 0 8px 0;
    word-break: break-word;
}

.venue-card-type {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 16px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 8px;
}

.venue-card-type.primary {
    background: #ecf3f9;
    color: #2271b1;
}

.venue-card-type.sub {
    background: #f0f4f8;
    color: #5b7a97;
}

.venue-card-primary {
    font-size: 13px;
    color: #7f8c8d;
    font-weight: 600;
    margin-top: 4px;
}

.venue-card-body {
    margin: 12px 0 20px 0;
    flex-grow: 1;
}

.venue-card-address {
    font-size: 13px;
    color: #7f8c8d;
    word-break: break-word;
    line-height: 1.6;
}

.venue-card-footer {
    display: flex;
    gap: 8px;
    padding-top: 12px;
    margin-top: auto;
}

.venue-card-footer button {
    flex: 1;
    padding: 8px 12px;
    font-size: 12px;
    font-weight: 700;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}

.venue-card-footer .btn-edit {
    background: linear-gradient(135deg, #2271b1 0%, #1a5a8e 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(34, 113, 177, 0.15);
}

.venue-card-footer .btn-edit:hover {
    box-shadow: 0 4px 12px rgba(34, 113, 177, 0.25);
    transform: translateY(-2px);
}

.venue-card-footer .btn-del {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(231, 76, 60, 0.15);
}

.venue-card-footer .btn-del:hover {
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.25);
    transform: translateY(-2px);
}

.masonry-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: #7f8c8d;
}

.masonry-empty-icon {
    font-size: 48px;
    margin-bottom: 12px;
    opacity: 0.5;
}

.masonry-empty-text {
    font-size: 16px;
    font-weight: 600;
}

/* Hide/Show based on view */
.digical-table-view {
    display: block;
}

.digical-table-view.hidden {
    display: none;
}

.digical-masonry-view {
    display: none;
}

.digical-masonry-view.show {
    display: block;
}

/* ===== Edit Modal ===== */
.venue-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.venue-modal-overlay.show {
    display: flex;
}

.venue-modal {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    max-height: 90vh;
    overflow-y: auto;
}

.venue-modal-header {
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 20px;
}

.venue-modal-field {
    margin-bottom: 16px;
}

.venue-modal-field label {
    display: block;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
    font-size: 14px;
}

.venue-modal-field input,
.venue-modal-field select {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #d1d8e0;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #2c3e50;
    box-sizing: border-box;
    transition: all 0.3s ease;
}

.venue-modal-field input:focus,
.venue-modal-field select:focus {
    outline: none;
    border-color: #2271b1;
    box-shadow: 0 0 0 3px rgba(34, 113, 177, 0.1);
}

.venue-modal-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

.venue-modal-actions button {
    flex: 1;
    padding: 12px 16px;
    font-size: 14px;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.venue-modal-actions .btn-save {
    background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(39, 174, 96, 0.15);
}

.venue-modal-actions .btn-save:hover {
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.25);
    transform: translateY(-2px);
}

.venue-modal-actions .btn-cancel {
    background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(157, 163, 171, 0.15);
}

.venue-modal-actions .btn-cancel:hover {
    box-shadow: 0 4px 12px rgba(157, 163, 171, 0.25);
    transform: translateY(-2px);
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    #venue-form {
        flex-direction: column;
    }

    #venue-form select,
    #venue-form input[type="text"],
    #save_venue_btn {
        width: 100%;
        min-width: unset;
    }

    .digical-venues-table th:last-child {
        width: auto;
    }

    .digical-venues-masonry {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
}

@media (max-width: 768px) {
    #venue-form {
        padding: 16px;
    }

    .digical-venues-table {
        font-size: 13px;
    }

    .digical-venues-table th,
    .digical-venues-table td {
        padding: 10px 12px;
    }

    .digical-btn-action {
        padding: 6px 10px;
        font-size: 11px;
    }

    .venue-address {
        max-width: 150px;
    }

    .digical-venues-masonry {
        grid-template-columns: 1fr;
    }

    .venue-card {
        padding: 16px;
    }

    .venue-card-title {
        font-size: 16px;
    }

    .view-toggle-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
}
</style>

<div class="digical-venues-container">
    <!-- Venue Input Form -->
    <form id="venue-form">
        <select id="venue_type" aria-label="<?php esc_attr_e('Venue Type', 'digical'); ?>">
            <option value="primary"><?php esc_html_e('Primary Venue', 'digical'); ?></option>
            <option value="secondary"><?php esc_html_e('Sub-venue', 'digical'); ?></option>
        </select>

        <select id="parent_select" aria-label="<?php esc_attr_e('Parent Venue', 'digical'); ?>" style="display:none;">
        </select>

        <input 
            type="text" 
            id="venue_name" 
            placeholder="<?php esc_attr_e('Venue name', 'digical'); ?>" 
            aria-label="<?php esc_attr_e('Venue name', 'digical'); ?>"
        >

        <input 
            type="text" 
            id="venue_address" 
            placeholder="<?php esc_attr_e('Address', 'digical'); ?>" 
            aria-label="<?php esc_attr_e('Address', 'digical'); ?>"
        >

        <button type="button" id="save_venue_btn">
            <?php esc_html_e('Save Venue', 'digical'); ?>
        </button>
    </form>

    <!-- View Toggle Bar -->
    <div class="view-toggle-bar">
        <div class="view-toggle">
            <button type="button" class="view-toggle-btn active" data-view="table" title="<?php esc_attr_e('Table view', 'digical'); ?>">
                📊 <?php esc_html_e('Table', 'digical'); ?>
            </button>
            <button type="button" class="view-toggle-btn" data-view="masonry" title="<?php esc_attr_e('Masonry view', 'digical'); ?>">
                📋 <?php esc_html_e('Masonry', 'digical'); ?>
            </button>
        </div>
        <button type="button" id="bulk_delete" class="digical-bulk">
            <?php esc_html_e('Delete Selected', 'digical'); ?>
        </button>
    </div>

    <!-- Table View -->
    <div class="digical-table-view">
        <table class="digical-venues-table" id="venues_table" aria-live="polite">
            <thead>
                <tr>
                    <th style="width:50px;">
                        <input type="checkbox" id="chk_all" aria-label="<?php esc_attr_e('Select all venues', 'digical'); ?>">
                    </th>
                    <th style="width:140px;"><?php esc_html_e('Type', 'digical'); ?></th>
                    <th style="width:280px;"><?php esc_html_e('Primary Venue', 'digical'); ?></th>
                    <th style="width:280px;"><?php esc_html_e('Venue Name', 'digical'); ?></th>
                    <th><?php esc_html_e('Address', 'digical'); ?></th>
                    <th><?php esc_html_e('Actions', 'digical'); ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Masonry View -->
    <div class="digical-masonry-view" id="masonry_view">
    </div>

    <!-- Edit Modal -->
    <div class="venue-modal-overlay" id="venue_modal_overlay">
        <div class="venue-modal">
            <div class="venue-modal-header"><?php esc_html_e('Edit Venue', 'digical'); ?></div>
            
            <div class="venue-modal-field">
                <label><?php esc_html_e('Venue Type', 'digical'); ?></label>
                <select id="modal_venue_type">
                    <option value="primary"><?php esc_html_e('Primary Venue', 'digical'); ?></option>
                    <option value="secondary"><?php esc_html_e('Sub-venue', 'digical'); ?></option>
                </select>
            </div>

            <div class="venue-modal-field" id="modal_parent_field" style="display: none;">
                <label><?php esc_html_e('Parent Venue', 'digical'); ?></label>
                <select id="modal_parent_select">
                </select>
            </div>

            <div class="venue-modal-field">
                <label id="modal_name_label"><?php esc_html_e('Venue Name', 'digical'); ?></label>
                <input type="text" id="modal_venue_name">
            </div>

            <div class="venue-modal-field">
                <label><?php esc_html_e('Address', 'digical'); ?></label>
                <input type="text" id="modal_venue_address">
            </div>

            <div class="venue-modal-actions">
                <button type="button" class="btn-save" id="modal_save_btn"><?php esc_html_e('Save', 'digical'); ?></button>
                <button type="button" class="btn-cancel" id="modal_cancel_btn"><?php esc_html_e('Cancel', 'digical'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
(function($) {
    'use strict';

    const AURL = (window.DIGICAL && DIGICAL.ajaxurl) || window.ajaxurl;
    let VENUES = [];
    let PRIMARIES = [];
    let CURRENT_EDIT_ID = null;

    /**
     * Set view mode and save preference
     */
    function setView(view) {
        localStorage.setItem('digical_venue_view', view);
        const $tableView = $('.digical-table-view');
        const $masonryView = $('.digical-masonry-view');

        if (view === 'masonry') {
            $tableView.addClass('hidden');
            $masonryView.addClass('show');
        } else {
            $tableView.removeClass('hidden');
            $masonryView.removeClass('show');
        }

        $('.view-toggle-btn').removeClass('active');
        $(`.view-toggle-btn[data-view="${view}"]`).addClass('active');
    }

    /**
     * View toggle buttons
     */
    $('.view-toggle-btn').on('click', function() {
        const view = $(this).data('view');
        setView(view);
    });

    /**
     * Load all venues from server
     */
    function loadVenues() {
        $.post(AURL, { action: 'digical_db_get_venues' }, function(resp) {
            if (!resp || !resp.success) {
                alert(resp?.data?.message || '<?php esc_attr_e('Failed to load venues', 'digical'); ?>');
                return;
            }
            VENUES = resp.data.venues || [];
            PRIMARIES = VENUES.filter(v => v.type === 'primary');
            renderParentSelectInForm();
            renderTable();
            renderMasonry();
        });
    }

    /**
     * Update parent select dropdown in form
     */
    function renderParentSelectInForm() {
        const $sel = $('#parent_select');
        const hasPrimaries = PRIMARIES.length > 0;
        $sel.empty();

        if (hasPrimaries) {
            $sel.append('<option value="">— <?php esc_html_e('Select primary venue', 'digical'); ?> —</option>');
            PRIMARIES.forEach(p => {
                $sel.append(`<option value="${p.id}">${escapeHtml(p.name)}</option>`);
            });
        }

        const type = $('#venue_type').val();
        $sel.toggle(type === 'secondary' && hasPrimaries);
    }

    $('#venue_type').on('change', function() {
        renderParentSelectInForm();
    });

    $('#parent_select').on('change', function() {
        const pid = $(this).val();
        const parent = PRIMARIES.find(p => p.id === pid);
        if (parent) {
            $('#venue_address').val(parent.address || '');
        }
    });

    /**
     * Save venue button handler
     */
    $('#save_venue_btn').on('click', function(e) {
        e.preventDefault();
        const type = $('#venue_type').val();
        const name = $('#venue_name').val().trim();
        const address = $('#venue_address').val().trim();
        const pid = $('#parent_select').is(':visible') ? $('#parent_select').val() : '';

        if (!name) {
            alert('<?php esc_attr_e('Please enter a venue name', 'digical'); ?>');
            return;
        }

        $.post(AURL, {
            action: 'digical_db_add_venue',
            venue_type: type,
            venue_name: name,
            venue_address: address,
            parent_id: pid
        }, function(resp) {
            if (!resp || !resp.success) {
                alert(resp?.data?.message || '<?php esc_attr_e('Failed to save venue', 'digical'); ?>');
                return;
            }
            $('#venue_name').val('');
            $('#venue_address').val('');
            $('#parent_select').val('');
            $('#venue_type').val('primary');
            loadVenues();
        });
    });

    /**
     * Render venues table
     */
    function renderTable() {
        const $tb = $('#venues_table tbody');
        $tb.empty();

        VENUES.forEach(row => {
            const tr = document.createElement('tr');
            tr.dataset.id = row.id;
            tr.dataset.type = row.type;
            tr.dataset.edit = '0';

            const isPrimary = row.type === 'primary';

            const checkedCell = `<td><input type="checkbox" class="chk_row" aria-label="<?php esc_attr_e('Select venue', 'digical'); ?>"></td>`;

            const typeBadge = isPrimary
                ? `<td><span class="venue-type-badge primary"><?php esc_html_e('Primary', 'digical'); ?></span></td>`
                : `<td><span class="venue-type-badge sub"><?php esc_html_e('Sub-venue', 'digical'); ?></span></td>`;

            const primaryName = isPrimary ? row.name : (row.parent_name || '');
            const primaryClass = isPrimary ? 'venue-primary' : '';
            const primaryCell = `<td class="${primaryClass}">${escapeHtml(primaryName)}</td>`;

            const subName = isPrimary ? '' : row.name;
            const subClass = isPrimary ? '' : 'venue-sub';
            const subCell = `<td class="${subClass}">${escapeHtml(subName)}</td>`;

            const addrCell = `<td class="venue-address" title="${escapeHtml(row.address || '')}">${escapeHtml(row.address || '')}</td>`;

            const actions = `
                <td style="text-align: center;">
                    <button type="button" class="digical-btn-action btn-edit" aria-label="<?php esc_attr_e('Edit venue', 'digical'); ?>"><?php esc_html_e('Edit', 'digical'); ?></button>
                    <button type="button" class="digical-btn-action delete btn-del" aria-label="<?php esc_attr_e('Delete venue', 'digical'); ?>"><?php esc_html_e('Delete', 'digical'); ?></button>
                </td>
            `;

            tr.innerHTML = checkedCell + typeBadge + primaryCell + subCell + addrCell + actions;
            $tb.append(tr);
        });

        bindTableRowHandlers();
        updateBulkBar();
    }

    /**
     * Render masonry view
     */
    function renderMasonry() {
        const $masonry = $('#masonry_view');
        $masonry.empty();

        if (VENUES.length === 0) {
            $masonry.html(`
                <div class="digical-venues-masonry">
                    <div class="masonry-empty">
                        <div class="masonry-empty-icon">📭</div>
                        <div class="masonry-empty-text"><?php esc_html_e('No venues yet', 'digical'); ?></div>
                    </div>
                </div>
            `);
            return;
        }

        const $container = $('<div class="digical-venues-masonry"></div>');

        VENUES.forEach(row => {
            const isPrimary = row.type === 'primary';
            const primaryName = isPrimary ? '' : (row.parent_name || '');
            const typeBadge = isPrimary
                ? '<span class="venue-card-type primary"><?php esc_html_e('Primary', 'digical'); ?></span>'
                : '<span class="venue-card-type sub"><?php esc_html_e('Sub-venue', 'digical'); ?></span>';

            const $card = $(`
                <div class="venue-card" data-id="${escapeHtml(row.id)}" style="position: relative;">
                    <input type="checkbox" class="chk_masonry" data-id="${escapeHtml(row.id)}" aria-label="<?php esc_attr_e('Select venue', 'digical'); ?>">
                    
                    <div class="venue-card-header">
                        ${typeBadge}
                        <h3 class="venue-card-title">${escapeHtml(row.name)}</h3>
                        ${primaryName ? `<div class="venue-card-primary"><?php esc_html_e('In:', 'digical'); ?> ${escapeHtml(primaryName)}</div>` : ''}
                    </div>

                    <div class="venue-card-body">
                        ${row.address ? `<div class="venue-card-address">${escapeHtml(row.address)}</div>` : '<div class="venue-card-address" style="color: #bdc3c7;">—</div>'}
                    </div>

                    <div class="venue-card-footer">
                        <button type="button" class="btn-edit" data-id="${escapeHtml(row.id)}" aria-label="<?php esc_attr_e('Edit venue', 'digical'); ?>"><?php esc_html_e('Edit', 'digical'); ?></button>
                        <button type="button" class="btn-del" data-id="${escapeHtml(row.id)}" aria-label="<?php esc_attr_e('Delete venue', 'digical'); ?>"><?php esc_html_e('Delete', 'digical'); ?></button>
                    </div>
                </div>
            `);

            $container.append($card);
        });

        $masonry.append($container);
        bindMasonryHandlers();
        updateBulkBar();
    }

    /**
     * Open edit modal for masonry
     */
    function openEditModal(id) {
        CURRENT_EDIT_ID = id;
        const venue = VENUES.find(v => v.id === id);
        if (!venue) return;

        const isPrim = venue.type === 'primary';

        $('#modal_venue_type').val(venue.type);
        $('#modal_venue_name').val(venue.name);
        $('#modal_venue_address').val(venue.address || '');

        const $parentField = $('#modal_parent_field');
        if (isPrim) {
            $parentField.hide();
        } else {
            $parentField.show();
            $('#modal_parent_select').empty();
            PRIMARIES.forEach(p => {
                const selected = p.id === venue.parent_id ? ' selected' : '';
                $('#modal_parent_select').append(`<option value="${p.id}"${selected}>${escapeHtml(p.name)}</option>`);
            });
        }

        $('#modal_name_label').text(isPrim ? '<?php esc_html_e('Venue Name', 'digical'); ?>' : '<?php esc_html_e('Sub-venue Name', 'digical'); ?>');

        $('#venue_modal_overlay').addClass('show');
    }

    function closeEditModal() {
        $('#venue_modal_overlay').removeClass('show');
        CURRENT_EDIT_ID = null;
    }

    /**
     * Modal type change handler
     */
    $('#modal_venue_type').on('change', function() {
        const type = $(this).val();
        if (type === 'primary') {
            $('#modal_parent_field').hide();
            $('#modal_name_label').text('<?php esc_html_e('Venue Name', 'digical'); ?>');
        } else {
            $('#modal_parent_field').show();
            $('#modal_name_label').text('<?php esc_html_e('Sub-venue Name', 'digical'); ?>');
        }
    });

    /**
     * Modal cancel button
     */
    $('#modal_cancel_btn').on('click', function() {
        closeEditModal();
    });

    /**
     * Modal save button
     */
    $('#modal_save_btn').on('click', function() {
        const type = $('#modal_venue_type').val();
        const name = $('#modal_venue_name').val().trim();
        const address = $('#modal_venue_address').val().trim();
        let parentId = '';

        if (type === 'secondary') {
            parentId = $('#modal_parent_select').val();
        }

        if (!name) {
            alert('<?php esc_attr_e('Venue name cannot be empty', 'digical'); ?>');
            return;
        }

        $.post(AURL, {
            action: 'digical_db_edit_venue',
            id: CURRENT_EDIT_ID,
            venue_type: type,
            venue_name: name,
            venue_address: address,
            parent_id: parentId
        }, function(resp) {
            if (!resp || !resp.success) {
                alert(resp?.data?.message || '<?php esc_attr_e('Failed to update venue', 'digical'); ?>');
                return;
            }
            closeEditModal();
            loadVenues();
        });
    });

    /**
     * Close modal when clicking overlay
     */
    $('#venue_modal_overlay').on('click', function(e) {
        if ($(e.target).is('#venue_modal_overlay')) {
            closeEditModal();
        }
    });

    /**
     * Bind table row event handlers
     */
    function bindTableRowHandlers() {
        $('#chk_all').off('change').on('change', function() {
            const on = $(this).is(':checked');
            $('#venues_table .chk_row').prop('checked', on);
            updateBulkBar();
        });

        $('#venues_table .chk_row').off('change').on('change', function() {
            if (!$(this).is(':checked')) {
                $('#chk_all').prop('checked', false);
            }
            updateBulkBar();
        });

        $('#venues_table .btn-del').off('click').on('click', function() {
            if (!confirm('<?php esc_attr_e('Are you sure? This cannot be undone.', 'digical'); ?>')) return;

            const id = $(this).closest('tr').data('id');
            $.post(AURL, { action: 'digical_db_delete_venue', id }, function(resp) {
                if (!resp || !resp.success) {
                    alert(resp?.data?.message || '<?php esc_attr_e('Failed to delete venue', 'digical'); ?>');
                    return;
                }
                loadVenues();
            });
        });

        $('#venues_table .btn-edit').off('click').on('click', function() {
            const $tr = $(this).closest('tr');
            if ($tr.data('edit') === '1') return;

            $tr.data('edit', '1');
            const id = $tr.data('id');
            const venue = VENUES.find(x => x.id === id);
            const isPrim = venue.type === 'primary';
            const $cells = $tr.children('td');

            $cells.eq(1).html(`
                <select class="inline-select edit-type">
                    <option value="primary" ${isPrim ? 'selected' : ''}><?php esc_html_e('Primary', 'digical'); ?></option>
                    <option value="secondary" ${!isPrim ? 'selected' : ''}><?php esc_html_e('Sub-venue', 'digical'); ?></option>
                </select>
            `);

            if (isPrim) {
                $cells.eq(2).html(`<input type="text" class="inline-input edit-primary-name" value="${escAttr(venue.name)}">`);
                $cells.eq(3).html(`<input type="text" class="inline-input edit-sub-name" value="" disabled>`);
            } else {
                let html = `<select class="inline-select edit-parent">`;
                PRIMARIES.forEach(p => {
                    const sel = (p.id === venue.parent_id) ? ' selected' : '';
                    html += `<option value="${p.id}"${sel}>${escAttr(p.name)}</option>`;
                });
                html += `</select>`;
                $cells.eq(2).html(html);
                $cells.eq(3).html(`<input type="text" class="inline-input edit-sub-name" value="${escAttr(venue.name)}">`);
            }

            $cells.eq(4).html(`<input type="text" class="inline-input edit-address" value="${escAttr(venue.address || '')}">`);

            $cells.eq(5).html(`
                <button type="button" class="digical-btn-action save btn-save"><?php esc_html_e('Save', 'digical'); ?></button>
                <button type="button" class="digical-btn-action cancel btn-cancel"><?php esc_html_e('Cancel', 'digical'); ?></button>
            `);

            $cells.eq(1).find('.edit-type').on('change', function() {
                const newType = $(this).val();
                if (newType === 'primary') {
                    $cells.eq(2).html(`<input type="text" class="inline-input edit-primary-name" value="${escAttr(isPrim ? venue.name : (venue.parent_name || ''))}">`);
                    $cells.eq(3).html(`<input type="text" class="inline-input edit-sub-name" value="" disabled>`);
                } else {
                    let html = `<select class="inline-select edit-parent">`;
                    PRIMARIES.forEach(p => {
                        html += `<option value="${p.id}">${escAttr(p.name)}</option>`;
                    });
                    html += `</select>`;
                    $cells.eq(2).html(html);
                    $cells.eq(3).html(`<input type="text" class="inline-input edit-sub-name" value="${escAttr(isPrim ? '' : venue.name)}">`);
                }
            });

            $cells.eq(5).find('.btn-cancel').on('click', function() {
                $tr.data('edit', '0');
                renderTable();
            });

            $cells.eq(5).find('.btn-save').on('click', function() {
                const newType = $cells.eq(1).find('.edit-type').val();
                let name, parentId = '';

                if (newType === 'primary') {
                    name = $cells.eq(2).find('.edit-primary-name').val().trim();
                } else {
                    parentId = $cells.eq(2).find('.edit-parent').val();
                    name = $cells.eq(3).find('.edit-sub-name').val().trim();
                }

                if (!name) {
                    alert('<?php esc_attr_e('Venue name cannot be empty', 'digical'); ?>');
                    return;
                }

                const address = $cells.eq(4).find('.edit-address').val().trim();

                $.post(AURL, {
                    action: 'digical_db_edit_venue',
                    id: id,
                    venue_type: newType,
                    venue_name: name,
                    venue_address: address,
                    parent_id: parentId
                }, function(resp) {
                    if (!resp || !resp.success) {
                        alert(resp?.data?.message || '<?php esc_attr_e('Failed to update venue', 'digical'); ?>');
                        return;
                    }
                    loadVenues();
                });
            });
        });
    }

    /**
     * Bind masonry card event handlers
     */
    function bindMasonryHandlers() {
        $('.chk_masonry').off('change').on('change', function() {
            updateBulkBar();
        });

        $('#masonry_view .venue-card-footer .btn-del').off('click').on('click', function() {
            if (!confirm('<?php esc_attr_e('Are you sure? This cannot be undone.', 'digical'); ?>')) return;

            const id = $(this).closest('.venue-card').data('id');
            $.post(AURL, { action: 'digical_db_delete_venue', id }, function(resp) {
                if (!resp || !resp.success) {
                    alert(resp?.data?.message || '<?php esc_attr_e('Failed to delete venue', 'digical'); ?>');
                    return;
                }
                loadVenues();
            });
        });

        $('#masonry_view .venue-card-footer .btn-edit').off('click').on('click', function() {
            const id = $(this).closest('.venue-card').data('id');
            openEditModal(id);
        });
    }

    /**
     * Update bulk action bar visibility
     */
    function updateBulkBar() {
        const tableChecked = $('#venues_table .chk_row:checked').length;
        const masonryChecked = $('.chk_masonry:checked').length;
        const totalChecked = tableChecked + masonryChecked;
        $('#bulk_delete').toggleClass('show', totalChecked >= 1);
    }

    /**
     * Bulk delete handler
     */
    $('#bulk_delete').on('click', function() {
        const tableIds = $('#venues_table .chk_row:checked').closest('tr').map(function() {
            return $(this).data('id');
        }).get();

        const masonryIds = $('.chk_masonry:checked').map(function() {
            return $(this).data('id');
        }).get();

        const ids = [...tableIds, ...masonryIds];

        if (ids.length < 1) {
            alert('<?php esc_attr_e('Please select at least one venue', 'digical'); ?>');
            return;
        }

        if (!confirm('<?php esc_attr_e('Delete selected venues? This cannot be undone.', 'digical'); ?>')) return;

        $.post(AURL, { action: 'digical_db_delete_venues', ids: ids }, function(resp) {
            if (!resp || !resp.success) {
                alert(resp?.data?.message || '<?php esc_attr_e('Failed to delete venues', 'digical'); ?>');
                return;
            }
            loadVenues();
        });
    });

    /**
     * Utility: Escape HTML special characters
     */
    function escapeHtml(s) {
        return (s || '').replace(/[&<>"']/g, m => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[m]));
    }

    /**
     * Utility: Escape for HTML attributes
     */
    function escAttr(s) {
        return escapeHtml(s);
    }

    // Initialize on ready
    $(document).ready(function() {
        loadVenues();
        const savedView = localStorage.getItem('digical_venue_view') || 'table';
        setView(savedView);
    });

})(jQuery);
</script>