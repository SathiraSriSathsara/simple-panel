# Simple Panel - Frontend Structure

## 📁 Folder Structure

```
frontend/
├── index.php                 # Main entry point (routing)
├── index.html               # Original HTML file (backup)
│
├── config/
│   └── config.php           # Configuration settings
│
├── includes/
│   ├── header.php           # HTML head and opening tags
│   └── footer.php           # JavaScript includes and closing tags
│
├── components/
│   ├── sidebar.php          # Sidebar navigation component
│   ├── topbar.php           # Top bar with page title
│   └── stat-card.php        # Reusable stat card component
│
├── pages/
│   ├── websites.php         # Websites page content
│   ├── nodeapps.php         # Node apps page content
│   ├── revproxy.php         # Reverse proxy page content
│   ├── databases.php        # Databases page content
│   ├── domains.php          # Domains page content
│   └── ssl.php              # SSL/TLS page content
│
└── assets/
    ├── css/
    │   └── style.css        # All styles
    ├── js/
    │   └── main.js          # JavaScript functionality
    └── images/
        └── simple-panel-logo-no-bg-white.png
```

## 🚀 How to Use

### 1. **Start a PHP Server**

Run the following command in the frontend directory:

```bash
php -S localhost:8000
```

Then open your browser and navigate to:
```
http://localhost:8000
```

### 2. **Navigation**

The application uses URL parameters for navigation:

- `http://localhost:8000/` - Default page (Websites)
- `http://localhost:8000/?page=websites` - Websites
- `http://localhost:8000/?page=nodeapps` - Node Apps
- `http://localhost:8000/?page=revproxy` - Reverse Proxy
- `http://localhost:8000/?page=databases` - Databases
- `http://localhost:8000/?page=domains` - Domains
- `http://localhost:8000/?page=ssl` - SSL/TLS

## 📝 File Descriptions

### **index.php**
Main entry point that:
- Loads configuration
- Handles routing via `?page=` parameter
- Includes header, sidebar, content, and footer

### **config/config.php**
Central configuration file containing:
- Site settings (name, version)
- Path definitions
- Available pages list
- Page configurations (title, icon, badge)

### **includes/header.php**
- HTML doctype and head section
- Meta tags
- CSS links (Font Awesome + custom styles)

### **includes/footer.php**
- JavaScript includes
- Closing HTML tags

### **components/sidebar.php**
Reusable sidebar navigation:
- Logo header
- Navigation menu with active state
- Footer with system status

### **components/topbar.php**
Page header component:
- Page title with icon
- System status indicator

### **components/stat-card.php**
Reusable function for stat cards:
```php
render_stat_card('fas fa-globe', 'Total sites', '12');
```

### **pages/*.php**
Individual page content files:
- Each page includes its own stats and content
- Uses the stat-card component
- Clean separation of concerns

## 🔧 Customization

### Adding a New Page

1. **Create page file** in `pages/` folder:
```php
<?php
// pages/newpage.php
require_once COMPONENTS_PATH . '/stat-card.php';

$page_title = 'New Page';
$page_icon = 'fas fa-star';
?>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-icon', 'Label', 'Value');
    ?>
</div>

<div class="content-placeholder">
    <h3>Page Content</h3>
    <p>Your content here...</p>
</div>
```

2. **Update config.php**:
```php
// Add to $available_pages array
$available_pages = [
    // ... existing pages
    'newpage'
];

// Add to $page_config array
$page_config['newpage'] = [
    'title' => 'New Page',
    'icon' => 'fas fa-star',
    'badge' => '5'
];
```

3. **Add to sidebar** in `components/sidebar.php`:
```php
<li class="nav-item <?php echo ($current_page === 'newpage') ? 'active' : ''; ?>">
    <a href="?page=newpage">
        <i class="fas fa-star"></i>
        <span>New Page</span>
        <span class="badge-float">5</span>
    </a>
</li>
```

## 🎨 Styling

All styles are in `assets/css/style.css`. Modify this file to customize the look and feel.

## 💡 Benefits of This Structure

1. **Separation of Concerns** - Each file has a single responsibility
2. **Reusability** - Components can be used across multiple pages
3. **Maintainability** - Easy to find and update specific features
4. **Scalability** - Simple to add new pages and components
5. **Clean Code** - No inline CSS or JavaScript in PHP files

## 🔄 Migration from Original

The original `index.html` has been split into:
- CSS → `assets/css/style.css`
- JavaScript → `assets/js/main.js`
- HTML Structure → `includes/` and `components/`
- Page Content → `pages/`
- Configuration → `config/config.php`
- Main Logic → `index.php`

The original file is kept as `index.html` for reference.
