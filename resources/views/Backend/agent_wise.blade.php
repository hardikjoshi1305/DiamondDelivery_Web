<table id="bootstrap-data-table-export" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th><strong>SHIPMENTDATE</strong></th>
            <th><strong>SHIPMENTMODE</strong></th>
            <th><strong>SELLDATE</strong></th>
            <th><strong>AGENTNAME</strong></th>
            <th><strong>SOLDBY</strong></th>
            <th><strong>CLIENT</strong></th>
            <th><strong>CONTACTNO</strong></th>
            <th><strong>TOTAL</strong></th>
            <th><strong>AMOUNT</strong></th>
            <th><strong>WEIGHT</strong></th>
            <th><strong>ACCEPTWEIGHT</strong></th>
            <th><strong>REMAININGWEIGHT</strong></th>
            <th><strong>SHAPE</strong></th>
            <th><strong>COLOR</strong></th>
            <th><strong>CLARITY</strong></th>
            <th><strong>CUT</strong></th>
            <th><strong>POL</strong></th>
            <th><strong>SYMM</strong></th>
            <th><strong>FLORO</strong></th>
            <th><strong>LAB</strong></th>
            <th><strong>LABNO</strong></th>
            <th><strong>PCS</strong></th>
            <th><strong>RATE</strong></th>
            <th><strong>MM1</strong></th>
            <th><strong>MM2</strong></th>
            <th><strong>MM3</strong></th>
            <th><strong>TABLE</strong></th>
            <th><strong>TD</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($agent as $agents)
        <tr>
            <td>{{ $agents->shipment_date }}</td>
            <td>{{ $agents->shipment_mode }}</td>
            <td>{{ $agents->sell_date }}</td>
            <td>{{ $agents->agent }}</td>
            <td>{{ $agents->sold_by}}</td>
            <td>{{ $agents->client }}</td>
            <td>{{ $agents->contact_no }}</td>
            <td>{{ $agents->total }}</td>
            <td>{{ $agents->amount }}</td>
            <td>{{ $agents->tweight }}</td>
            <td>{{ $agents->weight }}</td>
            <td>{{ $agents->remaining_weight }}</td>
            <td>{{ $agents->shape }}</td>
            <td>{{ $agents->color }}</td>
            <td>{{ $agents->clarity }}</td>
            <td>{{ $agents->cut }}</td>
            <td>{{ $agents->pol }}</td>
            <td>{{ $agents->symm }}</td>
            <td>{{ $agents->floro }}</td>
            <td>{{ $agents->lab }}</td>
            <td>{{ $agents->lab_no }}</td>
            <td>{{ $agents->pcs }}</td>
            <td>{{ $agents->rate }}</td>
            <td>{{ $agents->mm1 }}</td>
            <td>{{ $agents->mm2 }}</td>
            <td>{{ $agents->mm3 }}</td>
            <td>{{ $agents->table }}</td>
            <td>{{ $agents->td }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
