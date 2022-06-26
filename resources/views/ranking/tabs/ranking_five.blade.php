<div id="ranking_five" class="{{ $view_data['active_tab'] === "ranking_five" ? "active":"fade" }} tab-pane box-border">
    <div class="row">
      <table class="table table-hover table-sm" id="ranking_all" style="font-size: 24px;">
          <thead class="thead-dark">
            <tr >
              <th scope="col">No.</th>
              <th scope="col">Nombre</th>
              <th scope="col">Club</th>
              <th scope="col">Edad</th>
              <th scope="col">Puntuación</th>
            </tr>
          </thead>
          <tbody>


            @foreach($view_data['data'] as $key => $data)
                @php
                    $class = '';
                    switch ($key) {
                        case 0:
                            $class = 'table-success';
                            break;
                        case 1:
                            $class = 'table-primary';
                            break;
                        case 2:
                            $class = 'table-danger';
                            break;
                    }

                @endphp
                <tr class="{{$class}}">
                  <th scope="row">{{ $key + 1 }}</th>
                  <td>
                    <a href="{{route( 'show_player', ['sokker_id'=> $data->sk_player_id])}}">
                            {{ $data->player_name }}
                    </a>
                  </td>
                  <td>{{ $data->team }}</td>
                  <td>{{ $data->player_age }}</td>
                  <td>{{ number_format($data->max_score, 1) }}</td>
                </tr>
            @endforeach
          </tbody>
        </table>
    </div>
</div>
