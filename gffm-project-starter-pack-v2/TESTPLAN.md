# Test Plan (Working)

## Activation & Schema
- Plugin activates without fatal errors.
- Admin banner shows **Run Database Setup**; button runs successfully; banner disappears.

## Settings & Wizard
- All Settings tabs render and save options.
- Setup Wizard steps save; skipping does not break flow.

## Seasons & Dates
- Create "Summer" season; start/end; weekly Saturdays.
- Confirm dates expanded in `wp_gffm_dates`.
- Toggle Visitor Vendor; set capacity; save.

## Portal
- Create `/vendor-portal/` with `[gffm_portal]`.
- Send a magic link to a vendor with an email; link opens portal read‑only tabs.
- Expired/invalid token shows proper error.

## Security
- All postbacks use nonces + `gffm_manage` caps.
- No direct `$wpdb` from UI; DAL only (Phase‑1).
