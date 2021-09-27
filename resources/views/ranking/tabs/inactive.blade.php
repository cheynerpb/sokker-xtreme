<div id="inactive" class="{{ $view_data['active_tab'] === "inactive" ? "active":"fade" }} tab-pane box-border">
    <div class="row">
      <table class="table table-hover table-sm" id="inactive" style="font-size: 24px;">
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

            @foreach($view_data['inactive_data'] as $key => $data)
                <tr>
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
