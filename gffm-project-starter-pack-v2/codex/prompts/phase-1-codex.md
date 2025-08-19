## Codex Prompt — Phase‑1 Foundations (short)

You are a senior WordPress plugin engineer. Use the repo contents and implement Phase‑1:

SCOPE
- Settings tabs (+ save handlers) per `docs/SETTINGS-BLUEPRINT.md`
- 7‑step Setup Wizard (General → Seasons → Booths → Applications → Payments → Comms → Map)
- Seasons CRUD + Saturdays expansion into `wp_gffm_dates`; Visitor Vendor toggle with capacity/fee
- Data Access Layer for custom tables (prepared statements; no direct $wpdb in UI)
- Vendor Portal read‑only tabs (Profile/Schedule/Booth/Invoices/Docs) behind magic‑link token
- Compatibility with existing `vendor` CPT + ACF/meta mapping
- Keep manual **Run Database Setup** button flow

DELIVERABLES
- Plugin ZIP `gffm-market-manager-Phase1.zip`
- MIGRATION.md, TESTPLAN.md, CHANGELOG.txt
- Diff summary of files added/changed

CHECKS
- Activates cleanly; DB setup banner still works
- Settings/Wizard save & persist
- Saturdays expanded correctly; exceptions supported
- Portal shows read‑only tabs with valid token; errors on invalid/expired
- Nonces + `gffm_manage` caps on all postbacks
- Accessible labels/descriptions; translation-ready
