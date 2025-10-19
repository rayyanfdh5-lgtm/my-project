<button onclick="printTable()" class="link-secondary">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="lucide lucide-file-down-icon lucide-file-down">
        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
        <path d="M12 18v-6" />
        <path d="m9 15 3 3 3-3" />
    </svg>
    Export
</button>
<script>
    function printTable() {
        const table = document.querySelector('table').outerHTML;
        const style = `
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-family: sans-serif;
                }
                th, td {
                    border: 1px solid #999;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background: #f1f5f9;
                }
                td span.label {
                    all: unset;
                }
            </style>
        `;

        const win = window.open('', '', 'height=600,width=800');
        win.document.write('<html><head><title>Print History Table</title>');
        win.document.write(style);
        win.document.write('</head><body>');
        win.document.write('<h2>Inventory History</h2>');
        win.document.write(table);
        win.document.write('</body></html>');
        win.document.close();
        win.print();
    }
</script>
