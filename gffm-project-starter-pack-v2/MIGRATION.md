# Migration Guide (Working)

1. Install the plugin ZIP on **staging**.
2. Activate. Click **Run Database Setup** banner once.
3. Go to **Market Manager → Settings** and configure:
   - General
   - Seasons & Dates (create season; confirm Saturdays expand into Dates)
   - Integrations → OpenAI / Square (store keys only)
4. Create a **Vendor Portal** page with `[gffm_portal]`.
5. Open a **Vendor** post → **Vendor Portal** box → **Send Magic Link** → test access.

Rollback: deactivate plugin, restore DB backup/snapshot, remove custom tables (optional).
