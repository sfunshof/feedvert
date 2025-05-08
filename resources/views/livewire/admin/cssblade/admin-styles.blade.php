<style>

html, body {
    overflow-y: hidden;
}

/* Base navbar styles with improved positioning */
.navbar {
    position: relative;
    z-index: 1030;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

/* Toggler button with better touch target and visual style */
.navbar-toggler {
    position: relative;
    z-index: 1050;
    padding: 0.75rem;
    border: none;
    border-radius: 8px;
    transition: background-color 0.2s ease;
}

.navbar-toggler:hover, .navbar-toggler:focus {
    background-color: rgba(0,0,0,0.05);
    outline: none;
}

/* Current active page indicator on collapsed navbar */
.navbar-brand-wrapper {
    display: flex;
    align-items: center;
}

.current-page-indicator {
    display: none;
    margin-left: 1rem;
    font-weight: 500;
    color: #6c757d;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@media (max-width: 991.98px) {
    .current-page-indicator {
        display: block;
    }
}

/* Improved collapsed menu appearance */
.navbar-collapse {
    position: absolute;
    top: 100%;
    left: 0;
    background-color: white;
    z-index: 1040;
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    padding: 0.5rem;
    max-height: 85vh;
    overflow-y: auto;
    width: 280px;
    border-radius: 0 0 12px 12px;
}

/* Enhanced touch-friendly nav links */
.navbar-nav .nav-link {
    padding: 1rem 1.25rem;
    font-size: 1.1rem;
    white-space: nowrap;
    border-radius: 8px;
    transition: all 0.2s ease;
    margin-bottom: 0.25rem;
}

.navbar-nav .nav-link:active {
    transform: scale(0.98);
}

/* More prominent active state */
.nav-link.active {
    background-color: #f0f7ff;
    color: #0d6efd;
    font-weight: 500;
    border-left: 4px solid #0d6efd;
}

/* Improved hover states for better feedback */
.nav-link:hover:not(.active) {
    background-color: rgba(0, 0, 0, 0.03);
}

/* Enhanced dropdown items for touch */
.dropdown-item {
    padding: 0.85rem 1.5rem;
    font-size: 1.05rem;
    border-radius: 6px;
    margin: 0.25rem 0;
    transition: background-color 0.2s ease;
}

.dropdown-item:active {
    transform: scale(0.98);
}

/* Improved icon spacing */
.nav-link i {
    margin-right: 0.75rem;
    width: 20px;
    text-align: center;
}

/* Better dropdown styling */
.dropdown-menu {
    border: none;
    box-shadow: 0 0.75rem 1.5rem rgba(0,0,0,.15);
    border-radius: 12px;
    padding: 0.75rem;
}

/* Improve dividers */
.nav-divider {
    border-right: 1px solid rgba(0, 0, 0, 0.1);
    height: 24px;
    margin: auto 0.75rem;
}

/* Content area */
#custom-div {
    position: relative;
    z-index: 1000;
}

.content {
    padding: 2rem;
    text-align: center;
}

/* Desktop adjustments with smoother transitions */
@media (min-width: 992px) {
    .navbar-collapse {
        position: static;
        box-shadow: none;
        padding: 0;
        max-height: none;
        overflow: visible;
        width: auto;
    }
    
    .nav-link.active {
        border-left: none;
        border-bottom: 3px solid #0d6efd;
    }
}

/* Mobile/tablet dropdown enhancements */
@media (max-width: 991.98px) {
    .dropdown-menu {
        background-color: #f8f9fa;
        border: none;
        box-shadow: none;
        padding: 0.5rem 0.75rem 0.5rem 1.5rem;
        margin: 0.25rem 0;
    }
    
    /* Indent dropdown items for better hierarchy */
    .dropdown-item {
        padding-left: 2.5rem;
    }
    
    /* Animated dropdown toggle */
    .dropdown-toggle::after {
        transition: transform 0.3s ease;
    }
    
    .dropdown-toggle[aria-expanded="true"]::after {
        transform: rotate(180deg);
    }
}

