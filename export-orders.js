jQuery(document).ready(function ($) {
    $('#export-orders-button').on('click', function (e) {
        e.preventDefault();
        var startDate = $('#start-date').val();
        var endDate = $('#end-date').val();

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'export_orders_between_dates',
                start_date: startDate,
                end_date: endDate,
                security: ajax_object.ajax_nonce
            },
            success: function (response) {
                // Handle the response, e.g., initiate download or display success message
                // For demonstration purposes, log the response to the console
                var blob = new Blob([response], { type: 'text/csv' });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'exported_orders_between_dates.csv';
                link.click();
            },
            error: function (error) {
                // Handle errors
                console.log(error.responseText);
            }
        });
    });

});

(function ($) {
    var postStatus = $('.post_status_page').val(); // Get post_status value
    var category = $('#wfobpp_by_category').val(); // Get wfobpp_by_category value
    var product = $('#wfobpp_by_product').val(); // Get wfobpp_by_product value
    var bydate = $('#filter-by-date').val(); // Get filter_by_date value
    var customerUser = $('.wc-customer-search').val(); // Get _customer_user value

    // Format the date for month-based filtering (if it's in a different format)
    let year = bydate.substring(0, 4); // Extract the first four characters as year
    let month = bydate.substring(4);
    if (year == 0 || month == 0) {
        formattedDate = 0;
    } else {
        var formattedDate = year + '-' + month; // Format the date as Y
    }
    $('#downloadOrders').on('click', function () {

        $.ajax({
            url: ajaxurl, // WordPress AJAX URL
            method: 'GET',
            data: {
                action: 'export_filtered_orders', // AJAX action name
                post_status: postStatus, // Pass post_status filter
                wfobpp_by_category: category, // Pass wfobpp_by_category filter
                wfobpp_by_product: product, // Pass wfobpp_by_product filter
                filter_by_date: formattedDate, // Pass filter_by_date filter
                _customer_user: customerUser, // Pass _customer_user filter
                // Add other filter parameters if required
            },
            success: function (response) {
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'exported_filtered_orders.csv';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
                // Handle AJAX errors
            }
        });
    });
})(jQuery)

