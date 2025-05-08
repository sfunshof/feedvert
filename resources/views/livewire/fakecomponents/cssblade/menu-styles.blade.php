<style>
    .rectangle-container {
      display: flex;
      justify-content: space-between;
      gap: 20px; /* Adjustable gap between rectangles */
      margin-bottom: 20px;
    }

    .rectangle {
      flex: 1;
      aspect-ratio: 4 / 3; /* This creates a 75% height relative to width */
      background-color: #f8f9fa;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 8px;
      font-size: 16px;
      border: 2px solid #343a40; /* Clear border for each box */
    }

    .border-line {
      height: 4px;
      background-color: #343a40;
      margin-bottom: 20px;
    }

    .bottom-wrapper {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: calc((100% - 80px) / 7); /* Matching width of top rectangles */
    }

    .bottom-border-line {
      height: 4px;
      background-color: #343a40;
      margin-bottom: 10px;
      width: 100%; /* Ensure the line spans the full width of the wrapper */
    }

    /* Remove specific styling for bottom-rectangle */
    .bottom-rectangle {
      /* Styles are inherited from .rectangle */
    }


    .scroll-container {
        height: 150px;
        overflow-y: auto;
    }
    
    .list-group-item {
        transition: background-color 0.2s;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    /* Custom scrollbar styling */
    .scroll-container::-webkit-scrollbar {
        width: 8px;
    }

    .scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .scroll-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .scroll-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
  </style>