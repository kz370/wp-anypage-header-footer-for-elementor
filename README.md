# Template Manager - Custom WordPress Templates Plugin

Template Manager lets you create and manage `template-cu-*` templates from one compact settings page, with Elementor header/footer integration and usage visibility.

## Highlights

- Single admin page for create, edit, delete, and list
- Modal-based create/edit flow
- Two storage modes:
  - Real File (written to active theme directory)
  - Virtual (DB) (stored in plugin option)
- Live duplicate name validation in the create/edit modal
- Searchable header and footer selectors
- Template usage count with usage details modal
- Usage modal supports mixed content types (pages, posts, CPTs)
- Template priority handling: Database > Theme > Plugin

## Why Real File Storage Matters

Choosing **Real File** stores generated template files inside your active theme directory. This is useful because uninstalling the plugin will not remove those theme files, so your template files remain available.

## Installation

1. Copy the `template-manager` folder to:
   `wp-content/plugins/template-manager/`
2. Activate **Template Manager - Custom Templates** in WordPress Admin.
3. Open **Templates** in the WordPress admin menu.

## Usage

### Create a template

1. Go to **Templates**.
2. Click **Add Template**.
3. Enter template name, select header/footer, and choose storage type.
4. Save.

### Edit or delete

- Use action icons in the templates table.
- Edit opens in the same modal.
- Delete removes DB record or generated files depending on source.

### Check where a template is used

- Click the **Used By** badge.
- A modal shows all matching content using that template.
- Filter by content type (All, Page, Post, CPT labels).

## Header/Footer Fallback Safety

Generated templates include fallback behavior:
- If a configured header/footer ID is invalid or unavailable, they fall back to default theme header/footer.
- This prevents blank output or broken render from invalid IDs.

## File Structure

```text
template-manager/
├── template-manager.php
├── includes/
│   ├── class-tm-loader.php
│   ├── class-tm-template-manager.php
│   └── functions.php
├── admin/
│   ├── css/
│   │   └── admin.css
│   └── pages/
│       └── create-template.php
├── templates/
│   └── template-cu-*.php
└── README.md
```

## Requirements

- WordPress 5.0+
- PHP 7.2+
- Elementor (optional, only needed for Elementor content rendering)

## Notes

- The plugin registers and resolves templates with the `template-cu-` prefix.
- Template usage counts are based on `_wp_page_template` matches across public post types.

## License

GNU General Public License v2 or later
