<?php
if (!defined('ABSPATH')) exit;

global $wpdb;

/**
 * 1) Get rows safely (use helper if present, or direct DB query)
 */
if (function_exists('digical_days_all_rows')) {
    $rows = digical_days_all_rows();
} else {
    $table = $wpdb->prefix . 'digical_days';
    // Try to query (table may or may not exist; empty result is fine)
    $rows = $wpdb->get_results("
        SELECT id, date, start_time, end_time
        FROM `$table`
        ORDER BY CONCAT(SUBSTR(date,5,4),SUBSTR(date,3,2),SUBSTR(date,1,2)) ASC, start_time ASC
    ", ARRAY_A);
    if (!is_array($rows)) $rows = [];
}

/**
 * 2) Local helpers
 */
function digical_fmt_date_disp_local($d8){
    $d = preg_replace('/\D+/', '', (string)$d8);
    if (preg_match('/^(\d{2})(\d{2})(\d{4})$/', $d, $m)) {
        return "{$m[1]}.{$m[2]}.{$m[3]}";
    }
    return esc_html($d8);
}
function digical_weekday_name_local($d8){
    $d = preg_replace('/\D+/', '', (string)$d8);
    if (!preg_match('/^(\d{2})(\d{2})(\d{4})$/', $d, $m)) return '';
    $ts = strtotime("{$m[3]}-{$m[2]}-{$m[1]} 00:00:00");
    return $ts ? wp_date('l', $ts) : '';
}

$nonce          = wp_create_nonce('digical_nonce');
$ajaxurl        = admin_url('admin-ajax.php');
$day_manage_base= admin_url('admin.php?page=digical-day-');
?>
<style>
/* ===== Days Management - Modern Professional Styling ===== */

.digical-days-container {
    background: transparent;
    padding: 0;
}

#day-form {
    background: linear-gradient(135deg, #f8fafc 0%, #f0f4f8 100%);
    border: 1px solid #e0e6ed;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 30px;
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    align-items: flex-end;
}

#day-form input[type="text"] {
    padding: 12px 16px;
    font-size: 14px;
    border: 2px solid #d1d8e0;
    border-radius: 8px;
    box-sizing: border-box;
    transition: all 0.3s ease;
    flex: 1;
    min-width: 160px;
    font-weight: 600;
    color: #2c3e50;
    background: white;
}

#day-form input[type="text"]:focus {
    outline: none;
    border-color: #2271b1;
    box-shadow: 0 0 0 3px rgba(34, 113, 177, 0.1);
}

#day-form input[type="text"]::placeholder {
    color: #7f8c8d;
    font-weight: 500;
}

#save_day_btn {
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
}

#save_day_btn:hover {
    box-shadow: 0 4px 16px rgba(34, 113, 177, 0.3);
    transform: translateY(-2px);
}

.digical-bar {
    display: flex;
    align-items: center;
    gap: 16px;
    margin: 20px 0 24px 0;
}

.digical-bar .bulk-btn {
    display: none;
}

.digical-bar .bulk-btn.show {
    display: inline-block;
}

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
}

.digical-bulk:hover {
    box-shadow: 0 4px 16px rgba(231, 76, 60, 0.3);
    transform: translateY(-2px);
}

