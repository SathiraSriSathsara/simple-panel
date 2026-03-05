# Quick Start Guide - phpMyAdmin Integration

## What Changed?
Your custom **Database Management** page has been replaced with **phpMyAdmin** - a professional, industry-standard database admin tool - styled to match your panel's theme exactly.

## Where is It?
**Navigation**: Click "Databases" in your panel → phpMyAdmin loads

**Direct Access**: 
- `your-panel-url.com/vendor/phpmyadmin/index.php`

## How to Use It?

### First Time Setup
1. Go to your panel's **Databases** page
2. phpMyAdmin interface loads with your theme colors
3. Log in (uses your database credentials)

### Common Tasks

#### Browse & Query Data
```
Left sidebar → Select database → Select table → Browse tab
```

#### Create New Database
```
Left sidebar → Click "+" icon or use Query tab
Run: CREATE DATABASE my_new_db;
```

#### Export Database Backup
```
Select database → Export tab → Choose format (SQL/CSV/JSON) → Go
```

#### Import Data
```
Select database → Import tab → Choose file → Go
```

#### Manage Users
```
Top navigation → User Accounts → Add/Modify users
```

## Configuration Quick Reference

**Config File**: `vendor/phpmyadmin/config.inc.php`

Change these for different servers:
```php
$cfg['Servers'][1]['host'] = 'localhost';         // Server address
$cfg['Servers'][1]['port'] = 3306;                // Port number
$cfg['Servers'][1]['auth_type'] = 'cookie';       // Auth method
```

## Files Location

| File | Purpose |
|------|---------|
| `vendor/phpmyadmin/` | All phpMyAdmin application files |
| `vendor/phpmyadmin/config.inc.php` | Configuration & server settings |
| `pages/databases.php` | Panel integration wrapper |
| `pages/databases-backup.php` | Your original custom code (backup) |
| `PHPMYADMIN_SETUP.md` | Full setup documentation |

## Security Reminders

⚠️ **Important**:
1. Change `blowfish_secret` in config.inc.php
2. Use strong database passwords
3. Restrict IP access if possible
4. Always use HTTPS in production
5. Keep phpMyAdmin updated

## Restore Original Code?

Your original database management interface is safely backed up:
```
pages/databases-backup.php
```

To restore:
1. Delete current: `pages/databases.php`
2. Rename backup: `databases-backup.php` → `databases.php`
3. Refresh page

## Theme Customization

All colors in `pages/databases.php`:
```css
--primary-color: #1f3a68;      /* Main blue */
--primary-light: #2d538f;      /* Light blue */
--danger-color: #d94b4b;       /* Red for delete buttons */
--success-color: #20784a;      /* Green for success messages */
```

## Keyboard Shortcuts

In phpMyAdmin SQL editor (Ctrl/Cmd + key):
- `K` → Execute query
- `L` → Clear query
- `B` → Make bold in SQL
- `I` → Make italic in SQL

## Get Help

- Full docs: See `PHPMYADMIN_SETUP.md`
- phpMyAdmin help: Right-click any element
- Official: https://www.phpmyadmin.net/

---

**Ready to go!** Your database management is now better than ever. 🚀
