<style>
    .splash-screen {
        height: 100vh;
        background-color: white;
    }
    .logo {
        max-width: 150px;
        max-height: 150px;
    }

    .status-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        height: 44px; /* Standard iOS status bar height */
        background-color: rgba(255, 255, 255, 0.8);
        padding: 0 12px;
        box-sizing: border-box;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    .status-bar-left {
        position: absolute;
        left: 12px;
        font-size: 14px;
        color: #000;
    }

    .status-bar-center {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        font-size: 16px;
        font-weight: 600;
        color: #000;
    }

    .status-bar-right {
        position: absolute;
        right: 12px;
    }

    .status-icons {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
    }

    /* Optional: Add more detailed styling for status icons */
    .signal-icon, 
    .wifi-icon, 
    .battery-icon {
        display: inline-block;
    }
    
    ul {
        overflow-y: auto;
        scroll-behavior: smooth;
    }

    ul::-webkit-scrollbar {
        width: 12px;
    }

    ul::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 6px;
    }

    ul::-webkit-scrollbar-track {
        background-color: rgba(255, 255, 255, 0.8);
    }

    ul:focus {
        scroll-snap-align: end;
    }


    /* List styling */
    .menu-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-bottom: 1px solid #e9ecef;
    }

    .menu-item img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        margin-right: 15px;
    }

    .menu-item-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .menu-item-text-top {
        font-size: 16px;
        margin-bottom: 5px;
    }

    .menu-item-text-bottom {
        font-size: 14px;
        color: #6c757d;
    }

    /*** scrollbar ***/
    /* General styling to hide the horizontal scrollbar */
    body {
        overflow-x: hidden; /* Disable horizontal scrolling */
    }

    /* Style the vertical scrollbar */
    body::-webkit-scrollbar {
        width: 10px; /* Width of the vertical scrollbar */
    }

    body::-webkit-scrollbar-track {
        background: #f1f1f1; /* Light gray track */
    }

    body::-webkit-scrollbar-thumb {
        background-color: #888; /* Gray scrollbar thumb */
        border-radius: 5px; /* Rounded corners */
        border: 2px solid #f1f1f1; /* Optional: create padding effect */
    }

    body::-webkit-scrollbar-thumb:hover {
        background: #555; /* Darker gray on hover */
    }

    /* For Firefox */
    body {
        scrollbar-width: thin; /* Makes scrollbar thin */
        scrollbar-color: #888 #f1f1f1; /* Thumb color and track color */
    }

    /* Optional: Add smooth scrolling for better touch experience */
    html {
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch; /* Enable touch-based scrolling */
    }

    .scrollable-list {
        height: 90vh; /* Adjust as needed */
        overflow-y: auto;
        overscroll-behavior-y: contain;
        scroll-snap-type: y proximity;
        scrollbar-width: thin;
        scrollbar-color: #888 #f1f1f1;
        -webkit-overflow-scrolling: touch;
    }

    .scrollable-list::-webkit-scrollbar {
        width: 6px;
    }

    .scrollable-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .scrollable-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .scrollable-list::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    @supports (-webkit-touch-callout: none) {
        .scrollable-list {
            /* Fallback for iOS momentum scrolling */
            -webkit-overflow-scrolling: touch;
        }
    }

    html, body {
            height: 100%;
            margin: 0;
    }
    body {
        display: flex;
        flex-direction: column;
    }
    .content-wrapper {
        flex: 1 0 auto;
        overflow-y: auto;
        padding-bottom: 60px; /* Adjust based on your footer height */
    }
    .logo-container {
        height: 40vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .logo {
        width: 200px;
        height: 200px;
        object-fit: contain;
        margin-bottom: 5px; /* Add some space between logo and text */
    }
    
    .logo-text {
        text-align: center;
        margin: 1px 0;
    }
    .counter {
        font-size: 2rem;
        font-weight: bold;
    }
    .footer {
        flex-shrink: 0;
        height: 60px; /* Adjust as needed */
        background-color: #f8f9fa;
        position: fixed;
        bottom: 0;
        width: 100%;
    }
    .counter-container {
        display: flex;
        width: 80%;
        max-width: 400px;
        margin: 20px auto;
        border: 2px solid #007bff;
        border-radius: 0.375rem; /* Bootstrap 5 button border-radius */
        height: 38px; /* Standard Bootstrap 5 button height */
        overflow: hidden;
    }

    .counter-btn {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
        border: none;
        font-size: 16px;
        font-weight: 400;
        padding: 0;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .counter-btn:hover {
        background-color: #e9ecef;
    }

    .counter-btn.minus {
        border-right: 1px solid #dee2e6;
    }

    .counter-btn.plus {
        border-left: 1px solid #dee2e6;
    }

    .counter-display {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: white;
        font-size: 16px;
        padding: 0;
    }
    .btn-custom {
         display: flex; 
         justify-content: space-between; 
         align-items: center; 
         width: 100%; 
    }
    /*  Start check out css */
    .rectangle {
        border: none;
        padding: 1px;
        /* min-height: 300px; */
    }
    /*
    .icon-row {
        display: flex;
        justify-content: space-between;
        width: 100%;
    }
     */   
    .checkoutImg {
        width: 140px;
        height: 140px;
        object-fit: cover;
        margin-right: 1px;
    }
    .custom-select {
        max-width: 70px;
    }
    .no-shadow { 
        box-shadow: none !important; 
    }
    /*  End check out css */

    /* Custom CSS for underline effect */
    .underline-thick-fade {
        position: relative;
        display: inline-block;
        text-decoration: none;
    }

    .underline-thick-fade::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 1px; /* Adjust thickness here */
        bottom: 0;
        left: 0;
        background-color: currentColor;
        opacity: 0.5; /* Adjust opacity for fade effect */
        transition: opacity 0.3s ease-in-out; /* Adjust fade duration here */
    }
    
    /* This is Radio buttton */
     /* 1. Enlarge Radio Buttons and Remove Shadows */
    .custom-radio .form-check-input[type="radio"] {
        width: 1.5em;              /* Increase width */
        height: 1.5em;             /* Increase height */
        border-width: 0.2em;       /* Thicker border for visibility */
        box-shadow: none;          /* Remove default shadow */
        margin-top: 0.3em;         /* Align vertically with label */
        transition: all 0.3s ease; /* Smooth transitions */
        cursor: pointer;           /* Change cursor to pointer */
    }

    /* 2. Style the Inner Dot of the Radio Button */
    .custom-radio .form-check-input[type="radio"]::after {
        width: 0.75em;
        height: 0.75em;
        background-color: white;   /* Default inner dot color */
        border-radius: 50%;        /* Ensure it's circular */
        transition: background-color 0.3s ease;
    }

    /* 3. Change Inner Dot Color When Checked */
    .custom-radio .form-check-input[type="radio"]:checked::after {
        background-color: #0d6efd; /* Bootstrap's primary color (blue) */
    }

    /* 4. Dashed Border Around Label When Radio is Checked */
    .custom-radio .form-check-input[type="radio"]:checked + .form-check-label {
        border: 2px dashed #0d6efd; /* Dashed border with primary color */
        padding: 0.5em;              /* Space between text and border */
        border-radius: 0.25em;       /* Slightly rounded corners */
    }

    /* 5. Remove Border When Not Checked */
    .custom-radio .form-check-label {
        border: 2px solid transparent; /* Transparent border to maintain space */
        padding: 0.5em;                /* Space between text and border */
        border-radius: 0.25em;         /* Slightly rounded corners */
        transition: border 0.3s ease;  /* Smooth border transition */
        cursor: pointer;               /* Change cursor to pointer */
    }

    /* 6. Optional: Change Border Color on Hover */
    .custom-radio .form-check-label:hover {
        border-color: #a0c4ff; /* Slightly lighter blue on hover */
    }

    /* 7. Ensure Proper Alignment */
    .custom-radio .form-check-label {
        margin-left: 0.5em;         /* Space between radio and label */
        vertical-align: middle;     /* Vertically center the label */
    }

    /** Online **/
    .clickable-rectangle {
        width: 100%;
        height: 100%;
        background-color: #f0f0f0;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.2s;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .clickable-rectangle:hover {
        transform: scale(1.05);
    }

    .clickable-rectangle:active {
        transform: scale(0.95);
    }

    .logo-img {
        max-width: 80%;
        max-height: 80%;
        object-fit: contain;
    }
    /* Order collection Methd */
    .container-box {
            width: 180px;
            height: 180px;
            border: 3px solid #6f42c1;
            border-radius: 15px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background-color: #f8f9fa;
        }
        
        .box-text {
            text-align: center;
            font-weight: bold;
            color: #6f42c1;
            margin-bottom: 10px;
        }
        
        .box-image-container {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            flex-grow: 1;
        }
        
        .box-image {
            max-width: 75%;
            max-height: 75%;
            object-fit: contain;
        }
        
        /* Landscape orientation */
        @media (orientation: landscape) {
            .responsive-container {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                gap: 20px;
                height: 100vh;
                padding: 20px;
            }
        }
        
        /* Portrait orientation */
        @media (orientation: portrait) {
            .responsive-container {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 20px;
                height: 100vh;
                padding: 20px;
            }
        }
        
</style>    