/* Table Styles */
.digical-custom-table {
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

.digical-custom-table th {
    background: linear-gradient(135deg, #2271b1 0%, #1a5a8e 100%);
    color: #fff;
    text-align: left;
    font-weight: 700;
    font-size: 12px;
    border: none;
    padding: 14px 16px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.digical-custom-table th:first-child {
    padding-left: 16px;
}

.digical-custom-table th:last-child {
    text-align: center;
}

.digical-custom-table td {
    background: #fff;
    padding: 14px 16px;
    border: none;
    border-bottom: 1px solid #ecf0f1;
    vertical-align: middle;
    font-size: 14px;
    color: #2c3e50;
    font-weight: 500;
}

.digical-custom-table tbody tr:last-child td {
    border-bottom: none;
}

.digical-custom-table tbody tr:hover td {
    background: #f8fafc;
}

.digical-custom-table input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #2271b1;
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

.digical-btn-action.delete {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    box-shadow: 0 2px 6px rgba(231, 76, 60, 0.15);
}

.digical-btn-action.delete:hover {
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.25);
}

.digical-btn-config {
    background: linear-gradient(135deg, #27ae60 0%, #229954 100%) !important;
    color: #fff !important;
    border: none;
    border-radius: 6px;
    padding: 8px 12px !important;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    display: inline-block !important;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(39, 174, 96, 0.15);
    text-align: center;
    margin-right: 0;
    line-height: 1.4;
    white-space: nowrap;
}

.digical-btn-config:hover {
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.25);
    transform: translateY(-2px);
    color: #fff !important;
}

/* Edit Inline Inputs */
input.date-edit {
    width: 110px;
    font-size: 14px;
    padding: 8px 12px;
    box-sizing: border-box;
    border: 2px solid #2271b1;
    border-radius: 6px;
    font-weight: 600;
    background: white;
}

input.time-edit {
    width: 65px;
    font-size: 14px;
    padding: 8px 12px;
    box-sizing: border-box;
    border: 2px solid #2271b1;
    border-radius: 6px;
    font-weight: 600;
    background: white;
}

/* Responsive */
@media (max-width: 768px) {
    #day-form {
        flex-direction: column;
        gap: 12px;
    }

    #day-form input[type="text"] {
        width: 100%;
        min-width: unset;
    }

    #save_day_btn {
        width: 100%;
    }

    .digical-btn-action,
    .digical-btn-config {
        padding: 6px 12px;
        font-size: 11px;
        margin-right: 4px;
    }

    .digical-custom-table {
        font-size: 12px;
    }

    .digical-custom-table td {
        padding: 12px 10px;
    }
}

/* View Toggle Buttons */
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

