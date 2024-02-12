document.addEventListener('DOMContentLoaded', function() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            if (xhr.status == 200) {
                var parser = new DOMParser();
                var xmlDoc = parser.parseFromString(xhr.responseText, "text/xml");
                var currencies = xmlDoc.getElementsByTagName('Currency');
                var select = document.getElementById('currency');

                for (var i = 0; i < currencies.length; i++) {
                    var code = currencies[i].getElementsByTagName('code')[0].childNodes[0].nodeValue;
                    var option = document.createElement('option');
                    option.value = code;
                    option.textContent = code;
                    select.appendChild(option);
                }
            } else {
                console.error('An error occurred fetching the currency data');
            }
        }
    };
    xhr.open('GET', '../src/data/rates.xml', true);     
    xhr.send();
});


// Continue in app.js
document.getElementById('crudForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var action = document.querySelector('input[name="action"]:checked').value;
    var currency = document.getElementById('currency').value;

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            document.getElementById('responseOutput').textContent = xhr.responseText;
        }
    };

    xhr.open(action, 'index.php?cur=' + currency + '&action=' + action, true);
    xhr.send();
});
