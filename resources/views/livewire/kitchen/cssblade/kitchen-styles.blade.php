<style>
    /* Larger fixed header */
    .header {
        height: 120px;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
    }
    .menu-container {
        background-color: #f8f9fa;
        border-radius: 25px;
        padding: 10px 30px;
        display: flex;
        gap: 20px; /* Increased space between menu items */
    }
    .menu-item {
        font-size: 1.2rem;
        cursor: pointer;
        padding: 10px 25px; /* Increased padding for larger touch targets */
        border-radius: 20px;
        transition: background-color 0.3s ease;
    }
    .menu-item.active {
        background-color: #007bff;
        color: white;
    }
    .menu-item:not(.active) {
        background-color: #e9ecef;
    }
    /* Full-page body div while preventing a scrollbar */
    .body-content {
        position: absolute;
        top: 120px; /* Push below the fixed header */
        bottom: 20px; /* Prevents content from reaching the very bottom */
        left: 0;
        right: 0;
        /* overflow: hidden;  Ensures no scrolling */
        /* padding: 20px; */
    }


    /* Enhanced scrolling container */
    #tableContainer {
        display: flex;
        align-items: center;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        user-select: none;
        white-space: nowrap;
        scrollbar-width: thin;
        scrollbar-color: var(--bs-primary) rgba(0,0,0,0.1);
        padding-bottom: 12px;
    }

    /* Customize scrollbar for Webkit browsers (Chrome, Safari) */
    #tableContainer::-webkit-scrollbar {
        height: 8px;
        border-radius: 4px;
    }

    #tableContainer::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.05);
        border-radius: 4px;
    }

    #tableContainer::-webkit-scrollbar-thumb {
        background-color: var(--bs-primary);
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    #tableContainer::-webkit-scrollbar-thumb:hover {
        background-color: var(--bs-primary-dark, #0056b3);
    }

    /* Enhanced wrapper - added padding to prevent edge cutoff */
    .table-wrapper {
        display: flex;
        gap: 8px; /* Reduced gap between columns */
        padding: 16px 24px; /* Added horizontal padding to prevent edge cutoff */
        min-width: 100%;
    }

    /* Optimized columns for touch */
    .table-column {
        display: flex;
        flex-direction: column;
        flex: 0 0 auto;
        width: clamp(280px, 80vw, 350px);
        touch-action: pan-x;
        padding: 12px;
        border-radius: 8px;
        transition: transform 0.2s ease;
        margin: 0 2px; /* Small margin to ensure separation */
    }

    /* Touch device optimizations */
    @media (pointer: coarse) {
        .table-column {
            padding: 14px; /* Slightly reduced from previous version */
        }
        
        #tableContainer {
            scroll-behavior: smooth;
        }
        
        /* Ensure first and last items aren't cut off on touch devices */
        .table-wrapper {
            padding-left: calc(24px + env(safe-area-inset-left));
            padding-right: calc(24px + env(safe-area-inset-right));
        }
    }

    /* Make sure the container has proper positioning for edge visibility */
    .bg-light {
        height: 100%;
        overflow: hidden;
        position: relative;
        padding: 0; /* Remove default padding if any */
    }

    /* Optional: Adding fading edges for visual cue that there's more content */
    .bg-light::before,
    .bg-light::after {
        content: '';
        position: absolute;
        top: 0;
        width: 20px;
        height: 100%;
        z-index: 1;
        pointer-events: none;
    }

    .bg-light::before {
        left: 0;
        background: linear-gradient(to right, rgba(248, 249, 250, 0.9), rgba(248, 249, 250, 0));
    }

    .bg-light::after {
        right: 0;
        background: linear-gradient(to left, rgba(248, 249, 250, 0.9), rgba(248, 249, 250, 0));
    }

    /* Cell styles */
    .cell {
        overflow-wrap: break-word; /* Modern property for word wrapping */
        word-wrap: break-word; /* Older compatibility */
        word-break: break-word; /* Handles breaking words in certain cases */
        white-space: normal; /* Ensures the text wraps normally */
        border: 1px solid #ddd; /* Optional: Add a border for styling */
        color: black; /* Text color */
        padding: 10px; /* Optional: Adds spacing inside the div */
        max-width: 100%; /* Ensures the div doesn't overflow */
    }
    .top { height: 15%; background: #007bff; }
    .middle { height: 65%; }
    .bottom { height: 15%;}

    /* Prevent column cutting */
    #tableContainer::after {
        content: "";
        flex: 0 0 10px;
    }
    /* This is for the middle cell */
    .scroll-container {
        overflow-y: scroll; /* Enable vertical scrolling */
        -webkit-overflow-scrolling: touch; /* Enable smooth scrolling on iOS */
        /* Hide scrollbar for Chrome, Safari, and Opera */
        &::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge, and Firefox */
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
        /* Additional styling for better touch experience */
        max-height: 100vh; /* Or whatever height you prefer */
        padding: 10px;
        touch-action: pan-y; /* Optimize for vertical touch scrolling */
     }
</style>