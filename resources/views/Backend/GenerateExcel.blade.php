<table id="bootstrap-data-table-export" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th><strong>ID</strong></th>
            <th><strong>SHIPMENTDATE</strong></th>
            <th><strong>SHIPMENTMODE</strong></th>
            <th><strong>SNNO</strong></th>
            <th><strong>SELLDATE</strong></th>
            <th><strong>SOLDBY</strong></th>
            <th><strong>CLIENT</strong></th>
            <th><strong>CONTACTNO</strong></th>
            <th><strong>SHAPE</strong></th>
            <th><strong>WEIGHT</strong></th>
            <th><strong>PCS</strong></th>
            <th><strong>COLOR</strong></th>
            <th><strong>CLARITY</strong></th>
            <th><strong>CUT</strong></th>
            <th><strong>POL</strong></th>
            <th><strong>SYMM</strong></th>
            <th><strong>FLORO</strong></th>
            <th><strong>LAB</strong></th>
            <th><strong>LABNO</strong></th>
            <th><strong>MM1</strong></th>
            <th><strong>MM2</strong></th>
            <th><strong>MM3</strong></th>
            <th><strong>TABLE</strong></th>
            <th><strong>TD</strong></th>
            <th><strong>RATE</strong></th>
            <th><strong>TOTAL</strong></th>
            <th><strong>AGENTNAME</strong></th>
            <th><strong>REMAININGWEIGHT</strong></th>
            <th><strong>STATUS</strong></th>
        </tr>
    </thead>
    <tbody>
      @foreach($data as $row)
      <tr>
        <td>{{ $row->id }}</td>
        <td>{{ $row->shipment_date }}</td>
        <td>{{ $row->shipment_mode }}</td>
        <td>{{ $row->sn_no }}</td>
        <td>{{ $row->sell_date }}</td>
        <td>{{ $row->sold_by }}</td>
        <td>{{ $row->client }}</td>
        <td>{{ $row->contact_no }}</td>
        <td>{{ $row->shape }}</td>
        <td>{{ $row->weight }}</td>
        <td>{{ $row->pcs }}</td>
        <td>{{ $row->color }}</td>
        <td>{{ $row->clarity }}</td>
        <td>{{ $row->cut }}</td>
        <td>{{ $row->pol }}</td>
        <td>{{ $row->symm }}</td>
        <td>{{ $row->floro }}</td>
        <td>{{ $row->lab }}</td>
        <td>{{ $row->lab_no }}</td>
        <td>{{ $row->mm1 }}</td>
        <td>{{ $row->mm2 }}</td>
        <td>{{ $row->mm3 }}</td>
        <td>{{ $row->table }}</td>
        <td>{{ $row->td }}</td>
        <td>{{ $row->rate }}</td>
        <td>{{ $row->total }}</td>
        <td>{{ $row->agent }}</td>
        <td>{{ $row->remaining_weight }}</td>
        <td>{{ $row->status }}</td>
      </tr>
      @endforeach
    </tbody>
</table>
