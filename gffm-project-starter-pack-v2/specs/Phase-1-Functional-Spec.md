# Phase‑1 Functional Spec

Goals: Settings + Setup Wizard (real forms), Seasons/Dates UI with Saturdays expansion, DAL, Portal read‑only tabs, CPT compatibility.

Deliverables:
- Settings: tabs save/restore options.
- Wizard: 7 steps; save state; skip allowed.
- Seasons: create, time window, exceptions; expand Saturdays into `wp_gffm_dates`.
- Visitor Vendor: boolean + capacity + fee at season.
- DAL: table access with prepared statements; no direct $wpdb in UI.
- Portal: Profile/Schedule/Booth/Invoices/Docs (read‑only); token auth.
- Vendor CPT compatibility; ACF/meta mapping.