/* Masonry View */
.digical-days-masonry {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.digical-days-masonry:not(.show) {
    display: none;
}

.digical-days-masonry.show {
    display: grid;
}

.digical-day-card {
    background: white;
    border: 1px solid #e0e6ed;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
}

.digical-day-card:hover {
    box-shadow: 0 6px 16px rgba(34, 113, 177, 0.15);
    transform: translateY(-4px);
    border-color: #2271b1;
}

.digical-day-card-date {
    font-size: 18px;
    font-weight: 800;
    color: #2271b1;
    margin-bottom: 8px;
}

.digical-day-card-day {
    font-size: 12px;
    color: #7f8c8d;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-bottom: 16px;
}

.digical-day-card-times {
    background: linear-gradient(135deg, #f8fafc 0%, #f0f4f8 100%);
    border-left: 3px solid #2271b1;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 16px;
    font-size: 13px;
    font-weight: 600;
    color: #2c3e50;
}

.digical-day-card-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.digical-day-card-actions .digical-btn-action,
.digical-day-card-actions .digical-btn-config {
    width: 100% !important;
    text-align: center !important;
    margin-right: 0 !important;
    padding: 8px 14px !important;
    box-sizing: border-box !important;
    display: block !important;
    line-height: 1.5;
}

.digical-day-card-actions .digical-btn-config {
    width: 100% !important;
    min-height: 36px;
    display: flex !important;
    align-items: center;
    justify-content: center;
}
</style>

<h2 style="margin-bottom: 30px; font-size: 28px; font-weight: 700; color: #1a202c; letter-spacing: -0.5px;">Manage Days</h2>

<form id="day-form" autocomplete="off">
  <input type="text" id="day_date" name="day_date" required placeholder="Date (e.g. 01102025)">
  <input type="text" id="start_time" name="start_time" required placeholder="Start Time (e.g. 8)">
  <input type="text" id="end_time" name="end_time" required placeholder="End Time (e.g. 10)">
  <button type="submit" id="save_day_btn" class="digical-btn-save">Save Day</button>
</form>

<div class="digical-bar">
  <button type="button" id="bulk-delete" class="digical-bulk bulk-btn">Delete selected</button>
</div>

<!-- View Toggle Buttons -->
<div class="digical-view-toggle">
  <button type="button" id="view-table-btn" class="digical-view-btn">📊 Table View</button>
  <button type="button" id="view-masonry-btn" class="digical-view-btn active">📋 Masonry View</button>
</div>

<!-- Table View -->
<table class="digical-custom-table" id="days-table" style="display: none;">
  <thead>
    <tr>
      <th style="width:48px;text-align:center">
        <input type="checkbox" id="chk-all" class="digical-checkbox" aria-label="Select all">
      </th>
      <th>Date</th>
      <th>Day</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
      <tr data-id="<?php echo esc_attr($r['id']); ?>">
        <td><input type="checkbox" class="digical-checkbox row-chk"></td>
        <td><?php echo digical_fmt_date_disp_local($r['date']); ?></td>
        <td><?php echo esc_html(digical_weekday_name_local($r['date'])); ?></td>
        <td><?php echo esc_html($r['start_time']); ?></td>
        <td><?php echo esc_html($r['end_time']); ?></td>
        <td>
          <button type="button" class="digical-btn-action edit">Edit</button>
          <button type="button" class="digical-btn-action delete">Delete</button>
          <a href="<?php echo esc_url($day_manage_base . $r['id']); ?>" class="digical-btn-config">Configure Day</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Masonry View -->
<div class="digical-days-masonry show" id="days-masonry">
  <?php foreach ($rows as $r): ?>
    <?php 
      $date = $r['date'] ?? '';
      $start = $r['start_time'] ?? '';
      $end = $r['end_time'] ?? '';
      $day_name = digical_weekday_name_local($date);
      $formatted_date = digical_fmt_date_disp_local($date);
    ?>
    <div class="digical-day-card" data-id="<?php echo esc_attr($r['id']); ?>">
      <div class="digical-day-card-date"><?php echo esc_html($formatted_date); ?></div>
      <div class="digical-day-card-day"><?php echo esc_html($day_name); ?></div>
      <div class="digical-day-card-times">
        ⏰ <?php echo esc_html($start); ?> - <?php echo esc_html($end); ?>
      </div>
      <div class="digical-day-card-actions">
        <button type="button" class="digical-btn-action edit">Edit</button>
        <button type="button" class="digical-btn-action delete">Delete</button>
        <a href="<?php echo esc_url($day_manage_base . $r['id']); ?>" class="digical-btn-config">Configure Day</a>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Edit Modal -->
<div id="edit-modal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;justify-content:center;align-items:center;flex-direction:column;">
  <div style="background:white;padding:30px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.3);max-width:400px;width:90%;">
    <h3 style="margin-top:0;color:#2c3e50;font-size:18px;margin-bottom:20px;">Edit Day</h3>
    
    <div style="margin-bottom:16px;">
      <label style="display:block;font-weight:700;color:#2c3e50;margin-bottom:6px;font-size:14px;">Date (DDMMYYYY)</label>
      <input type="text" id="edit-date" style="width:100%;padding:10px 12px;border:2px solid #d1d8e0;border-radius:8px;font-size:14px;box-sizing:border-box;">
    </div>
    
    <div style="margin-bottom:16px;">
      <label style="display:block;font-weight:700;color:#2c3e50;margin-bottom:6px;font-size:14px;">Start Time</label>
      <input type="text" id="edit-start" style="width:100%;padding:10px 12px;border:2px solid #d1d8e0;border-radius:8px;font-size:14px;box-sizing:border-box;">
    </div>
    
    <div style="margin-bottom:20px;">
      <label style="display:block;font-weight:700;color:#2c3e50;margin-bottom:6px;font-size:14px;">End Time</label>
      <input type="text" id="edit-end" style="width:100%;padding:10px 12px;border:2px solid #d1d8e0;border-radius:8px;font-size:14px;box-sizing:border-box;">
    </div>
    
    <div style="display:flex;gap:12px;justify-content:flex-end;">
      <button type="button" id="edit-cancel" style="background:#bdc3c7;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-weight:700;cursor:pointer;transition:all 0.3s ease;">Cancel</button>
      <button type="button" id="edit-save" style="background:linear-gradient(135deg, #2271b1 0%, #1a5a8e 100%);color:#fff;border:none;border-radius:8px;padding:10px 24px;font-weight:700;cursor:pointer;transition:all 0.3s ease;box-shadow:0 2px 8px rgba(34, 113, 177, 0.2);">Save</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const ajaxurl   = <?php echo json_encode($ajaxurl); ?>;
  const nonce     = <?php echo json_encode($nonce); ?>;
  const managePre = <?php echo json_encode($day_manage_base); ?>;

  const tbody   = document.querySelector('#days-table tbody');
  const form    = document.getElementById('day-form');
  const bulkBtn = document.getElementById('bulk-delete');
  const chkAll  = document.getElementById('chk-all');

  // Helpers
  const fmtDate = s => String(s).replace(/^(\d{2})(\d{2})(\d{4})$/, '$1.$2.$3');
  const fmtTime = t => {
    const x = String(t).trim();
    if (/^\d{1,2}$/.test(x)) return x.padStart(2,'0') + ':00';
    if (/^\d{1,2}:\d{2}$/.test(x)) { const [h,m]=x.split(':'); return h.padStart(2,'0') + ':' + m; }
    return x;
  };
  function validateDate(ddmmyyyy){
    const clean = String(ddmmyyyy).replace(/\./g,'');
    if (!/^\d{8}$/.test(clean)) return false;
    const d = parseInt(clean.slice(0,2),10);
    const m = parseInt(clean.slice(2,4),10);
    const y = parseInt(clean.slice(4,8),10);
    const dt = new Date(y, m-1, d);
    return dt.getFullYear()===y && (dt.getMonth()+1)===m && dt.getDate()===d;
  }
  function weekdayName(dateStr){
    const p = String(dateStr).split('.');
    const d = new Date(`${p[2]}-${p[1]}-${p[0]}T00:00:00`);
    return isNaN(d) ? '' : d.toLocaleDateString(undefined, { weekday: 'long' });
  }
  const extractDays = j => (j && j.success && (Array.isArray(j.days) ? j.days : (j.data && Array.isArray(j.data.days) ? j.data.days : null)));

  function selectedIDs(){
    return [...tbody.querySelectorAll('.row-chk:checked')].map(ch => ch.closest('tr').getAttribute('data-id'));
  }
  function updateBulkButton(){
    const count = selectedIDs().length;
    bulkBtn.classList.toggle('show', count >= 2);
    const all = tbody.querySelectorAll('.row-chk').length;
    chkAll.checked = (all>0 && count === all);
  }

  function rebuild(days){
    if (!Array.isArray(days)) return;
    
    // Rebuild table
    const frag = document.createDocumentFragment();
    days.forEach(row=>{
      const tr=document.createElement('tr');
      tr.setAttribute('data-id', row.id);
      const dclean = String(row.date).replace(/\D/g,'');
      const ddisp  = fmtDate(dclean);
      tr.innerHTML = `
        <td><input type="checkbox" class="digical-checkbox row-chk"></td>
        <td>${ddisp}</td>
        <td>${weekdayName(ddisp)}</td>
        <td>${fmtTime(row.start_time)}</td>
        <td>${fmtTime(row.end_time)}</td>
        <td>
          <button type="button" class="digical-btn-action edit">Edit</button>
          <button type="button" class="digical-btn-action delete">Delete</button>
          <a href="${managePre}${row.id}" class="digical-btn-config">Configure Day</a>
        </td>`;
      frag.appendChild(tr);
    });
    tbody.innerHTML = '';
    tbody.appendChild(frag);
    chkAll.checked = false;
    updateBulkButton();

    // Rebuild masonry
    const masonryContainer = document.getElementById('days-masonry');
    if (masonryContainer) {
      masonryContainer.innerHTML = '';
      days.forEach(row => {
        const dclean = String(row.date).replace(/\D/g,'');
        const ddisp = fmtDate(dclean);
        const dayName = weekdayName(ddisp);
        
        const card = document.createElement('div');
        card.className = 'digical-day-card';
        card.setAttribute('data-id', row.id);
        card.innerHTML = `
          <div class="digical-day-card-date">${ddisp}</div>
          <div class="digical-day-card-day">${dayName}</div>
          <div class="digical-day-card-times">
            ⏰ ${fmtTime(row.start_time)} - ${fmtTime(row.end_time)}
          </div>
          <div class="digical-day-card-actions">
            <button type="button" class="digical-btn-action edit">Edit</button>
            <button type="button" class="digical-btn-action delete">Delete</button>
            <a href="${managePre}${row.id}" class="digical-btn-config">Configure Day</a>
          </div>
        `;
        masonryContainer.appendChild(card);
      });
    }

    // Refresh sidebar submenu if present
    const ul = document.getElementById('digical-days-list');
    if (ul) {
      ul.innerHTML = '';
      const sorted = [...days].sort((a,b)=>{
        const ay = String(a.date).slice(4), am = String(a.date).slice(2,4), ad = String(a.date).slice(0,2);
        const by = String(b.date).slice(4), bm = String(b.date).slice(2,4), bd = String(b.date).slice(0,2);
        return (ay+am+ad).localeCompare(by+bm+bd);
      });
      sorted.forEach(d=>{
        const label = fmtDate(String(d.date).replace(/\D/g,''));
        const li = document.createElement('li');
        const a  = document.createElement('a');
        a.className = 'digical-link';
        a.href = managePre + d.id;
        a.textContent = label;
        li.appendChild(a);
        ul.appendChild(li);
      });
    }
  }

  async function post(body){
    try{
      const res = await fetch(ajaxurl, {
        method:'POST',
        credentials:'same-origin',
        headers:{'Content-Type':'application/x-www-form-urlencoded','Cache-Control':'no-store'},
        body:(body instanceof URLSearchParams) ? body.toString() : new URLSearchParams(body).toString()
      });
      const txt = await res.text();
      try { return JSON.parse(txt); } catch(e){ console.warn('Non-JSON response:', txt); return null; }
    }catch(err){ console.error(err); return null; }
  }

  // Select-all / checkbox changes
  chkAll.addEventListener('change', ()=>{
    tbody.querySelectorAll('.row-chk').forEach(ch => ch.checked = chkAll.checked);
    updateBulkButton();
  });
  tbody.addEventListener('change', e=>{
    if (e.target.classList.contains('row-chk')) updateBulkButton();
  });

  // Bulk delete (no confirm)
  bulkBtn.addEventListener('click', ()=>{
    const ids = selectedIDs();
    if (ids.length < 2) return;
    const params = new URLSearchParams();
    params.append('action','digical_db_delete_days');
    params.append('nonce', nonce);
    ids.forEach(id => params.append('ids[]', id));
    post(params).then(j=>{
      const days = extractDays(j);
      if (days) rebuild(days);
      else if (j && j.data && j.data.message) alert(j.data.message);
    });
  });

  // Row actions
  tbody.addEventListener('click', function(e){
    const btn = e.target.closest('button'); if (!btn) return;
    const tr  = btn.closest('tr');
    const id  = tr.getAttribute('data-id');

    if (btn.classList.contains('delete')) {
      post({action:'digical_db_delete_day', id, nonce}).then(j=>{
        const days = extractDays(j);
        if (days) rebuild(days);
        else if (j && j.data && j.data.message) alert(j.data.message);
      });
      return;
    }

    if (btn.classList.contains('edit')) {
      const td = tr.querySelectorAll('td');
      const d  = td[1].textContent.trim();
      const s  = td[3].textContent.trim();
      const e2 = td[4].textContent.trim();
      td[1].innerHTML = `<input class="date-edit" value="${d}">`;
      // Day column (td[2]) will be recomputed on rebuild
      td[3].innerHTML = `<input class="time-edit" value="${s}">`;
      td[4].innerHTML = `<input class="time-edit" value="${e2}">`;
      btn.textContent = 'Save';
      btn.classList.remove('edit');
      btn.classList.add('save');
      return;
    }

    if (btn.classList.contains('save')) {
      const td   = tr.querySelectorAll('td');
      const newD = td[1].querySelector('input').value.replace(/\./g,'');
      const newS = td[3].querySelector('input').value;
      const newE = td[4].querySelector('input').value;

      if (!validateDate(newD)) { alert('Invalid date'); return; }

      post({
        action:'digical_db_edit_day',
        id, nonce,
        day_date:newD,
        start_time:newS,
        end_time:newE
      }).then(j=>{
        const days = extractDays(j);
        if (days) rebuild(days);
        else if (j && j.data && j.data.message) alert(j.data.message);
      });
      return;
    }
  });

  // View Toggle
  const tableViewBtn = document.getElementById('view-table-btn');
  const masonryViewBtn = document.getElementById('view-masonry-btn');
  const tableView = document.getElementById('days-table');
  const masonryView = document.getElementById('days-masonry');

  // Masonry view event handlers
  masonryView.addEventListener('click', function(e){
    const btn = e.target.closest('button');
    if (!btn) return;
    
    const card = btn.closest('.digical-day-card');
    const id = card.getAttribute('data-id');

    if (btn.classList.contains('delete')) {
      post({action:'digical_db_delete_day', id, nonce}).then(j=>{
        const days = extractDays(j);
        if (days) rebuild(days);
        else if (j && j.data && j.data.message) alert(j.data.message);
      });
    } else if (btn.classList.contains('edit')) {
      const card = btn.closest('.digical-day-card');
      const dateDisp = card.querySelector('.digical-day-card-date').textContent.trim();
      const timeSpan = card.querySelector('.digical-day-card-times').textContent.trim();
      const match = timeSpan.match(/(\d{1,2}:\d{2}|\d{1,2})\s*-\s*(\d{1,2}:\d{2}|\d{1,2})/);
      if (!match) return;
      
      const [, st, et] = match;
      const ddmmyyyy = dateDisp.replace(/\./g,'');
      
      // Open edit modal
      document.getElementById('edit-date').value = ddmmyyyy;
      document.getElementById('edit-start').value = st;
      document.getElementById('edit-end').value = et;
      
      const modal = document.getElementById('edit-modal');
      modal.style.display = 'flex';
      
      // Store the ID for saving
      modal.setAttribute('data-id', id);
      
      // Focus on date field
      document.getElementById('edit-date').focus();
    }
  });

  tableViewBtn.addEventListener('click', () => {
    tableViewBtn.classList.add('active');
    masonryViewBtn.classList.remove('active');
    tableView.style.display = 'table';
    masonryView.classList.remove('show');
    localStorage.setItem('digical_days_view', 'table');
  });

  masonryViewBtn.addEventListener('click', () => {
    masonryViewBtn.classList.add('active');
    tableViewBtn.classList.remove('active');
    tableView.style.display = 'none';
    masonryView.classList.add('show');
    localStorage.setItem('digical_days_view', 'masonry');
  });

  // Restore saved view preference - default to masonry
  let savedView = localStorage.getItem('digical_days_view');
  if (!savedView) {
    savedView = 'masonry';
    localStorage.setItem('digical_days_view', 'masonry');
  }
  
  if (savedView === 'masonry') {
    masonryViewBtn.classList.add('active');
    tableViewBtn.classList.remove('active');
    tableView.style.display = 'none';
    masonryView.classList.add('show');
  } else {
    tableViewBtn.classList.add('active');
    masonryViewBtn.classList.remove('active');
    tableView.style.display = 'table';
    masonryView.classList.remove('show');
  }

  // Edit Modal Handlers
  const modal = document.getElementById('edit-modal');
  const editCancel = document.getElementById('edit-cancel');
  const editSave = document.getElementById('edit-save');
  
  editCancel.addEventListener('click', () => {
    modal.style.display = 'none';
  });
  
  // Close modal when clicking outside
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });
  
  editSave.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    
    const id = modal.getAttribute('data-id');
    const newDate = document.getElementById('edit-date').value.trim();
    const newStart = document.getElementById('edit-start').value.trim();
    const newEnd = document.getElementById('edit-end').value.trim();
    
    console.log('Save clicked:', {id, newDate, newStart, newEnd});
    
    if (!newDate || !newStart || !newEnd) {
      alert('All fields are required');
      return;
    }
    
    if (!validateDate(newDate)) {
      alert('Invalid date format. Please use DDMMYYYY');
      return;
    }
    
    post({
      action:'digical_db_edit_day',
      id,
      day_date: newDate,
      start_time: newStart,
      end_time: newEnd,
      nonce
    }).then(j=>{
      console.log('Save response:', j);
      const days = extractDays(j);
      if (days) {
        rebuild(days);
        modal.style.display = 'none';
      }
      else if (j && j.data && j.data.message) alert(j.data.message);
    });
  });
  
  // Allow Enter key to save
  document.getElementById('edit-end').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      editSave.click();
    }
  });

  // Create
  form.addEventListener('submit', function(e){
    e.preventDefault();
    if (!validateDate(form.day_date.value)) { alert('Invalid date'); return; }
    post({
      action:'digical_db_add_day',
      nonce,
      day_date: form.day_date.value,
      start_time: form.start_time.value,
      end_time: form.end_time.value
    }).then(j=>{
      const days = extractDays(j);
      if (days) { rebuild(days); form.reset(); form.day_date.focus(); }
      else if (j && j.data && j.data.message) alert(j.data.message);
    });
  });
});
</script>