jQuery(document).ready(function($) {
    'use strict';

    /* Pre-load json Data */

    $('#fpi-json-single-data').on('click', (e) => {
        $('#fpi-json-input').load(fpi_js_data.single_json_file)
    });

    $('#fpi-json-large-data').on('click', (e) => {
        $('#fpi-json-input').load(fpi_js_data.large_json_file)
    });

    $('#fpi-json-file-upload').on('change', (e) => {
        let reader = new FileReader();
        reader.onload = () => {
            $('#fpi-json-input').html(reader.result);
        };
        reader.readAsText(e.currentTarget.files[0]);
    })

    /* Submit function */

    $('#fpi-json-submit').on('click', (e) => {
        let jsonData = $('#fpi-json-input').text();
        $.ajax({
            type: 'POST',
            url: fpi_js_data.admin_ajax,
            data: {
                action: 'fpi_form_submit',
                nonce: fpi_js_data.nonce,
                jsonData: jsonData,
            },
            success: (result) => {
                console.log(result);
                for (let item of result) {
                    $('#fpi-body').append(
                        '<p>' + item.msg + '</p>'
                    );
                }
                $('#fpi-body').append(
                    '<p>Completed</p>'
                );
            }
        });
    });
    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
});