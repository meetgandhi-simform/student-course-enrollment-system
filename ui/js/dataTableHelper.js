/**
 * DataTable Helper Functions
 *
 * Centralized utilities for DataTables initialization
 */

/**
 * Initialize a DataTable with standard configuration
 *
 * @param {string} tableSelector - jQuery selector for the table element
 * @param {string} ajaxUrl - URL for server-side processing
 * @param {Array} columns - Column configuration array for DataTables
 * @param {Object} options - Additional DataTables options (optional)
 *
 * @return {DataTable} Initialized DataTable instance
 */
function initializeDataTable(tableSelector, ajaxUrl, columns, options = {}) {
  const defaultOptions = {
    processing: true,
    serverSide: true,
    ajax: {
      url: ajaxUrl,
      type: "POST",
      error: function (xhr) {
        console.error(xhr.responseText);
        alert("Something went wrong while fetching data.");
      },
    },
    columns: columns,
    pageLength: options.pageLength || 5,
    responsive: true,
    ...options,
  };

  return $(tableSelector).DataTable(defaultOptions);
}
