@extends('adminlte::page')

@section('title', 'Subir Póliza')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Subir Pólizas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('companias.index') }}">Compañía</a></li>
        <li class="breadcrumb-item active">Registrar</li>
    </ol>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('polizas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="compania_id" class="form-label">Compañía</label>
                    <select class="form-select" name="compania_id" id="compania_id" required>
                        <option value="" disabled selected>Seleccione una compañía</option>
                        @foreach ($companias as $compania)
                            <option value="{{ $compania->id }}">{{ $compania->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tipo_seguro_id" class="form-label">Tipo de Seguro</label>
                    <select class="form-select" name="tipo_seguro_id" id="tipo_seguro_id" required>
                        <option value="" disabled selected>Seleccione un tipo de seguro</option>
                        @foreach ($seguros as $seguro)
                            <option value="{{ $seguro->id }}">{{ $seguro->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="pdf" class="form-label">Archivo PDF</label>
                    <input class="form-control" type="file" name="pdf[]" multiple required>
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn">Subir</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal para captura de datos faltantes -->
<div class="modal fade" id="dataCaptureModal" tabindex="-1" aria-labelledby="dataCaptureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataCaptureModalLabel">Capturar Información Faltante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="pdfViewer" style="height: 500px; border: 1px solid #ccc;"></div>
                <form id="dataCaptureForm">
                    <div class="mb-3 mt-3">
                        <label for="missingData" class="form-label">Información Faltante</label>
                        <input type="text" class="form-control" id="missingData" name="missingData" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
@stop

@section('js')
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('submitBtn').addEventListener('click', function(event) {
            event.preventDefault();
            
            // Aquí puedes verificar si falta información y abrir el modal
            // Por ejemplo, si falta algún dato específico extraído del PDF
            var dataMissing = true; // Supongamos que falta información

            if (dataMissing) {
                var myModal = new bootstrap.Modal(document.getElementById('dataCaptureModal'));
                myModal.show();

                // Previsualización del PDF
                var pdfUrl = URL.createObjectURL(document.querySelector('input[type="file"]').files[0]);
                pdfjsLib.getDocument(pdfUrl).promise.then(function(pdfDoc_) {
                    pdfDoc = pdfDoc_;
                    renderPage(1);
                });
            } else {
                document.querySelector('form').submit();
            }
        });

        document.getElementById('dataCaptureForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Captura y procesa los datos adicionales aquí
            var missingData = document.getElementById('missingData').value;
            console.log('Información capturada: ' + missingData);
            
            // Luego, puedes enviar el formulario principal
            document.querySelector('form').submit();
        });
    });

    var pdfDoc = null,
        pageNum = 1,
        canvas = document.createElement('canvas'),
        ctx = canvas.getContext('2d');

    function renderPage(num) {
        pdfDoc.getPage(num).then(function(page) {
            var viewport = page.getViewport({ scale: 1.5 });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            var renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            page.render(renderContext).promise.then(function() {
                document.getElementById('pdfViewer').appendChild(canvas);
            });
        });
    }
</script>
@stop
