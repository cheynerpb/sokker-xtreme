<div id="five_details" class="{{ $view_data['active_tab'] === 'five_details' ? 'active':'fade' }} tab-pane box-border">
        @foreach ($view_data['first_five'] as $item)

          <fieldset>
            <div class="row">
              <div class="col-12">
                <table class="table table-hover table-sm" style="font-size: 24px;">
                  <thead class="thead-dark">
                    <tr class="ml-3">
                        <th scope="col" style="max-width: 3.5vw;" class="text-center" colspan="4">
                            <a href="{{route( 'show_player', ['sokker_id'=> $item->sk_player_id])}}">
                                {{ $item->player_name }}
                            </a>
                        </th>
                        <th scope="col" style="max-width: 3.5vw;" class="text-center" colspan="2">Edad: {{$item->player_age}}</th>
                        <th scope="col" style="max-width: 3.5vw;" class="text-center" colspan="4">Club: {{$item->team}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td rowspan="4" style="vertical-align : middle;text-align:center;"><h3>{{$loop->index + 1}}</h3></td>
                      <td style="max-width: 3.5vw;">{{\Abilities::getAbility($item->stamina)}}</td>
                      <td style="max-width: 3.5vw;">
                        <strong>[ {{$item->stamina}}<span class="text-success">@if($item->differences->diff_stamina > 0) + {{$item->differences->diff_stamina}}@endif</span> ]
                        </strong>
                      </td>
                      <td style="max-width: 3.5vw;" colspan="2">condición</td>
                      <td style="max-width: 3.5vw;">{{\Abilities::getAbility($item->keeper)}}</td>
                      <td style="max-width: 3.5vw;">
                        <strong>[ {{$item->keeper}}<span class="text-success">@if($item->differences->diff_keeper != 0) + {{$item->differences->diff_keeper}}@endif</span> ]
                        </strong>
                      </td>
                      <td style="max-width: 3.5vw;" colspan="2">portería</td>
                      <td rowspan="4" style="vertical-align : middle;text-align:center;"><h3>{{number_format($item->score, 1)}}</h3></td>
                    </tr>
                    <tr>
                      <td style="max-width: 3.5vw;">{{\Abilities::getAbility($item->pace)}}</td>
                      <td style="max-width: 3.5vw;">
                        <strong>[ {{$item->pace}}<span class="text-success">@if($item->differences->diff_pace != 0) + {{$item->differences->diff_pace}}@endif</span> ]
                        </strong>
                      </td>
                      <td style="max-width: 3.5vw;" colspan="2">rapidez</td>
                      <td style="max-width: 3.5vw;">{{\Abilities::getAbility($item->defender)}}</td>
                      <td style="max-width: 3.5vw;">
                        <strong>[ {{$item->defender}}<span class="text-success">@if($item->differences->diff_defender != 0) + {{$item->differences->diff_defender}}@endif</span> ]
                        </strong>
                      </td>
                      <td style="max-width: 3.5vw;" colspan="2">defensa</td>
                    </tr>
                    <tr>
                      <td style="max-width: 3.5vw;">{{\Abilities::getAbility($item->technique)}}</td>
                      <td style="max-width: 3.5vw;">
                        <strong>[ {{$item->technique}}<span class="text-success">@if($item->differences->diff_technique != 0) + {{$item->differences->diff_technique}}@endif</span> ]
                        </strong>
                      </td>
                      <td style="max-width: 3.5vw;" colspan="2">técnica</td>
                      <td style="max-width: 3.5vw;">{{\Abilities::getAbility($item->playmaker)}}</td>
                      <td style="max-width: 3.5vw;">
                        <strong>[ {{$item->playmaker}}<span class="text-success">@if($item->differences->diff_playmaker != 0) + {{$item->differences->diff_playmaker}}@endif</span> ]
                        </strong>
                      </td>
                      <td style="max-width: 3.5vw;" colspan="2">creación</td>
                    </tr>
                    <tr>
                      <td style="max-width: 3.5vw;">{{\Abilities::getAbility($item->passing)}}</td>
                      <td style="max-width: 3.5vw;">
                         <strong>[ {{$item->passing}}<span class="text-success">@if($item->differences->diff_passing != 0) + {{$item->differences->diff_passing}}@endif</span> ]
                        </strong>
                      </td>
                      <td style="max-width: 3.5vw;" colspan="2">pases</td>
                      <td style="max-width: 3.5vw;">{{\Abilities::getAbility($item->striker)}}</td>
                      <td style="max-width: 3.5vw;">
                        <strong>[ {{$item->striker}}<span class="text-success">@if($item->differences->diff_striker != 0) + {{$item->differences->diff_striker}}@endif</span> ]
                        </strong>
                      </td>
                      <td style="max-width: 3.5vw;" colspan="2">anotación</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </fieldset>
        @endforeach

</div>
