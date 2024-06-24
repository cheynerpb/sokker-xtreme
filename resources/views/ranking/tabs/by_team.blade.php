<div id="by_team" class="{{ $view_data['active_tab'] === "by_team" ? "active":"fade" }} tab-pane box-border">
    <div class="row">
      <table class="table table-hover table-sm" id="by_team" style="font-size: 24px;">
          <thead class="thead-dark">
            <tr >
              <th scope="col">Club</th>
              <th scope="col">Cantidad</th>
            </tr>
          </thead>
          <tbody>

            @foreach($view_data['groupByTeam'] as $key => $data)
                <tr>
                  <th scope="row">{{ $data->team }}</th>
                  <td>{{ $data->count_player }}</td>
                </tr>
            @endforeach
          </tbody>
        </table>
    </div>
</div>
