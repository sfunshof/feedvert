<style>
    .custom-container {
        height: 60vh;  /* so that no scrollbar appears */
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .custom-rectangle {
        width: 50%;
        height: 50vh;
    }
    .button-container {
        display: flex;
        justify-content: space-between;
        width: 100%;
    }
    .fixed-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        height: 80px;
    }
    
    .header-divider {
        border-right: 2px solid #dee2e6;
        height: 100%;
    }
    body {
        padding-top: 90px;
    }
    .order-box {
        border: 2px solid #0d6efd;
        border-radius: 10px;
        padding: 5px;
        margin-bottom: 20px;
        text-align: center;
        font-size: 4rem;
        font-weight: bold;
        background-color: #e7f1ff;
        transition: all 0.3s ease;
    }
    .order-box:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .ready-box {
        border: 2px solid #198754;
        background-color: #d1e7dd;
    }
    .big-rectangle {
        border: 3px dashed #6c757d;
        border-radius: 15px;
        /* height: 200px; */
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        font-size: 12rem;
        color: #6c757d;
    }

    .toggle-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }
        
 
        /* Increase size of close button */
        .snapDialog-item .snapDialog-close {
            width: 44px !important;
            height: 44px !important;
            font-size: 1.5rem !important;
        }
        
        /* Increase size of action buttons */
        .snapDialog-action {
            padding: 12px 24px !important;
            font-size: 1.5rem !important;
        }
        
        /* Ensure icon is centered and sized properly */
        .snapDialog-close i {
            display: none !important;
        }
    

  </style>