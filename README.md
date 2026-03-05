<div align="center">

<img src="./assets/images/simple-panel-logo-no-bg-white.png" alt="logo" width="400px">

**Fast. Simple. Powerful Hosting Control.**

</div>

---

> [!WARNING]
> This Project is still under development. You can also contribute to this project.


<img src="./assets/images/screenshot.png" alt="screenshot">

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

- `http://localhost:8000/` - Default page (Dashboard)
- `http://localhost:8000/?page=dashboard` - Dashboard (Server Monitoring)
- `http://localhost:8000/?page=websites` - Websites
- `http://localhost:8000/?page=nodeapps` - Node Apps
- `http://localhost:8000/?page=revproxy` - Reverse Proxy
- `http://localhost:8000/?page=databases` - Databases
- `http://localhost:8000/?page=domains` - Domains
- `http://localhost:8000/?page=ssl` - SSL/TLS

## ✨ Features

### 📊 Real-time Dashboard
- **CPU Usage Monitoring** - Live CPU utilization with color-coded progress bars and real-time graph
- **RAM Usage** - Memory consumption tracking with used/total display and historical graph
- **Disk Usage** - Storage usage monitoring with doughnut chart visualization
- **Network Stats** - Network traffic monitoring (received/sent)
- **Server Information** - Hostname, OS, uptime, and load average
- **Auto-refresh** - Dashboard updates automatically every 3 seconds
- **Interactive Charts** - Real-time line graphs using Chart.js showing last 20 data points
- **Visual Progress Bars** - Color-coded indicators (green/orange/red based on usage)

### 🌐 Multi-language Support
- **English (default)** - Primary language for the interface
- **Sinhala (සිංහල)** - Automatic translation using Google Translate API
- **Dynamic Translation** - All text is automatically translated in real-time
- **Easy Language Switching** - Toggle between languages via the language selector
- **Translation Caching** - Reduces API calls by caching translated text

To switch languages, use the language dropdown in the top bar or add `?lang=si` to the URL.


