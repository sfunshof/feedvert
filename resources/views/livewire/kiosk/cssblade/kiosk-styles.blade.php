<style>
    /* Custom styling for the images */
    .kiosk_top-image {
        height: 75vh; /* 75% of the viewport height */
        width: 100%; /* Full width */
        object-fit: cover; /* Ensures the image covers the area without stretching */
    }
    .kiosk_lower-image {
        height: 25vh; /* 25% of the viewport height */
        width: 100%; /* Full width */
        object-fit: cover; /* Ensures the image covers the area without stretching */
        border: 5px solid #fa0d0d; /* Black border with 5px width */
        border-radius: 10px;
    }
    
    .kiosk_lower-container {
        position: relative; /* Makes the container relative to allow absolute positioning of the button */
     }

    /* Default to normal button size (for development) */
     .kiosk-button {
        font-size: 1rem; /* Standard font size for development */
        padding: 10px 20px; /* Normal padding */
    }

    /* Kiosk (large screen) - Use btn-lg for kiosks with larger screen */
    @media (min-width: 1080px) and (min-height: 1920px) {
        .kiosk-button {
            font-size: 1.5rem; /* Increase font size for kiosk screens */
            padding: 20px 40px; /* Larger padding for better touch */
        }
    }

    /* Content section - takes up 75% of the height */
    .kiosk-content {
        box-sizing: border-box;
        width: 100%;
        display: flex;
    }

    /* Footer - takes up 25% of the height and stays at the bottom */
    .kiosk-footer {
        height: 20%;
        background-color: lightcoral;
        display: flex;
        justify-content: center;
        align-items: center;
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
    }
    /* Left section - 25% of width */
    .kiosk-left {
        width: 25%;
        background-color: lightblue;
        padding: 20px;
        box-sizing: border-box;
        min-height:100%;
    }

    /* Right section - 75% of width */
    .kiosk-right {
        width: 75%;
        background-color: lightgreen;
        padding: 20px;
        box-sizing: border-box;
    }
         
    /** Scrollable/ Swiping  Table **/
    .table-container {
        flex-grow: 1; 
        width: 100%;
        max-height: 70vh; /* Prevents it from overlapping with other elements */
        overflow-y: auto; /* Enable scrolling if content overflows */
        display: flex;
        justify-content: flex-start; /* Ensures content starts at the top */
        align-items: flex-start; /* Align items at the start (top) */
        padding: 0; /* Remove extra padding */
        margin: 0; /* Remove extra margin */
        overflow-x: hidden;
         position: relative;
        border-radius: 8px;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        
        /* For touch devices */
        -webkit-overflow-scrolling: touch;
        
        /* Hide horizontal scrollbar */
        scrollbar-width: thin;
        scrollbar-color: #6b7280 transparent;
    }

/* Customize webkit scrollbar */
.table-container::-webkit-scrollbar {
    width: 8px;
}

.table-container::-webkit-scrollbar-track {
    background: transparent;
}

.table-container::-webkit-scrollbar-thumb {
    background-color: #6b7280;
    border-radius: 20px;
    border: 2px solid transparent;
    background-clip: padding-box;
}

/* Smooth scroll behavior */
.table-container {
    scroll-behavior: smooth;
    scroll-snap-type: y proximity;
}

/* Table styles */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
    padding: 0;
}

/* Header styles */
th {
    position: sticky;
    top: 0;
    background: white;
    z-index: 1;
    padding: 16px;
    text-align: left;
    border-bottom: 2px solid #e5e7eb;
}

/* Cell styles */
td {
    padding: 16px;
    border-bottom: 1px solid #e5e7eb;
    /* Prevent text from being cut */
    white-space: nowrap;
}

/* Add horizontal spacing between cells */
td + td,
th + th {
    margin-left: 12px;
}

/* Row styles */
tr {
    scroll-snap-align: start;
}

