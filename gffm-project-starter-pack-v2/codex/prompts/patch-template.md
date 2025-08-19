## Codex Patch Prompt (template)

Context: Environment repo already contains the plugin. Apply a focused fix.

TASK
- Describe the specific file/line and what to change, e.g.:
  - Fix PHP parse error in `includes/class-gffm-wizard.php` line 32 (missing semicolon).
  - Add nonce verification in `admin_post` action `gffm_save_season`.
  - Ensure DAL uses prepared statements in `class-gffm-dal.php` method `get_season_dates`.

OUTPUT
- Show unified diff or list of changed files.
- Rebuild plugin ZIP artifact.
