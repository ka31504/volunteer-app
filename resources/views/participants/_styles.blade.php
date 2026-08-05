@push('styles')
<style>
/* ── PARTICIPANTS MODULE STYLES ─────────────────────────────── */

/* Page header */
.p-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 24px;
}
.p-header-title { font-size: 20px; font-weight: 600; color: var(--ink); line-height: 1.2; }
.p-header-sub   { font-size: 13px; color: var(--ink-mute); margin-top: 4px; }
.p-breadcrumb   { font-size: 13px; color: var(--ink-mute); }
.p-breadcrumb a { color: var(--ink-mute); text-decoration: none; }
.p-breadcrumb a:hover { color: var(--ink); }

/* Buttons */
.p-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 500; padding: 8px 16px;
    border-radius: var(--r-sm); cursor: pointer;
    text-decoration: none; border: none; transition: background .15s, border-color .15s;
    white-space: nowrap;
}
.p-btn-primary  { background: var(--primary); color: var(--ink); }
.p-btn-primary:hover { background: var(--primary-deep); }
.p-btn-outline  { background: var(--canvas); color: var(--ink); border: 1px solid var(--hairline); }
.p-btn-outline:hover { border-color: var(--ink); }
.p-btn-ghost    { background: transparent; color: var(--ink-mute); border: 1px solid var(--hairline); }
.p-btn-ghost:hover { color: var(--ink); border-color: var(--ink-mute); }
.p-btn-danger   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.p-btn-danger:hover { background: #fee2e2; }
.p-btn-icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; padding: 0;
    border-radius: var(--r-sm); border: 1px solid var(--hairline);
    background: var(--canvas); cursor: pointer; color: var(--ink-mute);
    text-decoration: none; transition: border-color .15s, color .15s;
}
.p-btn-icon:hover { border-color: var(--ink-mute); color: var(--ink); }
.p-btn-icon.danger:hover { border-color: #fca5a5; color: #dc2626; background: #fef2f2; }
.p-btn-sm { padding: 5px 10px; font-size: 12px; }

/* Alert flash */
.p-alert {
    padding: 12px 16px; border-radius: var(--r-md); font-size: 13px;
    margin-bottom: 20px; border: 1px solid;
}
.p-alert-success { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
.p-alert-error   { background: #fef2f2; color: #991b1b; border-color: #fecaca; }

/* Card */
.p-card {
    background: var(--canvas);
    border: 1px solid var(--hairline-cool);
    border-radius: var(--r-lg);
    overflow: hidden;
}
.p-card-body   { padding: 20px; }
.p-card-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--hairline-cool);
    font-size: 14px; font-weight: 500; color: var(--ink);
}
.p-card-footer {
    padding: 14px 20px;
    border-top: 1px solid var(--hairline-cool);
    background: var(--canvas-soft);
}

/* Filter bar */
.p-filter {
    display: flex; align-items: flex-end; gap: 12px;
    flex-wrap: wrap; padding: 16px 20px;
    border-bottom: 1px solid var(--hairline-cool);
    background: var(--canvas-soft);
}
.p-filter-group { display: flex; flex-direction: column; gap: 5px; }
.p-filter-group.grow { flex: 1; min-width: 180px; }
.p-filter label { font-size: 11px; font-weight: 500; color: var(--ink-mute); text-transform: uppercase; letter-spacing: 0.5px; }
.p-input {
    height: 36px; padding: 0 10px; font-size: 13px;
    border: 1px solid var(--hairline); border-radius: var(--r-sm);
    background: var(--canvas); color: var(--ink); outline: none;
    transition: border-color .15s; font-family: inherit; width: 100%;
}
.p-input:focus { border-color: var(--primary); }
.p-input.error { border-color: #f87171; }

/* Table */
.p-table-wrap { overflow-x: auto; }
.p-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.p-table thead th {
    text-align: left; font-size: 11px; font-weight: 500;
    color: var(--ink-mute); text-transform: uppercase; letter-spacing: 0.5px;
    padding: 10px 16px; border-bottom: 1px solid var(--hairline-cool);
    background: var(--canvas-soft); white-space: nowrap;
}
.p-table tbody tr { border-bottom: 1px solid var(--hairline-cool); transition: background .1s; }
.p-table tbody tr:last-child { border-bottom: none; }
.p-table tbody tr:hover { background: var(--canvas-soft); }
.p-table td { padding: 12px 16px; color: var(--ink); vertical-align: middle; }
.p-table .mute { color: var(--ink-mute); font-size: 12px; margin-top: 2px; }
.p-table .num  { color: var(--ink-mute); font-size: 12px; }
.p-actions { display: flex; align-items: center; gap: 6px; }
.p-empty { text-align: center; padding: 48px 20px; color: var(--ink-mute); font-size: 13px; }

/* Badge / Status */
.p-badge {
    display: inline-flex; align-items: center;
    padding: 3px 10px; border-radius: 99px;
    font-size: 11px; font-weight: 500; white-space: nowrap;
}
.p-badge-green  { background: #f0fdf4; color: #166534; }
.p-badge-yellow { background: #fefce8; color: #854d0e; }
.p-badge-red    { background: #fef2f2; color: #991b1b; }
.p-badge-gray   { background: var(--canvas-soft); color: var(--ink-mute); border: 1px solid var(--hairline); }

/* Form layout */
.p-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
@media (max-width: 768px) { .p-form-grid { grid-template-columns: 1fr; } }
.p-form-section { display: flex; flex-direction: column; gap: 16px; }
.p-section-title {
    font-size: 13px; font-weight: 600; color: var(--ink);
    text-transform: uppercase; letter-spacing: 0.5px;
    padding-bottom: 10px; border-bottom: 1px solid var(--hairline-cool);
    margin-bottom: 4px;
}
.p-field { display: flex; flex-direction: column; gap: 5px; }
.p-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.p-label {
    font-size: 12px; font-weight: 500; color: var(--ink-mute);
}
.p-label.req::after { content: ' *'; color: #dc2626; }
.p-textarea {
    padding: 8px 10px; font-size: 13px;
    border: 1px solid var(--hairline); border-radius: var(--r-sm);
    background: var(--canvas); color: var(--ink); outline: none;
    transition: border-color .15s; font-family: inherit; resize: vertical; width: 100%;
}
.p-textarea:focus { border-color: var(--primary); }
.p-input-wrap { position: relative; display: flex; align-items: center; }
.p-input-addon {
    position: absolute; right: 10px; font-size: 12px; color: var(--ink-mute); pointer-events: none;
}
.p-error { font-size: 12px; color: #dc2626; margin-top: 2px; }
.p-form-actions {
    display: flex; align-items: center; justify-content: flex-end; gap: 10px;
    margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--hairline-cool);
}

/* Detail / show */
.p-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
@media (max-width: 768px) { .p-detail-grid { grid-template-columns: 1fr; } }
.p-dl { display: flex; flex-direction: column; gap: 0; }
.p-dl-row { display: grid; grid-template-columns: 140px 1fr; gap: 8px; padding: 11px 0; border-bottom: 1px solid var(--hairline-cool); font-size: 13px; }
.p-dl-row:last-child { border-bottom: none; }
.p-dt { color: var(--ink-mute); font-size: 12px; font-weight: 500; padding-top: 1px; }
.p-dd { color: var(--ink); }
.p-stat-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: var(--hairline-cool); border: 1px solid var(--hairline-cool); border-radius: var(--r-md); overflow: hidden; margin-bottom: 20px; }
.p-stat { background: var(--canvas); padding: 16px 20px; }
.p-stat-num { font-size: 22px; font-weight: 600; color: var(--ink); }
.p-stat-label { font-size: 12px; color: var(--ink-mute); margin-top: 2px; }

/* Pagination links (override Laravel default) */
.p-pagination { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
</style>
@endpush