/* Hover effect on rows */
tr:hover {
    background-color: #f9fafb;
}

/* Bounce effect at the end of scroll */
.table-container {
    overscroll-behavior-y: auto;
}

/* Optional: Add some padding to ensure last row is fully visible */
.table-container::after {
    content: '';
    display: block;
    padding-bottom: 20px;
}
 /********** End of Tabke swipe/scroll ************************************/

/****** Start of Menu Images display **********/
    .logo-container {
        display: flex;
        justify-content: flex-start;
        gap: 10px; /* Adjusted for smaller images */
        width: 100%;
        padding: 10px 0;
    }

    .logo-slot {
        flex: 0 0 calc(33.333% - 10px); /* Adjust gap size accordingly */
        display: flex;
        justify-content: center;
    }

    .logo-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 70%; /* Narrower for smaller images */
     }

    .logo-image {
        width: 144px; /* Reduced size */
        height: 144px; /* Reduced size */
        position: relative;
    }

    .logo-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .logo-text {
        margin-top: 5px; /* Reduced spacing */
        text-align: center;
        width: 100%;

    }

    .logo-text p {
        margin: 0;
        font-size: 1.1rem; /* Smaller text */
        padding: 1px 0;
        color:black;
    }

    .logo-name {
        font-weight: bold;
    }

    .logo-price {
        color: #666;
    }
    /********* End of Menu Images *****************************/
   
    

    /****  Start Hide scrollbar for a specific component while maintaining scroll functionality */
    .hide-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;     /* Firefox */
        overflow-y: auto;          /* Enable vertical scrolling */
    }
    
    /* Hide webkit (Chrome, Safari, newer versions of Edge) scrollbar */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    
    /* Style for components that should show scrollbar */
    .show-scrollbar {
        overflow-y: auto;
        scrollbar-width: thin;      /* Firefox */
        scrollbar-color: #888 #f1f1f1;  /* Firefox */
    }
    
    /* Webkit scrollbar styling for visible scrollbars */
    .show-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    
    .show-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .show-scrollbar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    
    .show-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    /**************  End of scrollbar *********************************/


    /*****  Start of  Menu like  underline ******/
    .menu-group {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .menu-item {
            cursor: pointer;
            position: relative;
            padding: 4px 0;
        }

        .menu-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: green;
            transition: width 0.3s ease;
        }

        .menu-item.active::after {
            width: 50%;
        }
    
    /******* End of menu like structture *****/
   
    .resize-image-fluid{
        width: 100%;
        height: auto;
        max-width: 144px;
        max-height: 144px;
        object-fit: cover;  /* Ensures the image scales without distortion */
    }
    
