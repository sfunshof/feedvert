<style>
    .slider-container {
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        overflow-x: auto;
        overflow-y: hidden;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch; /* Better scroll behavior on iOS */
        scrollbar-width: thin; /* For Firefox */
        scrollbar-color: #0d6efd #f0f0f0; /* For Firefox */
    }

    .slider-table {
        border-collapse: collapse;
        white-space: nowrap;
    }

    .slider-table td {
        border: none;
        min-width: 180px;
        height: 120px;
        text-align: center;
        scroll-snap-align: start;
        scroll-snap-stop: always;
    }

    /* Custom scrollbar styling */
    .slider-container::-webkit-scrollbar {
        height: 8px;
        display: block;
    }

    .slider-container::-webkit-scrollbar-track {
        background: #f0f0f0;
        border-radius: 4px;
    }

    .slider-container::-webkit-scrollbar-thumb {
        background: #0d6efd; /* Bootstrap 5 primary color */
        border-radius: 4px;
        transition: background 0.3s ease;
    }

    .slider-container::-webkit-scrollbar-thumb:hover {
        background: #0b5ed7; /* Slightly darker on hover */
    }

    /* Firefox scrollbar styling */
    .slider-container {
        scrollbar-width: thin;
        scrollbar-color: #0d6efd #f0f0f0;
    }

    /* Force scrollbar visibility on all devices */
    .slider-container::-webkit-scrollbar {
        height: 8px;
        display: block !important; /* Force display */
        -webkit-appearance: none; /* Ensure consistent appearance */
    }

    /* Media query for iPad Mini and similar small tablets */
    @media only screen and (max-width: 768px) {
        .slider-container::-webkit-scrollbar {
            height: 6px; /* Slightly smaller on mobile */
        }
        /* Add this to ensure content indicates scrollability */
        .slider-container::after {
            content: '';
            display: block;
            width: 10px;
            height: 100%;
            position: absolute;
            right: 0;
            top: 0;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.7));
            pointer-events: none;
        }
    }

   /* Rectangle inside the <td> */
    .rectangle {
        width: calc(100% - 1px); /* Adjust for left and right margin */
        height: 100%; /* Adjust for top and bottom margin */
    
    }
    .list-container {
        height: 65%;
        width: 100%;
        overflow-y: auto;
        border: 1px solid #ddd;
        padding: 1px;
        touch-action: pan-y;
        -webkit-overflow-scrolling: touch;
    }
    .list-container::-webkit-scrollbar {
        width: 18px;
    }
    .list-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 6px;
    }
    .list-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 6px;
        border: 3px solid #f1f1f1;
    }
    .list-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .list-item {
        border-bottom: 1px solid #ccc;
        margin-bottom: 8px;
        padding: 2px;
    }
    .list-row {
        padding: 2px;
        margin: 2px 0;
        background: hsl(0, 0%, 98%);
    }
     
    
    .keypad-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 5px;
    }

    .keypad-btn {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        /* font-weight: bold; */
    }

    .btn-touch {
        padding: 12px 24px; /* Increase padding for a larger touch target */
        font-size: 1.25rem; /* Increase font size for better readability */
        border-radius: 8px; /* Add rounded corners */
        height: auto; /* Let the height adjust naturally */
        min-height: 48px; /* Set a minimum height for touch comfort */
        display: inline-flex; /* Align content easily */
        align-items: center; /* Center text/icon vertically */
        justify-content: center; /* Center text/icon horizontally */
    }

    /* Add a hover effect for better interactivity */
    .btn-touch:hover {
        background-color: #007bff; /* Adjust to match your brand's color */
        color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Add subtle shadow */
    }

    /* Optional: Focus styles for accessibility */
    .btn-touch:focus {
        outline: 2px solid #0056b3; /* Ensure focus visibility */
        outline-offset: 2px;
    }


    /* Counter container styling */
    .counter-container {
        display: inline-flex;
        align-items: center;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    /* Counter button styling */
    .btn-counter {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
        background-color: #007bff;
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.2s, box-shadow 0.2s;
    }
    /* Hover and active states for buttons */
    .btn-counter:hover {
        background-color: #0056b3;
    }
    .btn-counter:active {
        background-color: #004085;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    /* Counter display styling */
    .counter-display {
        min-width: 60px;
        font-size: 1.25rem;
        font-weight: bold;
        text-align: center;
        background-color: white;
        color: #333;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }





    .full-height {
      height: calc(80%);
    }
    
    .hidden { 
        visibility: hidden; 
    }

    .w-80 {
        width: 80%;
    }
     .h-90 {
        height: 90%;
    }
    .h-10 {
        height: 10%;
    }
    .w-33 {
        width: 33.33%;
    }

    /***** Start of  ipad/table ******/
    body {
        padding: 0;
        margin: 0;
    }
    .sidebar {
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }
    .content-area {
        margin-left: 16.666667%; /* Equal to col-md-2 */
    }
    @media (max-width: 768px) {
        .content-area {
            margin-left: 0;
            padding-top: 60px;
        }
        .sidebar {
            width: 100%;
            height: auto;
            position: fixed;
            z-index: 1000;
        }
        .sidebar-buttons {
            display: flex;
            flex-direction: row;
            padding: 10px;
        }
        .sidebar-buttons .btn {
            margin: 0 5px;
            flex: 1;
        }
    }
    /*  Send modal back beacause of snapDialog */
    .modal {
        z-index: 1 !important; /* Lower value to push it behind */
    }
    .modal-backdrop {
        z-index: 0 !important;
    }
    .modal-dialog {
      overflow: hidden; /* Ensures no outer scrollbars */
    }

    .modal-body {
        overflow-y: auto; /* Enable internal vertical scrolling if needed */
        scrollbar-width: none; /* For Firefox */
        -ms-overflow-style: none; /* For Internet Explorer and Edge */
    }

    /* Hide scrollbar visually */
    .modal-body::-webkit-scrollbar {
        display: none; /* For Chrome, Safari, and Opera */
    }

    
     /* Scrollable Container CSS with Tablet Optimizations */
    .scrollable_container {
        /* Make it take full height of parent */
        height: 100%;

        /* Enable vertical scrolling only */
        overflow-y: auto;
        overflow-x: hidden;

        /* Add padding for content breathing room */
        padding: 15px;

        /* Bootstrap 5 text-primary color 
        color: #0d6efd;
        */ 
        
        /* Attractive scrollbar styling */
        scrollbar-width: thin; /* For Firefox */
        scrollbar-color: #0d6efd rgba(13, 110, 253, 0.1); /* For Firefox */

        /* Tablet-specific improvements */
        -webkit-overflow-scrolling: touch; /* Enables momentum scrolling on iOS */
        touch-action: pan-y; /* Explicitly allow vertical touch scrolling only */

        /* Prevent text selection during scrolling on touch devices */
        -webkit-user-select: none;
            user-select: none;
    }

    /* Webkit scrollbar styling (Chrome, Safari, newer Edge) */
    .scrollable_container::-webkit-scrollbar {
        width: 8px;
    }

    .scrollable_container::-webkit-scrollbar-track {
        background: rgba(13, 110, 253, 0.1);
        border-radius: 10px;
    }

    .scrollable_container::-webkit-scrollbar-thumb {
        background-color: #0d6efd;
        border-radius: 10px;
    }

    /* Make scrollbar slightly larger on touch devices for easier interaction */
    @media (pointer: coarse) {
        .scrollable_container::-webkit-scrollbar {
            width: 12px;
        }
    }

    /* Parent div setup for context */
    .parentDiv {
        /* Example parent container setup */
        position: relative;
        height: 100%; /* Adjust as needed */
        width: 100%;
    }

    /* Text element optimizations for tablets */
    .scrollable_container p {
        /* Improved readability on tablets */
        font-size: 16px; /* Minimum recommended size for readable text on tablets */
        line-height: 1.5;
    }
    /** This is the modal functions Menu  ***/
    .sidebar-menu {
        padding: 1rem 0.5rem;
    }
    
    .sidebar-menu .menu-item {
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .sidebar-menu .menu-item a {
        display: block;
        padding: 0.75rem 1rem;
        color: #495057;
        text-decoration: none;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }
    
    .sidebar-menu .menu-item:hover a {
        border-color: #adb5bd;
        background-color: #f8f9fa;
        color: #212529;
        box-shadow: 0 2px 5px rgba(0,0,0,0.08);
    }
    
    .sidebar-menu .menu-item.active a {
        border-color: #0d6efd;
        border-left-width: 4px;
        background-color: rgba(13, 110, 253, 0.05);
        color: #0d6efd;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(13, 110, 253, 0.1);
    }
</style>