/* iPad/tablet specific optimizations */
@media (min-width: 768px) and (max-width: 991.98px) {
    .navbar-toggler {
        padding: 1rem;
    }
    
    .navbar-collapse {
        width: 320px;
    }
    
    .navbar-nav .nav-link,
    .dropdown-item {
        padding-top: 1.1rem;
        padding-bottom: 1.1rem;
    }
}

  /* Custom container for better mobile experience */
   .table-responsive-container {
            position: relative;
            height: 400px; /* Adjust height as needed */
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        /* Table header styling */
        .table-fixed-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background-color: #f8f9fa;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        /* Table body container with custom scrollbar */
        .table-scrollable-body {
            height: 100%;
            overflow-y: auto;
            /* Better touch scrolling for tablets */
            -webkit-overflow-scrolling: touch;
        }
        
        /* Custom scrollbar styling for WebKit browsers (iOS/iPad) */
        .table-scrollable-body::-webkit-scrollbar {
            width: 8px;
        }
        
        .table-scrollable-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 8px;
        }
        
        .table-scrollable-body::-webkit-scrollbar-thumb {
            background: #0d6efd;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        
        .table-scrollable-body::-webkit-scrollbar-thumb:hover {
            background: #0b5ed7;
        }
        
        /* Table row styling for better touch feedback */
        .table-hover tbody tr {
            transition: background-color 0.2s;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        
        /* Improved touch target sizes for mobile/tablet */
        .table td, .table th {
            padding: 12px 15px;
        }
        
        /* Add more visible feedback for touch devices */
        @media (pointer: coarse) {
            .table tbody tr:active {
                background-color: rgba(13, 110, 253, 0.1);
            }
        }

        /* Custom CSS for touch-optimized color boxes */
        .color-box {
            width: 60px;
            height: 60px;
            display: inline-block;
            margin: 5px;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .color-box.selected {
            border: 2px solid #000;
        }
        .color-box:hover {
            opacity: 0.8;
        }
        /* Floating labels */
        .form-floating > label {
            padding: 0.5rem 0.75rem;
        }
        /* Row demarcation */
        .row-divider {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
        }
        
        /* Scrollable Div */
        :root {
            --primary-color: #4a6da7;
            --secondary-color: #f5f7fa;
            --text-color: #333;
            --border-radius: 12px;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --scrollbar-color: #4a6da7; /* Blue scrollbar color variable */
            --scrollbar-track-color: rgba(74, 109, 167, 0.1); /* Light blue track */
        }

        .scroll-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            height: 500px;
            overflow-y: auto;
            position: relative;
            padding: 0;
            margin-bottom: 20px;
            -webkit-overflow-scrolling: touch; /* Enables momentum scrolling on iOS */
            scrollbar-color: var(--scrollbar-color) var(--scrollbar-track-color); /* Firefox */
            scrollbar-width: thin; /* Firefox */
        }
        .scroll-content {
            padding: 25px;
        }

        /* Custom scrollbar for webkit browsers - now blue */
        .scroll-container::-webkit-scrollbar {
            width: 8px;
            background-color: var(--scrollbar-track-color);
        }
        .scroll-container::-webkit-scrollbar-thumb {
            background-color: var(--scrollbar-color);
            border-radius: 4px;
        }
        .scroll-container::-webkit-scrollbar-track {
            background-color: var(--scrollbar-track-color);
            border-radius: 4px;
        }
        /* Scroll indicator */
        .scroll-indicator {
            height: 4px;
            width: 100%;
            background-color: #e0e0e0;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 10;
            border-top-left-radius: var(--border-radius);
            border-top-right-radius: var(--border-radius);
        }
        .scroll-progress {
            height: 100%;
            width: 0%;
            background-color: var(--primary-color);
            border-top-left-radius: var(--border-radius);
            transition: width 0.05s ease-out;
        }
       /* Control buttons for better UX */
        .scroll-controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }

        /* Responsive adjustments for tablets */
        @media (max-width: 1024px) {
            .container {
                padding: 15px;
            }
            .scroll-container {
                height: 62vh; /* Use viewport height for better tablet sizing */
            }
        }
        @media (min-width: 1025px) {
            .container {
                padding: 15px;
            }
            .scroll-container {
                height: 58vh; /* others */
            }
        }
        .square-btn {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
</style>