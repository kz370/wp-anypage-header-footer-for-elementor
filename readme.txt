=== AnyPage Header Footer for Elementor ===
Contributors: kz370
Tags: elementor, header, footer, templates
Requires at least: 5.0
Tested up to: 6.9
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Create and manage custom page templates with Elementor header and footer support from a single admin page.

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

1. Copy the `anypage-header-footer-for-elementor` folder to:
   `wp-content/plugins/anypage-header-footer-for-elementor/`
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
anypage-header-footer-for-elementor/
├── anypage-header-footer-for-elementor.php
├── includes/
│   ├── class-tm-loader.php
│   ├── class-tm-anypage-header-footer-for-elementor.php
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

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA.
