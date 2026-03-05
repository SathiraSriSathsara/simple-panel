<?php
/**
 * Docker Management Interface
 * Docker Hub Applications, Container Management, and Docker Compose
 */

require_once COMPONENTS_PATH . '/stat-card.php';

?>

<style>
    .docker-wrap {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dfe9f7;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 12px 26px rgba(18, 46, 84, 0.06);
        margin-bottom: 20px;
    }

    .docker-title {
        margin: 0 0 16px 0;
        color: #142c4e;
        font-size: 1.2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .docker-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .docker-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .docker-field label {
        font-size: 0.84rem;
        color: #4f6688;
        font-weight: 600;
    }

    .docker-input,
    .docker-select {
        border: 1px solid #c8daf5;
        border-radius: 10px;
        padding: 10px 12px;
        background: #fff;
        color: #163256;
        font-size: 0.95rem;
    }

    .docker-input:focus,
    .docker-select:focus {
        border-color: #5f9cf8;
        outline: none;
        box-shadow: 0 0 0 3px rgba(95, 156, 248, 0.1);
    }

    .docker-btn {
        border: none;
        border-radius: 10px;
        padding: 10px 14px;
        background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%);
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .docker-btn:hover {
        box-shadow: 0 4px 12px rgba(31, 58, 104, 0.3);
        transform: translateY(-1px);
    }

    .docker-btn.secondary {
        background: #edf3ff;
        color: #1f3a68;
        border: 1px solid #c7daf8;
    }

    .docker-btn.secondary:hover {
        background: #dae6ff;
        border-color: #5f9cf8;
    }

    .docker-btn.danger {
        background: #d94b4b;
    }

    .docker-btn.danger:hover {
        background: #c63838;
    }

    .docker-btn.success {
        background: #20784a;
    }

    .docker-btn.success:hover {
        background: #1a5f3a;
    }

    .docker-btn-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .docker-search-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
        align-items: center;
    }

    .docker-search-input {
        flex: 1;
        border: 1px solid #c8daf5;
        border-radius: 10px;
        padding: 10px 12px;
        background: #fff;
        color: #163256;
        font-size: 0.95rem;
    }

    .docker-app-card {
        background: #ffffff;
        border: 1px solid #dfe9f7;
        border-radius: 12px;
        padding: 16px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .docker-app-card:hover {
        border-color: #5f9cf8;
        box-shadow: 0 4px 12px rgba(95, 156, 248, 0.15);
        transform: translateY(-2px);
    }

    .app-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .app-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #e8f0ff 0%, #f0f6ff 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #2d538f;
    }

    .app-name {
        font-size: 1rem;
        font-weight: 600;
        color: #142c4e;
        margin: 0;
    }

    .app-description {
        font-size: 0.85rem;
        color: #4f6888;
        margin: 8px 0 12px 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .app-meta {
        display: flex;
        gap: 8px;
        font-size: 0.8rem;
        color: #637b9b;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .app-meta-item {
        background: #f0f6ff;
        padding: 4px 8px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .app-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 12px;
        margin-top: 12px;
    }

    .container-card {
        background: #ffffff;
        border: 1px solid #dbe6f5;
        border-radius: 12px;
        padding: 16px;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 16px;
        align-items: start;
    }

    .container-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .container-name {
        font-size: 1rem;
        font-weight: 600;
        color: #142c4e;
    }

    .container-detail {
        font-size: 0.85rem;
        color: #4f6888;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .container-status {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 6px;
    }

    .container-status.running {
        background-color: #20784a;
    }

    .container-status.stopped {
        background-color: #d94b4b;
    }

    .container-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .action-btn-small {
        padding: 8px 12px;
        font-size: 0.85rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .action-btn-small.console {
        background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%);
        color: #fff;
    }

    .action-btn-small.restart {
        background: #e8f0ff;
        color: #1f3a68;
        border: 1px solid #c7daf8;
    }

    .action-btn-small.delete {
        background: #ffecec;
        color: #a63a3a;
        border: 1px solid #f8baba;
    }

    .action-btn-small:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .tabs {
        display: flex;
        gap: 0;
        border-bottom: 2px solid #dfe9f7;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .tab {
        padding: 12px 20px;
        background: transparent;
        border: none;
        color: #4f6888;
        font-weight: 500;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all 0.3s ease;
    }

    .tab:hover {
        color: #1f3a68;
    }

    .tab.active {
        color: #1f3a68;
        border-bottom-color: #2d538f;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #4f6888;
    }

    .empty-state i {
        font-size: 3rem;
        color: #c8daf5;
        margin-bottom: 12px;
    }

    .empty-state p {
        font-size: 1rem;
        margin: 0;
    }

    .docker-badge {
        display: inline-block;
        background: #e8f0ff;
        color: #1f3a68;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .compose-editor {
        background: #1e1e1e;
        color: #d4d4d4;
        border: 1px solid #3e3e42;
        border-radius: 10px;
        padding: 16px;
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.9rem;
        min-height: 300px;
        line-height: 1.6;
    }

    .compose-editor textarea {
        width: 100%;
        background: transparent;
        color: #d4d4d4;
        border: none;
        resize: vertical;
        min-height: 300px;
        font-family: inherit;
        font-size: inherit;
        outline: none;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .docker-app-card {
            padding: 12px;
        }

        .app-grid {
            grid-template-columns: 1fr;
        }

        .container-card {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .tabs {
            gap: 0;
        }

        .tab {
            padding: 10px 16px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="docker-wrap">
    <h3 class="docker-title">
        <i class="fab fa-docker"></i> Docker Overview
    </h3>
    <div class="stats-grid">
        <div style="background: linear-gradient(135deg, #e8f0ff 0%, #f0f6ff 100%); border: 1px solid #c7daf8; border-radius: 12px; padding: 12px; text-align: center;">
            <div style="font-size: 0.85rem; color: #4f6888; margin-bottom: 6px;">Running Containers</div>
            <div style="font-size: 1.8rem; font-weight: 700; color: #1f3a68;">0</div>
        </div>
        <div style="background: linear-gradient(135deg, #f0f6ff 0%, #f8fbff 100%); border: 1px solid #dfe9f7; border-radius: 12px; padding: 12px; text-align: center;">
            <div style="font-size: 0.85rem; color: #4f6888; margin-bottom: 6px;">Total Containers</div>
            <div style="font-size: 1.8rem; font-weight: 700; color: #2d538f;">0</div>
        </div>
        <div style="background: linear-gradient(135deg, #e8f0ff 0%, #f0f6ff 100%); border: 1px solid #c7daf8; border-radius: 12px; padding: 12px; text-align: center;">
            <div style="font-size: 0.85rem; color: #4f6888; margin-bottom: 6px;">Images</div>
            <div style="font-size: 1.8rem; font-weight: 700; color: #1f3a68;">0</div>
        </div>
        <div style="background: linear-gradient(135deg, #f0f6ff 0%, #f8fbff 100%); border: 1px solid #dfe9f7; border-radius: 12px; padding: 12px; text-align: center;">
            <div style="font-size: 0.85rem; color: #4f6888; margin-bottom: 6px;">Status</div>
            <div style="font-size: 1.8rem; font-weight: 700; color: #2d538f;"><i class="fas fa-times-circle" style="color: #d94b4b;"></i></div>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<div class="docker-wrap" style="padding: 0;">
    <div class="tabs" style="padding: 0 20px;">
        <button class="tab active" data-tab="hub">
            <i class="fas fa-cloud"></i> Docker Hub
        </button>
        <button class="tab" data-tab="containers">
            <i class="fas fa-box"></i> Containers
        </button>
        <button class="tab" data-tab="compose">
            <i class="fas fa-file-code"></i> Compose
        </button>
    </div>
</div>

<!-- TAB 1: Docker Hub Applications -->
<div id="hub" class="tab-content active docker-wrap">
    <h3 class="docker-title">
        <i class="fas fa-cloud-download-alt"></i> Docker Hub Applications
    </h3>

    <div class="docker-search-bar">
        <input 
            type="text" 
            class="docker-search-input" 
            placeholder="Search applications (nginx, mysql, postgres, redis, mongodb...)"
            id="dockerHubSearch"
        >
        <button class="docker-btn"><i class="fas fa-search"></i> Search</button>
    </div>

    <!-- Popular Applications -->
    <h4 style="color: #142c4e; margin: 20px 0 16px 0; font-size: 1rem; font-weight: 600;">
        <i class="fas fa-star"></i> Popular Applications
    </h4>

    <div class="app-grid">
        <!-- nginx Card -->
        <div class="docker-app-card">
            <div class="app-header">
                <div class="app-icon">
                    <i class="fas fa-server"></i>
                </div>
                <h4 class="app-name">nginx</h4>
            </div>
            <p class="app-description">Official build of Nginx - a free, open-source, high-performance HTTP server and reverse proxy</p>
            <div class="app-meta">
                <span class="app-meta-item"><i class="fas fa-download"></i> 10M+</span>
                <span class="app-meta-item"><i class="fas fa-star"></i> 4.8</span>
                <span class="app-meta-item"><i class="fas fa-tag"></i> latest</span>
            </div>
            <div class="docker-btn-row">
                <button class="docker-btn" style="flex: 1;"><i class="fas fa-info-circle"></i> Details</button>
            </div>
        </div>

        <!-- MySQL Card -->
        <div class="docker-app-card">
            <div class="app-header">
                <div class="app-icon">
                    <i class="fas fa-database"></i>
                </div>
                <h4 class="app-name">mysql</h4>
            </div>
            <p class="app-description">The most popular open source relational database management system</p>
            <div class="app-meta">
                <span class="app-meta-item"><i class="fas fa-download"></i> 50M+</span>
                <span class="app-meta-item"><i class="fas fa-star"></i> 4.7</span>
                <span class="app-meta-item"><i class="fas fa-tag"></i> 8.0</span>
            </div>
            <div class="docker-btn-row">
                <button class="docker-btn" style="flex: 1;"><i class="fas fa-info-circle"></i> Details</button>
            </div>
        </div>

        <!-- PostgreSQL Card -->
        <div class="docker-app-card">
            <div class="app-header">
                <div class="app-icon">
                    <i class="fas fa-cube"></i>
                </div>
                <h4 class="app-name">postgres</h4>
            </div>
            <p class="app-description">The world's most advanced open source relational database</p>
            <div class="app-meta">
                <span class="app-meta-item"><i class="fas fa-download"></i> 30M+</span>
                <span class="app-meta-item"><i class="fas fa-star"></i> 4.8</span>
                <span class="app-meta-item"><i class="fas fa-tag"></i> 15</span>
            </div>
            <div class="docker-btn-row">
                <button class="docker-btn" style="flex: 1;"><i class="fas fa-info-circle"></i> Details</button>
            </div>
        </div>

        <!-- Redis Card -->
        <div class="docker-app-card">
            <div class="app-header">
                <div class="app-icon">
                    <i class="fas fa-zap"></i>
                </div>
                <h4 class="app-name">redis</h4>
            </div>
            <p class="app-description">Redis is an open source key-value store that functions as a data structure server</p>
            <div class="app-meta">
                <span class="app-meta-item"><i class="fas fa-download"></i> 25M+</span>
                <span class="app-meta-item"><i class="fas fa-star"></i> 4.8</span>
                <span class="app-meta-item"><i class="fas fa-tag"></i> 7.0</span>
            </div>
            <div class="docker-btn-row">
                <button class="docker-btn" style="flex: 1;"><i class="fas fa-info-circle"></i> Details</button>
            </div>
        </div>

        <!-- MongoDB Card -->
        <div class="docker-app-card">
            <div class="app-header">
                <div class="app-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h4 class="app-name">mongo</h4>
            </div>
            <p class="app-description">MongoDB is a document database with JSON-like documents & full developer support</p>
            <div class="app-meta">
                <span class="app-meta-item"><i class="fas fa-download"></i> 20M+</span>
                <span class="app-meta-item"><i class="fas fa-star"></i> 4.6</span>
                <span class="app-meta-item"><i class="fas fa-tag"></i> 7.0</span>
            </div>
            <div class="docker-btn-row">
                <button class="docker-btn" style="flex: 1;"><i class="fas fa-info-circle"></i> Details</button>
            </div>
        </div>

        <!-- Node.js Card -->
        <div class="docker-app-card">
            <div class="app-header">
                <div class="app-icon">
                    <i class="fab fa-node-js"></i>
                </div>
                <h4 class="app-name">node</h4>
            </div>
            <p class="app-description">Node.js is a JavaScript runtime built on Chrome's V8 JavaScript engine</p>
            <div class="app-meta">
                <span class="app-meta-item"><i class="fas fa-download"></i> 35M+</span>
                <span class="app-meta-item"><i class="fas fa-star"></i> 4.7</span>
                <span class="app-meta-item"><i class="fas fa-tag"></i> 20</span>
            </div>
            <div class="docker-btn-row">
                <button class="docker-btn" style="flex: 1;"><i class="fas fa-info-circle"></i> Details</button>
            </div>
        </div>

        <!-- Apache Card -->
        <div class="docker-app-card">
            <div class="app-header">
                <div class="app-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h4 class="app-name">httpd</h4>
            </div>
            <p class="app-description">The Apache HTTP Server Project - a robust, commercial-grade, featureful HTTP server</p>
            <div class="app-meta">
                <span class="app-meta-item"><i class="fas fa-download"></i> 15M+</span>
                <span class="app-meta-item"><i class="fas fa-star"></i> 4.5</span>
                <span class="app-meta-item"><i class="fas fa-tag"></i> 2.4</span>
            </div>
            <div class="docker-btn-row">
                <button class="docker-btn" style="flex: 1;"><i class="fas fa-info-circle"></i> Details</button>
            </div>
        </div>

        <!-- PHP Card -->
        <div class="docker-app-card">
            <div class="app-header">
                <div class="app-icon">
                    <i class="fab fa-php"></i>
                </div>
                <h4 class="app-name">php</h4>
            </div>
            <p class="app-description">PHP is a programming language for creating dynamic and interactive web pages</p>
            <div class="app-meta">
                <span class="app-meta-item"><i class="fas fa-download"></i> 40M+</span>
                <span class="app-meta-item"><i class="fas fa-star"></i> 4.7</span>
                <span class="app-meta-item"><i class="fas fa-tag"></i> 8.2</span>
            </div>
            <div class="docker-btn-row">
                <button class="docker-btn" style="flex: 1;"><i class="fas fa-info-circle"></i> Details</button>
            </div>
        </div>

        <!-- RabbitMQ Card -->
        <div class="docker-app-card">
            <div class="app-header">
                <div class="app-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h4 class="app-name">rabbitmq</h4>
            </div>
            <p class="app-description">RabbitMQ is an open source message broker software that implements AMQP</p>
            <div class="app-meta">
                <span class="app-meta-item"><i class="fas fa-download"></i> 8M+</span>
                <span class="app-meta-item"><i class="fas fa-star"></i> 4.6</span>
                <span class="app-meta-item"><i class="fas fa-tag"></i> 3.12</span>
            </div>
            <div class="docker-btn-row">
                <button class="docker-btn" style="flex: 1;"><i class="fas fa-info-circle"></i> Details</button>
            </div>
        </div>
    </div>

    <h4 style="color: #142c4e; margin: 30px 0 16px 0; font-size: 1rem; font-weight: 600;">
        <i class="fas fa-history"></i> All Applications
    </h4>
    <div class="empty-state" style="padding: 20px;">
        <p>Search or browse more applications by using the search bar above</p>
    </div>
</div>

<!-- TAB 2: Locally Running Containers -->
<div id="containers" class="tab-content docker-wrap">
    <h3 class="docker-title">
        <i class="fas fa-box"></i> Running Containers
    </h3>

    <div class="docker-btn-row" style="margin-bottom: 20px;">
        <button class="docker-btn"><i class="fas fa-redo"></i> Refresh</button>
        <button class="docker-btn secondary"><i class="fas fa-filter"></i> Filter</button>
    </div>

    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p>No containers are currently running</p>
        <p style="font-size: 0.9rem; color: #637b9b; margin-top: 8px;">Start by selecting an application from the Docker Hub tab or using a Docker Compose file</p>
    </div>

    <!-- Example Container (Placeholder) -->
    <div style="display: none;">
        <div class="container-card">
            <div class="container-info">
                <div class="container-name">
                    <span class="container-status running"></span>
                    nginx-prod
                </div>
                <div class="container-detail">
                    <i class="fas fa-image"></i> nginx:latest
                </div>
                <div class="container-detail">
                    <i class="fas fa-clock"></i> Running for 2 days
                </div>
                <div class="container-detail">
                    <i class="fas fa-network-wired"></i> 172.17.0.2:80→80/tcp
                </div>
                <div class="container-detail">
                    <i class="fas fa-microchip"></i> CPU: 0.1% | Memory: 25MB
                </div>
            </div>
            <div class="container-actions">
                <button class="action-btn-small console"><i class="fas fa-terminal"></i> Console</button>
                <button class="action-btn-small restart"><i class="fas fa-sync"></i> Restart</button>
                <button class="action-btn-small restart"><i class="fas fa-pause"></i> Pause</button>
                <button class="action-btn-small delete"><i class="fas fa-trash"></i> Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- TAB 3: Docker Compose -->
<div id="compose" class="tab-content docker-wrap">
    <h3 class="docker-title">
        <i class="fas fa-file-code"></i> Docker Compose
    </h3>

    <p style="color: #4f6888; margin-bottom: 16px; font-size: 0.95rem;">
        Create and manage multi-container applications using Docker Compose. Write your docker-compose.yml content below.
    </p>

    <h4 style="color: #142c4e; margin: 20px 0 12px 0; font-size: 0.95rem; font-weight: 600;">
        docker-compose.yml
    </h4>

    <div class="compose-editor">
        <textarea placeholder="version: '3.8'
services:
  web:
    image: nginx:latest
    ports:
      - '80:80'
    volumes:
      - ./html:/usr/share/nginx/html
  
  database:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: secret
    ports:
      - '3306:3306'
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:" spellcheck="false"></textarea>
    </div>

    <div class="docker-btn-row" style="margin-top: 16px;">
        <button class="docker-btn"><i class="fas fa-play"></i> Start Compose</button>
        <button class="docker-btn secondary"><i class="fas fa-stop"></i> Stop Compose</button>
        <button class="docker-btn secondary"><i class="fas fa-redo"></i> Restart</button>
        <button class="docker-btn danger"><i class="fas fa-trash"></i> Remove</button>
    </div>

    <div style="margin-top: 20px; padding: 16px; background: #f0f6ff; border: 1px solid #c7daf8; border-radius: 12px;">
        <h4 style="color: #1f3a68; margin: 0 0 10px 0; font-size: 0.95rem; font-weight: 600;">
            <i class="fas fa-lightbulb"></i> Tips
        </h4>
        <ul style="color: #4f6888; font-size: 0.9rem; margin: 0; padding-left: 20px;">
            <li>Use version 3.8 or higher for best compatibility</li>
            <li>Define services, networks, and volumes for complex applications</li>
            <li>Use environment variables for configuration management</li>
            <li>Mount volumes for persistent data storage</li>
            <li>Configure ports for service accessibility</li>
        </ul>
    </div>

    <h4 style="color: #142c4e; margin: 30px 0 12px 0; font-size: 0.95rem; font-weight: 600;">
        <i class="fas fa-history"></i> Saved Configurations
    </h4>

    <div class="empty-state" style="padding: 20px;">
        <i class="fas fa-database"></i>
        <p>No saved compose configurations</p>
        <p style="font-size: 0.9rem; color: #637b9b; margin-top: 8px;">Create and save your docker-compose configurations here</p>
    </div>
</div>

<script>
    // Tab switching functionality
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(t => {
                t.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked tab
            this.classList.add('active');
        });
    });

    // Search functionality
    document.getElementById('dockerHubSearch').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            console.log('Searching for:', this.value);
            // TODO: Implement search API call
        }
    });
</script>
