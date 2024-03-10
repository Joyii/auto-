<!DOCTYPE html>
<head>
    <title>Excel File Drag and Preview</title>
    <style>
        #drop-zone {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            font-size: 18px;
            color: #888;
        }
    </style>
</head>
<body>
<h1>拖拽 Excel 文件到这里</h1>
<div id="drop-zone">
    拖放 Excel 文件到这里
</div>
<div id="preview"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<script>
    const dropZone = document.getElementById('drop-zone');
    const previewDiv = document.getElementById('preview');

    dropZone.addEventListener('dragover', handleDragOver, false);
    dropZone.addEventListener('drop', handleFileSelect, false);

    function handleDragOver(e) {
        e.stopPropagation();
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
    }

    function handleFileSelect(e) {
        e.stopPropagation();
        e.preventDefault();

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const data = e.target.result;
                const workbook = XLSX.read(data, { type: 'binary' });
                const sheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[sheetName];
                const html = XLSX.utils.sheet_to_html(worksheet);
                previewDiv.innerHTML = html;
            }

            reader.readAsBinaryString(file);
        }
    }
</script>
</body>
