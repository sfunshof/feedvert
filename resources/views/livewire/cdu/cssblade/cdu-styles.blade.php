<style>
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
    /* Main container styling */
.main-container {
    width: 100%;
    max-width: 400px; /* Adjust as needed */
    margin: 0 auto; /* Center the container */
    padding: 20px;
    background-color: #f8f9fa; /* Light background for contrast */
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

/* Scrollable container styling */
.scrollable-container {
    max-height: 100%; /* Set a fixed height to enable scrolling */
    overflow-y: auto; /* Enable vertical scrolling */
    padding: 15px;
    background-color: white /* White background for content */
    border: 1px solid #ced4da; /* Subtle border */
    border-radius: 8px;
}

/* Custom scrollbar styling */
.custom-scrollbar::-webkit-scrollbar {
    width: 10px; /* Width of the scrollbar */
}

.custom-scrollbar::-webkit-scrollbar-track {
    background-color: #e9ecef; /* Light gray track */
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #0d6efd; /* Bootstrap 5 primary color */
    border-radius: 10px;
    border: 2px solid #ffffff; /* Add a border to the thumb for better aesthetics */
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: #0b5ed7; /* Darker shade on hover */
}

/* For Firefox */
.custom-scrollbar {
    scrollbar-color: #0d6efd #e9ecef; /* Thumb and track colors */
    scrollbar-width: thin; /* Thin scrollbar */
}
</style>