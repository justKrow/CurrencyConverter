// Event listener for DOMContentLoaded event, fires when the HTML document has been completely loaded
document.addEventListener('DOMContentLoaded', function() {
    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Define a callback function to handle state changes of the XHR object
    xhr.onreadystatechange = function() {
        // Check if the request is done
        if (xhr.readyState == XMLHttpRequest.DONE) {
            // Check if the request was successful
            if (xhr.status == 200) {
                // Parse the XML response
                var parser = new DOMParser();
                var xmlDoc = parser.parseFromString(xhr.responseText, "text/xml");
                // Extract currency elements from the XML response
                var currencies = xmlDoc.getElementsByTagName('Currency');
                // Get the select element by ID
                var select = document.getElementById('currency');

                // Iterate through the currency elements
                for (var i = 0; i < currencies.length; i++) {
                    // Extract currency code from each currency element
                    var code = currencies[i].getElementsByTagName('code')[0].childNodes[0].nodeValue;
                    // Create an option element for the currency
                    var option = document.createElement('option');
                    // Set value and text content for the option element
                    option.value = code;
                    option.textContent = code;
                    // Append the option element to the select element
                    select.appendChild(option);
                }
            } else {
                // Log an error message if the request fails
                console.error('An error occurred fetching the currency data');
            }
        }
    };

    // Open a GET request to fetch currency data from the server
    xhr.open('GET', '../src/data/rates.xml', true);
    // Send the request
    xhr.send();
});


// Event listener for form submission
document.getElementById('crudForm').addEventListener('submit', function(e) {
    // Prevent the default form submission behavior
    e.preventDefault();
    // Get the selected action (CRUD operation)
    var action = document.querySelector('input[name="action"]:checked').value;
    // Get the selected currency code
    var currency = document.getElementById('currency').value;

    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();
    // Define a callback function to handle state changes of the XHR object
    xhr.onreadystatechange = function() {
        // Check if the request is done
        if (xhr.readyState == XMLHttpRequest.DONE) {
            // Update the response output with the server's response
            document.getElementById('responseOutput').textContent = xhr.responseText;
        }
    };

    // Open a request with the selected action and currency code as parameters
    xhr.open(action, 'index.php?cur=' + currency + '&action=' + action, true);
    // Send the request
    xhr.send();
});
