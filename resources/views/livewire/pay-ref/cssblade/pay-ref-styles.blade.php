<style>
   /* Main table container */
.scrollable-table-container {
  position: relative;
  max-height: 90%;
  overflow-y: auto;
  overflow-x: hidden;
  border-radius: 6px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS devices */
}

/* Bootstrap 5 primary color for scrollbar */
.scrollable-table-container::-webkit-scrollbar {
  width: 8px;
}

.scrollable-table-container::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.scrollable-table-container::-webkit-scrollbar-thumb {
  background-color: #0d6efd; /* Bootstrap 5 primary color */
  border-radius: 4px;
}

/* Firefox scrollbar styling */
.scrollable-table-container {
  scrollbar-width: thin;
  scrollbar-color: #0d6efd #f1f1f1;
}

/* Table styling */
.scrollable-table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

/* Header styling */
.scrollable-table thead {
  position: sticky;
  top: 0;
  z-index: 1;
}

.scrollable-table th {
  background-color: #f8f9fa;
  padding: 12px 15px;
  text-align: left;
  font-weight: bold;
  color: #212529;
  border-bottom: 2px solid #dee2e6;
}

/* Row styling */
.scrollable-table tbody tr {
  border-bottom: 1px solid #dee2e6;
}

.scrollable-table tbody tr:nth-child(even) {
  background-color: #f8f9fa;
}

.scrollable-table tbody tr:hover {
  background-color: rgba(13, 110, 253, 0.1); /* Light primary color for hover */
}

/* Cell styling */
.scrollable-table td {
  padding: 12px 15px;
  vertical-align: middle;
}

/* Mobile optimizations */
@media screen and (max-width: 767px) {
  /* Increase cell size for better touch targets */
  .scrollable-table td {
    padding: 14px 12px;
    min-height: 44px; /* Apple's recommended minimum touch target size */
  }
  
  /* Button specific styling for better touch targets */
  .scrollable-table td button,
  .scrollable-table td .btn {
    min-height: 44px;
    min-width: 44px;
    padding: 10px 12px;
    margin: 4px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    touch-action: manipulation; /* Optimize for touch */
  }
  
  /* Action cell specific styling */
  .scrollable-table td:last-child {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: flex-start;
  }
  
  /* Optional: For very small screens, make buttons full width */
  @media screen and (max-width: 400px) {
    .scrollable-table td:last-child {
      flex-direction: column;
    }
    
    .scrollable-table td button,
    .scrollable-table td .btn {
      width: 100%;
    }
  }
  
  /* Optimize table width for small screens */
  .scrollable-table {
    table-layout: auto;
  }
  
  /* Increase font size slightly for better readability */
  .scrollable-table {
    font-size: 16px; /* Minimum recommended font size for mobile */
  }
  
  /* Prevent text selection when tapping buttons */
  .scrollable-table td button,
  .scrollable-table td .btn {
    user-select: none;
    -webkit-user-select: none;
  }
}

/* Tablet optimizations */
@media screen and (min-width: 768px) and (max-width: 1024px) {
  .scrollable-table td button,
  .scrollable-table td .btn {
    min-height: 40px;
    padding: 8px 12px;
    margin: 2px;
  }
  
  /* Add some spacing between buttons on tablets */
  .scrollable-table td:last-child {
    padding: 8px 12px;
    display: flex;
    gap: 8px;
  }
}
</style>