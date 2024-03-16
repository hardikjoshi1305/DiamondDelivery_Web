<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div style="overflow-x:auto; max-width: 100%;">
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #ccc;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ccc;font-size:5px;">SHIPMENTDATE</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">SHIPMENTMODE</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">SELLDATE</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">AGENTNAME</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">SOLDBY</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">CLIENT</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">TOTAL</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">AMOUNT</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">WEIGHT</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">ACCEPTWEIGHT</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">REMAININGWEIGHT</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">AMOUNT</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">REASON</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">SHAPE</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">COLOR</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">CLARITY</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">CUT</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">POL</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">SYMM</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">FLORO</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">LAB</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">LABNO</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">PCS</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">RATE</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">MM1</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">MM2</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">MM3</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">TABLE</th>
                    <th style="border: 1px solid #ccc;font-size:5px;">TD</th>
                </tr>
            </thead>
            <tbody style="max-height: 300px; overflow-y: scroll;">
                @foreach ($agent as $row)
                    <tr>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->shipment_date }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->shipment_mode }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->sell_date }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->agent }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->sold_by }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->client }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->total }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->amount }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->tweight }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->Total_selling_weight }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->remaining_weight }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->amount }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->reason }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->shape }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->color }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->clarity }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->cut }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->pol }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->symm }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->floro }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->lab }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->lab_no }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->pcs }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->rate }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->mm1 }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->mm2 }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->mm3 }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->table }}</td>
                        <td style="border: 1px solid #ccc;font-size:5px;">{{ $row->td }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