/* Shake Animation */
    @keyframes shake {
    0% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(-10px);
    }
    50% {
        transform: translateX(10px);
    }
    75% {
        transform: translateX(-10px);
    }
    100% {
        transform: translateX(0);
    }
    }

    /* Badge Drop Animation */
    @keyframes drop {
    0% {
        top: -50px;
        opacity: 1;
    }
    50% {
        top: 50%;
        transform: translateY(-50%);
        opacity: 1;
    }
    100% {
        top: 50%;
        transform: translateY(-50%);
        opacity: 1;
    }
    }

    /* Wrapper for image and text */
    .image-container {
        display: inline-block;
        position: relative;
        text-align: center;
        margin-top: 30px;
    }

    /* Badge styles */
    .badge {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        font-size: 1rem;
        color: white;
        background-color: rgb(183, 192, 62);
        border-radius: 50%;
        padding: 10px;
        opacity: 0;
        animation: drop 1s forwards;
        animation-delay: 0s; /* Controls when the badge drop starts */
    }

    /* Apply shake to the image */
    .shake {
         animation: shake 0.5s ease-in-out 1s forwards; /* Shake starts after the badge drop */
    }

    /* Message styles: Text appears above the image */
    .message {
        position: absolute;
        top: -60px; /* Position the text above the image */
        left: 50%;
        transform: translateX(-50%); /* Center horizontally */
        font-size: 1.5rem;
        color: rgba(20, 24, 20, 0.822);
        font-weight: bold;
        opacity: 0;
        z-index: 10; /* Ensure it appears above the image */
        width: auto; /* Do not limit width by image */
        white-space: nowrap; /* Prevent wrapping */
        background-color: rgba(255, 255, 255, 0.7); /* Optional: background for contrast */
        border-radius: 5px; /* Optional: rounded corners */
        padding: 10px; /* Optional: padding for better visibility */
        animation: showMessage 0.5s 1.5s forwards; /* Delay to show after shake */
    }

    /* Animation to show the message */
    @keyframes showMessage {
        to {
            opacity: 1;
        }
    }
    /***************** End  ****************************/ 
   
    .resize-image-fluid {
        width: 100%;
        height: auto;
        max-width: 144px;
        max-height: 144px;
        object-fit: cover;  /* Ensures the image scales without distortion */
    }
    .resize-image-fluid64 {
        width: 100%;
        height: auto;
        max-width: 64px;
        max-height: 64px;
        object-fit: cover;  /* Ensures the image scales without distortion */
    }
    .resize-image-fluid100 {
        width: 100%;
        height: auto;
        max-width: 100px;
        max-height: 100px;
        object-fit: cover;  /* Ensures the image scales without distortion */
    }


    
    .menuOption-div { 
        margin-left: 10%;
        margin-right: 10%; 
        height: 75vh; 
        margin-top:10%;
        /* 75% of the viewport height */
     }
    
    /******* Start view order *******/
    /*
    .top-section {
         height: 60vh;
         background-color: #f8d7da;
    }
     .middle-section {
        height: 20vh;
        background-color: #d1ecf1;
    }
    .bottom-section {
        height: 20vh;
        background-color: #d4edda;
    }
    .info-row {
        display: flex;
        align-items: center;
        height: 33.33%;
    }
     .info-label {
        font-weight: bold;
        margin-right: 10px;
    }
    .info-value {
        color: #007bff;
    }
*/

    .top-section {
         height: 65vh;
    }
    .middle-section {
         height: 15vh;
    }
    .bottom-section {
        height: 20vh;
    }    

    /***** End view order *******/
   
    /** Customise */
    .customise-wrapper {
        padding-top: 0px;
        padding-bottom: 0px;
    }
    /** End ** */

    .counter-container {
        display: inline-flex;
        height: 3.5rem; /* Large button height */
    }

    .counter-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 4.5rem; /* Extended width */
        height: 100%;
        background-color: transparent;
        border: 1px solid #6c757d; /* Bootstrap btn-outline-secondary border color */
        color: #6c757d;
        cursor: pointer;
        font-size: 1.25rem;
        transition: all 0.15s ease-in-out;
        padding: 0 0.75rem;
    }

    .counter-btn:hover {
        background-color: #6c757d;
        color: white;
    }

    .counter-minus {
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
        border-right: none;
    }

    .counter-plus {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
        border-left: none;
    }

    .counter-value {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 100%;
        border-top: 1px solid #6c757d;
        border-bottom: 1px solid #6c757d;
        background-color: transparent;
        color: #6c757d;
        font-size: 1.25rem;
    }
    .customise-img {
        max-width: 100%; /* Ensures responsiveness */
        height: auto;    /* Maintains aspect ratio */
        max-height: 64px; /* Adjust the max height as needed */
        object-fit: contain; /* Ensures the image fits within the specified dimensions */
    }

    footer {
        max-height: 10vh; /* Prevents the footer from growing too much */
        position: fixed;
        bottom: 0;
        width: 100%;
    }
    /* view Order */
    .header-div{
        height: 5vh; 
    }
    .top-div {
        display:flex;
        flex-direction: column; /* Stack children vertically */
        flex-shrink: 0; /* Prevent shrinking */
        height: 40vh; /* 50% of viewport height */
       /* background-color: #007bff;  Blue */
       /*  color: white; */
    }
    .middle-div {
        height: 30vh; /* 30% of viewport height */
        /* background-color: #28a745;  Green */
       /*  color: white; */
    }
    .footer-div {
        height: 25vh; /* 20% of viewport height */
       /* background-color: #343a40; */
        /* color: white; */
        position: fixed;
        bottom: 0;
        width: 100%;
    }
    /* For larger screens (lg and above) */
    @media (min-width: 992px) {
        .header-div {
            height: 5vh;
        }
        .top-div {
            height: 70vh;
        }

        .middle-div {
            height: 15vh;
        }

        .footer-div {
            height: 10vh;
        }
    }

    /**  CSS **/
    /* Main virtual keyboard container */
    .virtual-keyboard {
        width: 100%;
        margin: 0 auto;
        padding: 0;
        box-sizing: border-box;
    }

    /* Keyboard wrapper */
    .kb-wrapper {
        width: 100%;
        background-color: #f0f2f5;
        border-radius: 8px;
        padding: 10px;
        box-sizing: border-box;
        box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
    }

    /* Numeric keyboard specific styles */
    .kb-numeric {
        max-width: 300px;
        margin: 0 auto;
    }

    /* Keyboard row */
    .kb-row {
        display: flex;
        justify-content: center;
        margin-bottom: 8px;
        width: 100%;
        box-sizing: border-box;
    }

    .kb-row:last-child {
        margin-bottom: 0;
    }

    /* Basic key button */
    .kb-key {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 45px;
        margin: 0 3px;
        border-radius: 5px;
        background-color: white;
        border: 1px solid #ddd;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.15s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        min-width: 0; /* Prevents overflow */
        max-width: none; /* Allows equal flexing */
        overflow: hidden; /* Prevents content from causing overflow */
        box-sizing: border-box;
    }

    /* Key states */
    .kb-key:hover {
        background-color: #f8f9fa;
        border-color: #ced4da;
    }

    .kb-key:active {
        transform: translateY(2px);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Special keys (like delete and toggle) */
    .kb-special {
        background-color: #e3f2fd;
        color: #1976d2;
        font-weight: bold;
        font-size: 14px;
    }

    /* Delete key */
    .kb-delete {
        background-color: #ffebee;
        color: #d32f2f;
    }

    /* Spacer for middle row alignment */
    .kb-spacer {
        flex: 0.5;
        min-width: 0;
        box-sizing: border-box;
    }

    /* Input field styling */
    .input-group .form-control {
        font-size: 1.5rem;
        letter-spacing: 1px;
        background-color: #f8f9fa;
        border: 2px solid #ddd;
        height: 60px;
    }

    /* Responsive adjustments */
    @media (min-width: 576px) {
        .kb-key {
            height: 50px;
            margin: 0 4px;
            font-size: 18px;
        }

        .kb-special {
            font-size: 15px;
        }

        .kb-row {
            margin-bottom: 10px;
        }

        .kb-wrapper {
            padding: 12px;
        }
    }

    @media (min-width: 768px) {
        .kb-key {
            height: 55px;
            margin: 0 5px;
            font-size: 20px;
        }

        .kb-special {
            font-size: 16px;
        }

        .kb-wrapper {
            padding: 15px;
        }
    }

    @media (min-width: 992px) {
        .virtual-keyboard {
            max-width: 700px;
        }

        .kb-key {
            height: 60px;
        }

        .kb-wrapper {
            padding: 18px;
        }
    }
    .height-5 {
        height: 5%; /* Relative to the parent container */
    }
    .height-10 {
        height: 10%; /* Relative to the parent container */
    }
    .height-40 {
        height: 40%; /* Relative to the parent container */
    }
    .kiosk-height-gap{
        height: 10%; 
    }
 </style>