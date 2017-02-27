<div class="table-responsive">

    <table class="table table-striped table-bordered jambo_table bulk_action" id="datatable-buttons">
        <thead>
        <tr class="headings">

            <th class="column-title">User </th>
            <th class="column-title">Host </th>
            <th class="column-title">Db </th>
            <th class="column-title">Command </th>
            <th class="column-title">Time </th>
            <th class="column-title">State </th>

            <th class="column-title last">Info</th>
        </tr>
        </thead>

        <tbody>

        @foreach($proccesses as $pr)
        <tr class="even pointer">

            <td>{{$pr->User}}</td>
            <td>{{$pr->Host}}</td>
            <td>{{$pr->db}}</td>
            <td>{{$pr->Command}}</td>
            <td>{{$pr->Time}}</td>
            <td>{{$pr->State}}</td>
            <td>{{$pr->Info}}</td>


        </tr>
            @endforeach


        </tbody>
    </table>

</div>