@extends('layouts.app')
@section('styles')
    <style>
        td input {
            max-width: 30px;
        }
    </style>
@endsection
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Tabla de puntajes por habilidad
                </div>

                <div class="card-body">
                   <!-- Contenido Aqui -->
                   <table class="table table-hover table-sm text-center col-6 offset-3">
                      <thead class="thead-dark">
                        <tr >
                          <th scope="col">Nivel</th>
                          <th scope="col">Condición</th>
                          <th scope="col">Rapidez</th>
                          <th scope="col">Resto</th>
                        </tr>
                      </thead>
                      <tbody>
                          <tr>
                            <td>Terrible [1]</td>
                            <td>0.1</td>
                            <td>1.4</td>
                            <td>1.2</td>
                          </tr>
                          <tr>
                            <td>Deficiente [2]</td>
                            <td>0.2</td>
                            <td>1.7</td>
                            <td>1.4</td>
                          </tr>
                          <tr>
                            <td>Pobre [3]</td>
                            <td>0.3</td>
                            <td>1.9</td>
                            <td>1.6</td>
                          </tr>
                          <tr>
                            <td>Débil [4]</td>
                            <td>0.4</td>
                            <td>2.2</td>
                            <td>1.8</td>
                          </tr>
                          <tr>
                            <td>Regular [5]</td>
                            <td>0.5</td>
                            <td>2.4</td>
                            <td>2.0</td>
                          </tr>
                          <tr>
                            <td>Aceptable [6]</td>
                            <td>0.6</td>
                            <td>2.6</td>
                            <td>2.2</td>
                          </tr>
                          <tr>
                            <td>Bueno [7]</td>
                            <td>0.7</td>
                            <td>3.0</td>
                            <td>2.5</td>
                          </tr>
                          <tr>
                            <td>Sólido [8]</td>
                            <td>0.8</td>
                            <td>3.4</td>
                            <td>2.8</td>
                          </tr>
                          <tr>
                            <td>Muy Bueno [9]</td>
                            <td>1.0</td>
                            <td>3.7</td>
                            <td>3.1</td>
                          </tr>
                          <tr>
                            <td>Excelente [10]</td>
                            <td>1.2</td>
                            <td>4.1</td>
                            <td>3.4</td>
                          </tr>
                          <tr>
                            <td>Formidable [11]</td>
                            <td>1.5</td>
                            <td>4.6</td>
                            <td>3.8</td>
                          </tr>
                          <tr>
                            <td>Destacado [12]</td>
                            <td></td>
                            <td>5.0</td>
                            <td>4.2</td>
                          </tr>
                          <tr>
                            <td>Increíble [13]</td>
                            <td></td>
                            <td>5.5</td>
                            <td>4.6</td>
                          </tr>
                          <tr>
                            <td>Brillante [14]</td>
                            <td></td>
                            <td>6.0</td>
                            <td>5.0</td>
                          </tr>
                          <tr>
                            <td>Mágico [15]</td>
                            <td></td>
                            <td>7.2</td>
                            <td>6.0</td>
                          </tr>
                           <tr>
                            <td>Sobrenatural [16]</td>
                            <td></td>
                            <td>8.4</td>
                            <td>7.0</td>
                          </tr>
                           <tr>
                            <td>Divino [17]</td>
                            <td></td>
                            <td>9.6</td>
                            <td>8.0</td>
                          </tr>
                           <tr>
                            <td>Superdivino [18]</td>
                            <td></td>
                            <td>11.5</td>
                            <td>9.5</td>
                          </tr>
                      </tbody>
                    </table>
                </div>
                <div class="card-footer text-muted">
                    <div class="row">
                        <div class="col-md-6">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){

    });
</script>

@endsection
