import PerfectScrollbar from 'perfect-scrollbar';
window.PerfectScrollbar = PerfectScrollbar;
import Instascan from 'instascan';
import onScan from 'onscan.js';

require('./bootstrap');
require('./custom')
window.Instascan = Instascan;

document.addEventListener('DOMContentLoaded', () => {
    onScan.attachTo(document, {
        onScan: function (scannedCode) {
            console.log('Scanned: ', scannedCode);
            document.getElementById('barcode').value = scannedCode;

            // Send the scanned data to the server
            fetch('/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ code: scannedCode })
            })
            .then(response => response.json())
            .then(data => console.log(data))
            .catch(error => console.error('Error:', error));
        }
    });
});